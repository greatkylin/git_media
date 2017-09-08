<?php
/**
 * 首页相关的业务操作
 * User: xy
 * Date: 2017/8/29
 * Time: 18:38
 */

namespace Home\Service;
use Common\Service\BaseService;

class ArticleService extends BaseService
{
    const ART_SHOW_POSITION_APP = 1;        //文章展示平台 1 APP
    const ART_SHOW_POSITION_MEDIA = 2;      //文章展示平台 2 媒体站

    const ART_STATUS_NO = 0;        //文章发布状态 0 未发布
    const ART_STATUS_PUBLISH = 1;      //文章发布状态 1 发布

    const ART_DELETE_NO = 0;        //文章删除状态 0 未删除
    const ART_DELETE_YES = 1;      //文章删除状态 1 删除

    const ARTICLE_TYPE_APP = 0;         //文章分类类型 0 游戏专题
    const ARTICLE_TYPE_NEWS = 1;        //文章分类类型 1 新闻中心

    const ARTICLE_LEVEL_LOWER = 1;     //文章 初阶
    const ARTICLE_LEVEL_MIDDLE = 2;    //文章 进级
    const ARTICLE_LEVEL_HIGH = 3;      //文章 高阶

    /**
     * 获取指定条数的最新的发布在媒体站每日一题的文章 按时间倒序排序
     * @author xy
     * @since 2017/08/31 15:20
     * @param int $limit
     * @return bool
     */
    public function getLastEveryDayQues($limit = 9){
        $where = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', show_position) AND status = '.self::ART_STATUS_PUBLISH.' AND is_delete = '.self::ART_DELETE_NO;
        // TODO 每日一题的筛选文章库还没做
        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))
            ->where($where)
            ->limit($limit)
            ->order('create_time DESC')
            ->select();
        if($articleList === false){
            return $this->setError('查询失败');
        }
        return $articleList;
    }

    /**
     * 获取指定分类以及该分类子类下的所有展示在媒体站的文章
     * @author xy
     * @since 2017/08/31 16:35
     * @param int $cateId 文章栏目分类id
     * @param int $limit 获取的条数
     * @return bool
     */
    public function getAllAppArticleByCateId($cateId, $limit = 5){
        if(empty($cateId)){
            return $this->setError('必填参数缺失');
        }
        $cateList = self::getAllColumnInfoFromRedis();
        if(empty($cateList)){
            $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
                ->field('catid, cat_name, parent_id')
                ->select();
        }
        //递归获取指定分类以及该分类子类id
        $catIdArr = get_id_array_recursive($cateList,$cateId,$newArray,'parent_id','catid');
        $cateIdStr = implode(',', $catIdArr);
        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', show_position) AND status = '.self::ART_STATUS_PUBLISH.' AND is_delete = '.self::ART_DELETE_NO.' AND catid IN ('.$cateIdStr.')';
        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))
            ->where($where)
            ->limit($limit)
            ->order('create_time DESC')
            ->select();
        if($articleList === false){
            return $this->setError('查询失败');
        }
        return $articleList;
    }

    /**
     * 获取指定分类以及该分类子类下的指定游戏所有展示在媒体站的文章
     * @author xy
     * @since 2017/08/31 16:35
     * @param int $cateId 文章栏目分类id
     * @param int $appId 游戏id
     * @param int $limit 获取的条数
     * @return bool
     */
    public function getAppArticleByCateIdAndAppId($cateId, $appId, $limit = 5){
        if(empty($cateId) || empty($appId)){
            return $this->setError('必填参数缺失');
        }
        $cateList = self::getAllColumnInfoFromRedis();
        if(empty($cateList)){
            $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
                ->field('catid, cat_name, parent_id')
                ->select();
        }
        //递归获取指定分类以及该分类子类id
        $catIdArr = get_id_array_recursive($cateList,$cateId,$newArray,'parent_id','catid');
        $cateIdStr = implode(',', $catIdArr);
        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', show_position) AND status = '.self::ART_STATUS_PUBLISH.' AND is_delete = '.self::ART_DELETE_NO.' AND catid IN ('.$cateIdStr.') AND app_id = '.$appId;
        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))
            ->where($where)
            ->limit($limit)
            ->order('create_time DESC')
            ->select();
        if($articleList === false){
            return $this->setError('查询失败');
        }
        return $articleList;
    }

    /**
     * 获取指定栏目以及该栏目的子栏目下指定游戏的所有阶级文章
     * @author xy
     * @since 2017/09/07 18:22
     * @param int $cateId 栏目id
     * @param int $appId 游戏id
     * @param int $typeId 文章类型id 1表示初阶，2表示进阶，3表示高阶
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @return bool
     */
    public function getAppArticleStrategyByCateIdAndAppId($cateId, $appId, $typeId, $currentPage, $pageSize = 5){
        if(empty($cateId) || empty($appId)){
            return $this->setError('必填参数缺失');
        }
        $cateList = self::getAllColumnInfoFromRedis();
        if(empty($cateList)){
            $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
                ->field('catid, cat_name, parent_id')
                ->select();
        }
        //递归获取指定分类以及该分类子类id
        $catIdArr = get_id_array_recursive($cateList,$cateId,$newArray,'parent_id','catid');
        $cateIdStr = implode(',', $catIdArr);
        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', show_position) AND status = '.self::ART_STATUS_PUBLISH.' AND is_delete = '.self::ART_DELETE_NO.' AND catid IN ('.$cateIdStr.') AND app_id = '.$appId.' AND FIND_IN_SET(\''.$typeId.'\', typeids)' ;
        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*, IF(a.attr = 0, 999999999, a.attr) as new_attr')
            ->where($where)
            ->limit($currentPage, $pageSize)
            ->order('new_attr ASC, a.create_time DESC')
            ->select();
        if($articleList === false){
            return $this->setError('查询失败');
        }
        return $articleList;
    }

    /**
     * 计算指定栏目以及该栏目的子栏目下指定游戏的所有阶级文章的数量
     * @author xy
     * @since 2017/09/08 14:49
     * @param int $cateId 栏目id
     * @param int $appId 游戏id
     * @param int $typeId 文章类型id 1表示初阶，2表示进阶，3表示高阶
     * @return bool
     */
    public function countAppArticleStrategyByCateIdAndAppId($cateId, $appId, $typeId){
        if(empty($cateId) || empty($appId)){
            return $this->setError('必填参数缺失');
        }
        $cateList = self::getAllColumnInfoFromRedis();
        if(empty($cateList)){
            $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
                ->field('catid, cat_name, parent_id')
                ->select();
        }
        //递归获取指定分类以及该分类子类id
        $catIdArr = get_id_array_recursive($cateList,$cateId,$newArray,'parent_id','catid');
        $cateIdStr = implode(',', $catIdArr);
        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', show_position) AND status = '.self::ART_STATUS_PUBLISH.' AND is_delete = '.self::ART_DELETE_NO.' AND catid IN ('.$cateIdStr.') AND app_id = '.$appId.' AND FIND_IN_SET(\''.$typeId.'\', typeids)' ;
        $totalNum = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->where($where)
            ->order('a.attr ASC, a.create_time DESC')
            ->count();
        if($totalNum === false){
            return $this->setError('查询失败');
        }
        return $totalNum;
    }

    /**
     * 获取文章等级数组
     * @author xy
     * @since 2017/09/07 18:37
     * @return array
     */
    public static function getArticleLeverArr(){
        return array(
            self::ARTICLE_LEVEL_LOWER => '初阶',
            self::ARTICLE_LEVEL_MIDDLE => '进阶',
            self::ARTICLE_LEVEL_HIGH => '高阶',
        );
    }
}