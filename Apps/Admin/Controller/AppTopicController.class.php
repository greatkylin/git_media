<?php
/**
 * 游戏库专题控制器
 * @author: xy
 * @since: 2017/07/07 10:40
 */

namespace Admin\Controller;

use Admin\Service\AppService;
use Admin\Service\AppTopicService;
use Think\Controller;
use Admin\Controller\AdminBaseController;

class AppTopicController extends AdminBaseController
{

    public function __construct()
    {
        //控制器初始化
        parent::__construct();
    }

    /**
     * 游戏专题页列表管理
     * @author xy
     * @since 2017/07/07 16:19
     */
    public function app_topic_list(){
        $where = '';
        $title = trim(I('title'));
        if(!empty($title)){
            $where['topic_name'] = array('like',array('%'.$title.'%'));
        }
        $service = new AppTopicService();
        $totalCount = $service->getTopicListCount($where);//统计一共有多少条数据
        if($totalCount === false){
            $this->error($service->getFirstError());
            exit;
        }
        $page     = intval(I('p'));
        $pageSize = intval(I('pagesize'));
        $pageSize = $pageSize > 0 ? $pageSize : DEFAULT_PAGE_SIZE; //每页显示条数
        $totalPages = ceil($totalCount / $pageSize);        //总页数
        $page       = $page > $totalPages ? $totalPages : $page;
        $page       = $page > 0 ? $page : 1;
        $currentPage   = $pageSize * ($page-1);
        $this->assign('firstRow', $currentPage);
        $this->assign('page',     $page);
        $this->assign('pages',    $totalPages);
        $this->assign('pagesize', $pageSize);
        $topicList = $service->getTopicList($where,$currentPage,$pageSize);
        if($topicList === false){
            $this->error($service->getFirstError());
            exit;
        }
        $this->assign('title',$title);
        $this->assign('topic_list',$topicList);
        $this->display();
    }

    /**
     * 添加专题标题
     * @author xy
     * @since 2017/07/10
     */
    public function app_topic_add(){
        $topicId = trim(I('topic_id'));
        $topicName = trim(I('topic_name'));
        $topicRangeStart = trim(I('time_range_start'));
        $topicRangeEnd = trim(I('time_range_end'));
        $service = new AppTopicService();
        if(IS_AJAX){
            if(!empty($topicId)){
                if(!is_numeric($topicId)){
                    $this->outputJSON(true,'100001','专题id参数格式不正确');
                }
                $data['topic_id'] = $topicId;
                $data['update_time'] = time();
            }else{
                $data['create_time'] = time();
            }
            if(empty($topicName)){
                $this->outputJSON(true,'100001','专题名称不能为空');
            }
            if(empty($topicRangeStart)||empty($topicRangeEnd)){
                $this->outputJSON(true,'100001','请填写专题的时间区间');
            }
            if($topicRangeStart>$topicRangeEnd){
                $this->outputJSON(true,'100001','开始时间要小于结束时间');
            }
            $data['topic_name'] = $topicName;
            $data['time_range_start'] = strtotime($topicRangeStart);
            $data['time_range_end'] = strtotime($topicRangeEnd);
            $data['admin_id'] = $this->user_info['id'];
            $result = $service->saveAppTopicData($data);
            if($result !== true){
                $this->outputJSON(true,'100001','添加或修改游戏专题失败');
            }
            $this->outputJSON(false,'000000','保存成功');
        }else{
            if(!empty($topicId)){
                if(!is_numeric($topicId)){
                    $this->error('专题id参数格式不正确');
                }
                $topic = $service->loadAppTopicByPk($topicId);
                if($topic === false){
                    $this->error($service->getFirstError());
                    exit;
                }
                if(empty($topic['time_range_start']))
                    $topic['time_range_start'] = time();
                if(empty($topic['time_range_end']))
                    $topic['time_range_end'] = time();
            }else{
                if(empty($topic['time_range_start']))
                    $topic['time_range_start'] = time();
                if(empty($topic['time_range_end']))
                    $topic['time_range_end'] = time();
            }
            $this->assign('topic',$topic);
            $this->display();
        }



    }


    /**
     * 添加专题具体内容
     * @author xy
     * @since 2017/07/10
     */
    public function topic_content_add(){
        //专题id
        $topicId = intval(I('topic_id'));
        $service = new AppTopicService();
        if(IS_AJAX){
            if(empty($topicId)|| !is_numeric($topicId)){
                $this->outputJSON(true,'100001','专题id参数错误');
                return false;
            }
            //当前使用的是哪种类型的专题，1,2,3分别表示模板、编辑器、H5。为空则默认是模板
            $type = intval(I('topic_type'));
            if(empty($type) || !in_array($type,array(1,2,3))){
                $this->outputJSON(true,'100001','专题类型参数错误');
            }

            //1.判断是否要更新至最新时间，并更新topic_id为$topicId当前的专题类型
            $topicData['topic_id'] = $topicId;
            $topicData['topic_type'] = $type;
            $topicData['update_time'] = time();
            $topicData['admin_id'] = $this->user_info['id'];
            //是否更新到最新时间,有则发布时间为当前时间，否则为当前选择的时间
            $updateNow = intval(I('update_now'));
            if(!empty($updateNow)){
                $topicData['publish_time'] = time();
            }else{
                $topicData['publish_time'] = strtotime(I('publish_time'));
            }
            //判断是否要发布
            $topicStatus = intval(I('topic_status'));
            if(!empty($topicStatus)){
                $topicData['is_publish'] = 1;
            }

            //当前的封面图片
            $currentCover = !empty($topicData['cover_image_path']) ? $topicData['cover_image_path'] : '';
            //封面图片
            $topicData['cover_image_path'] = empty(trim(I('img_url_cover'))) ? $currentCover : trim(I('img_url_cover'));

            $content['topic_id'] = $topicId;
            $content['topic_type'] = $type;

            //获取当前的专题内容
            $currentInfo = $service->getCurrentContentByTopicIdAndType($topicId,$type);

            if($type == 1){
                //专题的简介
                $content['introduce'] = empty(trim(I('introduce'))) ? '' : trim(I('introduce'));
                //添加的游戏信息,进行序列化操作
                $appIds = I('app_id');
                $appNames = I('app_name');
                $videoUrls = I('video_url');
                $detailUrl = I('detail_url');
                $downloadUrl = I('download_url');
                $appIntroduce = I('app_introduce');
                if(!empty($appIds)){
                    $appInfo = array();
                    foreach ($appIds as $key=>$val){
                        $appInfo[$val]['app_id'] = $val;
                        $appInfo[$val]['app_name'] = empty($appNames[$key])?'':$appNames[$key];
                        $appInfo[$val]['video_url'] = empty($videoUrls[$key])?'':$videoUrls[$key];
                        $appInfo[$val]['detail_url'] = empty($detailUrl[$key])?'':$detailUrl[$key];
                        $appInfo[$val]['download_url'] = empty($downloadUrl[$key])?'':$downloadUrl[$key];
                        $appInfo[$val]['app_introduce'] = empty($appIntroduce[$key])?'':$appIntroduce[$key];
                    }
                    $appInfoStr = serialize($appInfo);
                }
                $content['content'] = !empty($appInfoStr) ? $appInfoStr : '';

                //抬头图片
                $picUrl = I('pic_url');
                $delPicUrl = I('pic_url_del');
                if(!empty($currentInfo['background_image_path'])){
                    $libPicUrl = explode(',', $currentInfo['background_image_path']);
                    $delPicUrlArr = array();
                    if(!empty($delPicUrl)) {
                        // 删除已经添加都数据库的图片（上传成功后替换的不给删除）
                        if(!empty($libPicUrl)) {
                            foreach($delPicUrl as $dVal) {
                                if(in_array($dVal, $libPicUrl)) {
                                    $delPicUrlArr[] = $dVal;
                                }
                            }
                        }

                    }
                    // 将原有的图片添加进去
                    if(!empty($libPicUrl)) {
                        foreach ($libPicUrl as $lVal) {
                            if(!in_array($lVal, $delPicUrlArr)) {
                                $picUrl[] = $lVal;
                            }
                        }
                    }
                }
                $content['background_image_path'] = implode(',', $picUrl);
            }else if($type == 2){
                //编辑器编辑的内容
                $content['content'] = empty(trim(I('uedit_content'))) ? '' : trim(I('uedit_content'));
            }else{
                //h5 的连接
                $content['content'] = empty(trim(I('h5_content'))) ? '' : trim(I('h5_content'));
            }
            $content['admin_id'] = $this->user_info['id'];
            //2.将表单提交的数据保存到对应的类型的数据中
            $result = $service->saveTopicContent($topicData,$content);
            if($result === true){
                $this->outputJSON(false,'000000','保存成功');
            }else{
                $this->outputJSON(true,'100001',$service->getFirstError());
            }

        }else{
            //报错时跳转的url
            $returnUrl = U('Admin/AppTopic/app_topic_list');
            if(empty($topicId)){
                $this->error('专题id不能为空',$returnUrl);
                exit;
            }
            //1.根据专题id获取专题当前的专题类型
            $topic = $service->loadAppTopicByPk($topicId);
            if($topic === false){
                $this->error('查找数据失败',$returnUrl);
                exit;
            }
            if($topic === null){
                $this->error('未查找专题id为'.$topicId.'的数据',$returnUrl);
                exit;
            }
            if(empty($topic['publish_time'])){
                $topic['publish_time'] = time();
            }
            //设置默认的专题类型为 模板类型
            if(empty($topic['topic_type'])){
                $topic['topic_type'] = 1;
            }
            //2.根据专题id获取专题的内容 模板，编辑器，h5
            $contentList = $service->getTopicContentByTopicId($topicId);
            if($contentList === false){
                $this->error($service->getFirstError(),$returnUrl);
                exit;
            }
            //3.在后台前端页面展示内容
            $this->assign('topic',$topic);
            $this->assign('content_list',$contentList);
            $this->assign('reload_url', U('Admin/AppTopic/topic_content_add',array('topic_id'=>$topicId)));
            $this->display();
        }

    }


    /**
     * 发布或者下架游戏专题
     * @author xy
     * @since 2017/07/30 17:42
     */
    public function publish_status_change(){
        $topicId = intval(I('topic_id'));
        if(empty($topicId)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $topic = M('app_topic')->where(array('topic_id'=>$topicId))->find();
        if(empty($topic)){
            $this->outputJSON(true,'100001','未找到id为'.$topicId.'的专题');
        }

        if($topic['is_publish'] == 1){
            $data['is_publish'] = 2;
        }else{
            $data['is_publish'] = 1;
        }
        M()->startTrans();
        $result = M('app_topic')->where(array('topic_id'=>$topicId))->save($data);
        if(empty($result)){
            M()->rollback();
            $this->outputJSON(true,'100001','改变状态失败');
        }else{
            M()->commit();
            $this->outputJSON(false,'000000','改变状态成功');
        }
    }

    /**
     * 删除或者恢复游戏专题
     * @author xy
     * @since 2017/07/30 17:42
     */
    public function delete_status_change(){
        $topicId = intval(I('topic_id'));
        if(empty($topicId)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $topic = M('app_topic')->where(array('topic_id'=>$topicId))->find();
        if(empty($topic)){
            $this->outputJSON(true,'100001','未找到id为'.$topicId.'的专题');
        }

        if($topic['is_delete'] == 1){
            $data['is_delete'] = 2;
        }else{
            $data['is_delete'] = 1;
        }
        M()->startTrans();
        $result = M('app_topic')->where(array('topic_id'=>$topicId))->save($data);
        if(empty($result)){
            M()->rollback();
            $this->outputJSON(true,'100001','改变状态失败');
        }else{
            M()->commit();
            $this->outputJSON(false,'000000','改变状态成功');
        }
    }

}