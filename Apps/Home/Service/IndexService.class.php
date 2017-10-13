<?php
/**
 * 首页相关的业务操作
 * User: xy
 * Date: 2017/8/29
 * Time: 18:38
 */

namespace Home\Service;
use Common\Service\BaseService;

class IndexService extends BaseService
{
    /**
     * 通过图片分类关键字选取指定数量的图片
     * @author xy
     * @since 2017/08/30 09:42
     * @param string $keyword 图片分类关键字
     * @param int $limit 选取图片数量
     * @return bool
     */
    public function getAdContentListByCategoryKeyword($keyword, $limit = 3){
        if(empty($keyword)){
            $this->setError('请填写分类关键字');
            return false;
        }

        $where = array(
            'acc.is_delete' => 1, //分类未删除
            'c.is_delete' => 1, //图片未删除
            'c.is_publish' => 1, //图片已发布
            'acc.keyword' => strtoupper($keyword), //指定的分类
        );

        $adImageList = M('index_column_category')->alias('acc')
            ->field('acc.keyword, c.*, IF(c.sort=0, 9999999, c.sort) AS sort')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_column_content as c ON c.category_id = acc.id')
            ->where($where)
            ->limit($limit)
            ->order('sort ASC')
            ->select();

        if($adImageList === false){
            return $this->setError('查询失败');
        }

        return $adImageList;
    }

    /**
     * 通过图片分类关键字选取指定数量的图片
     * @author xy
     * @since 2017/08/30 09:42
     * @param int $id 图片分类id
     * @param int $limit 选取图片数量
     * @return bool
     */
    public function getAdContentListByCategoryId($id, $limit = 3){
        if(empty($id) || !is_numeric($id)){
            $this->setError('请填写分类id');
            return false;
        }

        $where = array(
            'acc.is_delete' => 1, //分类未删除
            'c.is_delete' => 1, //图片未删除
            'c.is_publish' => 1, //图片已发布
            'acc.id' => $id, //指定的分类
        );

        $adImageList = M('index_column_category')->alias('acc')
            ->field('acc.keyword, c.*, IF(c.sort=0, 9999999, c.sort) AS sort')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_column_content as c ON c.category_id = acc.id')
            ->where($where)
            ->limit($limit)
            ->order('c.sort ASC')
            ->select();

        if($adImageList === false){
            return $this->setError('查询失败');
        }

        return $adImageList;
    }


    /**
     * 通过分类关键字选取指定数量的新游测评内容
     * @author xy
     * @since 2017/09/03 20:29
     * @param string $keyword 分类关键字
     * @param int $limit 选取数量
     * @return bool
     */
    public function getNewAppNoticeByCategoryKeyword($keyword, $limit = 3){
        if(empty($keyword)){
            $this->setError('请填写分类关键字');
            return false;
        }

        $where = array(
            'acc.is_delete' => 1, //分类未删除
            'c.is_delete' => 1, //未删除
            'c.is_publish' => 1, //已发布
            'acc.keyword' => strtoupper($keyword), //指定的分类
        );

        $contentList = M('index_column_category')->alias('acc')
            ->field('acc.keyword, c.*, IF(c.sort=0, 9999999, c.sort) AS sort, app.*')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_column_content as c ON c.category_id = acc.id')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_app_test_open as app ON app.index_content_id = c.id')
            ->where($where)
            ->limit($limit)
            ->order('sort ASC')
            ->select();

        if($contentList === false){
            return $this->setError('查询失败');
        }

        return $contentList;
    }

    /**
     * 获取指定日期当天的新游预测列表
     * @author xy
     * @since 2017/09/04 09:55
     * @param $timeStamp
     * @return bool
     */
    public function getNewAppNoticeListByDate($timeStamp){
        if(empty($timeStamp)){
            $timeStamp = time();
        }
        $where = array(
            'acc.is_delete' => 1, //分类未删除
            'c.is_delete' => 1, //未删除
            'c.is_publish' => 1, //已发布
            'acc.keyword' => strtoupper('NEW_APP_NOTICE'), //指定的分类 新游预告
            'app.is_delete' => 1, //游戏未删除
            'app.open_test_time' => array(array('egt', $timeStamp), array('lt', $timeStamp+86400), 'AND'), //指定的时间
        );

        $contentList = M('index_column_category')->alias('acc')
            ->field('acc.keyword, c.*, IF(c.sort=0, 9999999, c.sort) AS sort, app.*, IFNULL(alib.icon, lib.icon) as icon')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_column_content as c ON c.category_id = acc.id')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_app_test_open as app ON app.index_content_id = c.id')
            ->join('INNER JOIN '. C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib as lib ON lib.app_id = app.app_id')
            ->join('LEFT JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib as alib ON alib.app_id = app.app_id')
            ->where($where)
            ->order('sort ASC, app.open_test_time ASC')
            ->select();

        if($contentList === false){
            return $this->setError('查询失败');
        }
        return $contentList;
    }

    /**
     * 通过id获取未删除的已发布的活动
     * @author xy
     * @since  2017/09/04 17:41
     * @param int $activityId 活动id
     * @return bool
     */
    public function getValidActivityDetailById($activityId){
        if(empty($activityId)){
            return $this->setError('请填写活动id');
        }
        $where = array(
            'pa.is_publish' => 1, //已上架
            'pa.is_delete' => 1,  //未删除
            'pa.activity_id' => $activityId, //指定的id
        );
        $activity = M('popular_activity')->alias('pa')
            ->field('pa.*')
            ->where($where)
            ->find();
        if($activity === false){
            return $this->setError('查询失败');
        }
        if(empty($activity)){
            return $this->setError('未找到对应的活动数据');
        }
        return $activity;

    }

    /**
     * 通过id获取活动
     * @author xy
     * @since  2017/09/04 17:41
     * @param int $activityId 活动id
     * @return bool
     */
    public function getActivityDetailById($activityId){
        if(empty($activityId)){
            return $this->setError('请填写活动id');
        }
        $where = array(
            'pa.activity_id' => $activityId, //指定的id
        );
        $activity = M('popular_activity')->alias('pa')
            ->field('pa.*')
            ->where($where)
            ->find();
        if($activity === false){
            return $this->setError('查询失败');
        }
        if(empty($activity)){
            return $this->setError('未找到对应的活动数据');
        }
        return $activity;

    }

    /**
     * 根据关键字获取本周推荐专题数据
     * @author xy
     * @since 2017/09/19 17:34
     * @return bool|array
     */
    public function getThisWeekTopicByKeyword(){
        //首页管理分类关键字为 WEEK_RECOMMEND 的本周推荐
        $keyword = 'WEEK_RECOMMEND';
        if(empty($keyword)){
            $this->setError('请填写分类关键字');
            return false;
        }

        $where = array(
            'acc.is_delete' => 1, //分类未删除
            'c.is_delete' => 1, //图片未删除
            'c.is_publish' => 1, //图片已发布
            'acc.keyword' => strtoupper($keyword), //指定的分类
        );

        $recommendTopic = M('index_column_category')->alias('acc')
            ->field('acc.keyword, c.*, IF(c.sort=0, 9999999, c.sort) AS sort')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_column_content as c ON c.category_id = acc.id')
            ->where($where)
            ->find();

        if($recommendTopic === false){
            return $this->setError('查询失败');
        }

        return $recommendTopic;
    }

    /**
     * 根据关键字获取每日一题合集页的每日一题游戏图片
     * @author xy
     * @since 2017/09/20 16:02
     * @param null $limit 查询的数量
     * @return bool|array
     */
    public function getDailyQuestionAppByKeyword($limit = NULL){
        //首页管理分类关键字为 WEEK_RECOMMEND 的本周推荐
        $keyword = 'DAILY_QUESTION_APP';
        if(empty($keyword)){
            $this->setError('请填写分类关键字');
            return false;
        }

        $where = array(
            'acc.is_delete' => 1, //分类未删除
            'c.is_delete' => 1, //图片未删除
            'c.is_publish' => 1, //图片已发布
            'acc.keyword' => strtoupper($keyword), //指定的分类
        );

        $dailyQuesImgList = M('index_column_category')->alias('acc')
            ->field('acc.keyword, c.*, IF(c.sort=0, 9999999, c.sort) AS sort')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_column_content as c ON c.category_id = acc.id')
            ->where($where)
            ->limit($limit)
            ->select();

        if($dailyQuesImgList === false){
            return $this->setError('查询失败');
        }

        return $dailyQuesImgList;
    }

    /**
     * 通过关键字获取关于我们，联系我们等单页内容
     * @author xy
     * @since 2017/10/11 14:41
     * @param string $keyword 关键词
     * @return bool|mixed
     */
    public function getIndependentContentByKeyword($keyword){
        if(empty(trim($keyword))){
            return $this->setError('必填参数缺失');
        }
        $where = array(
            'keyword' => strtoupper($keyword),
            'is_publish' => 1,
            'is_delete' => 1
        );
        $content = M('independent_content')->where($where)->find();
        if($content === false){
            return $this->setError('未找到对应内容');
        }
        if(!empty($content)){
            $content['content'] = htmlspecialchars_decode($content['content']);
        }
        return $content;
    }
}