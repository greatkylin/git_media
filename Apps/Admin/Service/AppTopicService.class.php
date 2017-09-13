<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/7/30
 * Time: 17:15
 */

namespace Admin\Service;

use Common\Service\BaseService;

class AppTopicService extends BaseService
{
    private $appTopicModel;              //zy_media_app_topic 数据表Model
    private $appContentModel;           //zy_media_app_topic_content 数据表Model

    public function __construct(){

        $this->appTopicModel = M('app_topic');
        $this->appContentModel = M('app_topic_content');
    }

    /**
     * 根据条件获取专题的数量
     * @author xy
     * @since 2017/07/07 17:16
     * @param null $where
     * @return bool
     */
    public function getTopicListCount($where = NULL){
        $count = $this->appTopicModel
            ->field('count(`topic_id`) as num')
            ->where($where)
            ->find();
        if($count === false){
            $this->setError('查询专题列表失败');
            return false;
        }
        return $count['num'];
    }

    /**
     * 根据条件分页获取游戏专题列表
     * @author xy
     * @since 2017/07/07 17:03
     * @param NULL $where 查询条件
     * @return bool|mixed
     */
    public function getTopicList($where = NULL,$currentPage,$pageSize){
        $list = $this->appTopicModel->alias('a')
            ->where($where)
            ->limit($currentPage,$pageSize)
            ->order('publish_time DESC')
            ->select();
        if($list === false){
            $this->setError('查询专题列表失败');
            return false;
        }
        return $list;
    }

    /**
     * 根据主键获取游戏专题
     * @author xy
     * @since 2017/07/07 18:26
     * @param $topicId
     * @return bool|mixed
     */
    public function loadAppTopicByPk($topicId){
        $data = $this->appTopicModel->where('topic_id = '.$topicId)->find();
        if($data === false){
            $this->setError('查询topic_id为'.$topicId.'的数据失败');
            return false;
        }
        return $data;
    }

    /**
     * 添加游戏专题数据到app_topic表
     * @author xy
     * @since 2017/07/07 18:36
     * @param array $data
     * @return bool|mixed
     */
    public function saveAppTopicData(array $data){
        if(!is_array($data)){
            $this->setError('数据格式正确');
            return false;
        }
        if(empty($data['topic_id'])){
            $result = $this->appTopicModel->add($data);
        }else{
            if(!is_numeric($data['topic_id'])){
                $this->setError('topic_id参数格式不正确');
            }
            $result = $this->appTopicModel->where('topic_id = '.$data['topic_id'])->save($data);
        }
        if($result === false){
            $this->setError('保存专题数据失败');
            return false;
        }
        if($result === '0' || $result === 0){
            $this->setError('保存专题数据失败');
            return false;
        }
        return true;
    }

    /**
     * 根据专题id获取所有专题内容
     * @author xy
     * @since 2017/07/10
     * @param integer $topicId 专题id
     * @return bool|mixed
     */
    public function getTopicContentByTopicId($topicId){
        if(empty($topicId)){
            $this->setError('专题id不能为空');
        }
        //查找topic_id为$topicId，状态为未删除的专题内容（模板的，编辑器的，H5）
        $contentList = $this->appContentModel
            ->where('`topic_id` = '.$topicId)
            ->order('topic_type ASC')
            ->select();
        if($contentList === false){
            $this->setError('查找数据失败');
            return false;
        }
        if($contentList !== NULL){
            $returnList = array();
            foreach ($contentList as $key=>$value){
                $returnList[$value['topic_type']] = $value;
                //使用模板类型的专题时，将保存的游戏信息反序列化
                if($value['topic_type'] == 1){
                    $returnList[$value['topic_type']]['content'] = unserialize($value['content']);
                    foreach ($returnList[$value['topic_type']]['content'] as $k=>$val){
                        $returnList[$value['topic_type']]['content'][$k]['video_url'] = htmlspecialchars_decode($val['video_url']);
                    }
                }
            }
            return $returnList;
        }else{
            return $contentList;
        }
    }


    /**
     * 根据专题id与类型id判断是否已经添加了专题内容
     * @author xy
     * @since 2017/07/11 17:30
     * @param integer $topicId 专题id
     * @param integer $type 专题类型
     * @return bool|int
     */
    public function checkIsExistContentByTopicIdAndType($topicId,$type){
        if(empty($topicId) || !is_numeric($topicId)){
            $this->setError('专题id参数错误');
            return false;
        }
        if(!in_array($type,array(1,2,3))){
            $this->setError('专题类型有误');
            return false;
        }
        $data = $this->appContentModel
            ->where('`topic_id` = '.$topicId.' AND `topic_type` = '.$type)
            ->find();
        if($data === false){
            $this->setError($this->appContentModel->getDbError());
            return false;
        }
        if($data === null){
            return 0;
        }else{
            return true;
        }
    }

    /**
     * 保存的专题的内容，并修改专题的类型
     * @author xy
     * @since 2017/07/11 18:00
     * @param array $topicData 保存到app_topic的数据
     * @param array $topicContent 保存到app_topic_content的数据
     * @return bool
     */
    public function saveTopicContent(array $topicData, array $topicContent){
        if(empty($topicData['topic_id'])|| !is_numeric($topicData['topic_id'])){
            $this->setError('专题id参数错误');
            return false;
        }
        if(empty($topicData['topic_type']) || !in_array($topicData['topic_type'],array(1,2,3))){
            $this->setError('专题类型有误');
            return false;
        }
        if(empty($topicContent['topic_id'])){
            $topicContent['topic_id'] = $topicData['topic_type'];
        }
        if(empty($topicContent['topic_type'])){
            $topicContent['topic_type'] = $topicData['topic_type'];
        }
        //判读是否存在已添加的专题内容
        $isExist = $this->checkIsExistContentByTopicIdAndType($topicContent['topic_id'],$topicContent['topic_type']);

        if($isExist === false){
            $this->setError('执行查询语句失败');
            return false;
        }
        $topicData['update_time'] = time();
        $whereContent = '`topic_id` = '.$topicContent['topic_id'].' AND `topic_type` = '.$topicContent['topic_type'];
        $whereTopic = '`topic_id` = '.$topicContent['topic_id'];
        M()->startTrans();
        if($isExist === true){
            //存在执行更新操作
            $topicContent['update_time'] = time();
            //修改专题的内容
            $result = $this->appContentModel->where($whereContent)->save($topicContent);
            if($result == true){
                //修改当前专题的类型
                $result = $this->appTopicModel->where($whereTopic)->save($topicData);
                if($result == true){
                    M()->commit();
                    return true;
                }else{
                    M()->rollback();
                    return false;
                }
            }else{
                M()->rollback();
                return false;
            }
        }else{
            //不存在执行添加操作
            $topicContent['create_time'] = time();
            //添加专题内容
            $result = $this->appContentModel->data($topicContent)->add();
            if($result == true){
                //修改当前专题的类型
                $result = $this->appTopicModel->where($whereTopic)->save($topicData);
                if($result == true){

                    M()->commit();
                    return true;
                }else{
                    M()->rollback();
                    return false;
                }
            }else{
                M()->rollback();
                return false;
            }
        }
    }

    /**
     * 根据专题id与类型id获取已经添加了专题内容
     * @author xy
     * @since 2017/07/12 11:04
     * @param integer $topicId 专题id
     * @param integer $type 专题类型
     * @return bool|int|mixed
     */
    public function getCurrentContentByTopicIdAndType($topicId,$type){
        if(empty($topicId) || !is_numeric($topicId)){
            $this->setError('专题id参数错误');
            return false;
        }
        if(!in_array($type,array(1,2,3))){
            $this->setError('专题类型有误');
            return false;
        }
        $data = $this->appContentModel
            ->where('`topic_id` = '.$topicId.' AND `topic_type` = '.$type)
            ->find();
        if($data === false){
            $this->setError($this->appContentModel->getDbError());
            return false;
        }
        if($data === null){
            return 0;
        }else{
            return $data;
        }
    }
}