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
}