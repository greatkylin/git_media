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

    const ARTICLE_STRATEGY = 1;    //游戏攻略
    const ARTICLE_NEWS= 2;         //游戏资讯
    const ARTICLE_EVALUATION = 3;  //游戏测评
    const ARTICLE_QUESTION = 50;   //游戏每日一题

    /**
     * 获取指定条数的最新的发布在媒体站每日一题的文章 按时间倒序排序
     * @author xy
     * @since 2017/08/31 15:20
     * @param int $limit
     * @return bool|array
     */
    public function getLastEveryDayQues($limit = 9){

        $cateList = self::getAllColumnInfoFromRedis();
        if(empty($cateList)){
            $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
                ->field('catid, cat_name, parent_id')
                ->select();
        }
        //游戏攻略的分类id
        $cateId = self::ARTICLE_QUESTION;
        //递归获取指定分类以及该分类子类id
        $catIdArr = get_id_array_recursive($cateList,$cateId,$newArray,'parent_id','catid');
        $cateIdStr = implode(',', $catIdArr);
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            'a.catid' => array('IN', $cateIdStr),
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
        );

        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->where($where)
            ->limit($limit)
            ->order('a.release_time DESC')
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
     * @return bool|array
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

        //展示在媒体站未删除已发布的文章 按发布时间倒序排序
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            'a.catid' => array('IN', $cateIdStr),
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
        );

        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->where($where)
            ->limit($limit)
            ->order('a.release_time DESC')
            ->select();
        if($articleList === false){
            return $this->setError('查询失败');
        }
        //生成详情页链接
        foreach ($articleList as &$art){
            $art['top_parent_id'] = $this->getTopCateIdByChildCateId($art['catid']);
            if($art['top_parent_id'] == self::ARTICLE_STRATEGY){
                $art['url'] = U('Home/Article/strategy_detail', array('id' => $art['id']));
            }else if($art['top_parent_id'] == self::ARTICLE_NEWS){
                $art['url'] = U('Home/Article/news_detail', array('id' => $art['id']));
            }else if($art['top_parent_id'] == self::ARTICLE_EVALUATION){
                $art['url'] = U('Home/Article/evaluate_detail', array('id' => $art['id']));
            }else if($art['top_parent_id'] == self::ARTICLE_QUESTION){
                $art['url'] = U('Home/Article/ques_detail', array('id' => $art['id']));
            }
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
     * @return bool|array
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
        //展示在媒体站未删除已发布的文章 按发布时间倒序排序
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            'a.catid' => array('IN', $cateIdStr),
            'a.app_id' => $appId,
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
        );

        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->where($where)
            ->limit($limit)
            ->order('a.release_time DESC')
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
     * @return bool|array
     */
    public function getAppArticleStrategyByCateIdAndAppId($cateId, $appId, $typeId, $currentPage, $pageSize = 5){
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
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            'a.catid' => array('IN', $cateIdStr),
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
        );
        if($appId){
            $where['app_id'] = $appId;
        }
        if($typeId){
            $where['_string']  = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position) AND FIND_IN_SET(\''.$typeId.'\', a.typeids)' ;
        }

        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->field('a.*, IF(a.attr = 0, 999999999, a.attr) as new_attr')
            ->where($where)
            ->limit($currentPage, $pageSize)
            ->order('new_attr ASC, a.release_time DESC')
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
     * @return bool|int
     */
    public function countAppArticleStrategyByCateIdAndAppId($cateId, $appId, $typeId){
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
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            'a.catid' => array('IN', $cateIdStr),
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
        );
        if($appId){
            $where['app_id'] = $appId;
        }
        if($typeId){
            $where['_string']  = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', show_position) AND FIND_IN_SET(\''.$typeId.'\', typeids)' ;
        }
        $totalNum = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->where($where)
            ->order('a.attr ASC, a.release_time DESC')
            ->count();
        if($totalNum === false){
            return $this->setError('查询失败');
        }
        return $totalNum;
    }

    /**
     * 根据攻略类型获取攻略数据
     * @param int $typeId 文章类型id 1表示初阶，2表示进阶，3表示高阶
     * @param int $limit 获取的条数
     * @return bool|array
     */
    public function getAllStrategyListByTypeId($typeId, $limit = 5){
        if(empty($typeId) || !in_array($typeId, self::getArticleLevelIdArr())){
            return $this->setError('必填参数类型错误');
        }
        $cateList = self::getAllColumnInfoFromRedis();
        if(empty($cateList)){
            $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
                ->field('catid, cat_name, parent_id')
                ->select();
        }
        //游戏攻略的分类id
        $cateId = self::ARTICLE_STRATEGY;
        //递归获取指定分类以及该分类子类id
        $catIdArr = get_id_array_recursive($cateList,$cateId,$newArray,'parent_id','catid');
        $cateIdStr = implode(',', $catIdArr);
        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            'a.catid' => array('IN', $cateIdStr),
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position) AND FIND_IN_SET(\''.$typeId.'\', a.typeids)',
        );

        $strategyList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->where($where)
            ->order('a.release_time DESC')
            ->limit($limit)
            ->select();
        if($strategyList === false){
            return $this->setError('查询失败');
        }
        return $strategyList;
    }

    /**
     * 获取指定游戏厂商有游戏攻略的游戏列表
     * @autho xy
     * @since 2017/09/18 11:00
     * @param int $supplierId 游戏厂商id
     * @param int $limit 查询的数量
     * @return bool|array
     */
    public function getStrategyAppListBySupplierId($supplierId, $limit = 12){
        $supplierId = intval($supplierId);
        if(empty($supplierId)){
            $this->setError('游戏厂商id错误');
        }
        $cateList = self::getAllColumnInfoFromRedis();
        if(empty($cateList)){
            $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
                ->field('catid, cat_name, parent_id')
                ->select();
        }
        //游戏攻略的分类id
        $cateId = self::ARTICLE_STRATEGY;
        //递归获取指定分类以及该分类子类id
        $catIdArr = get_id_array_recursive($cateList,$cateId,$newArray,'parent_id','catid');
        $cateIdStr = implode(',', $catIdArr);
        //媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));
        //指定的游戏厂商
        $where['IFNULL(alib.supplier_id, lib.supplier_id)'] = $supplierId;
        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where['a.status'] = self::ART_STATUS_PUBLISH;
        $where['a.is_delete'] = self::ART_DELETE_NO;
        $where['a.catid'] = array('IN', $cateIdStr);
        $where['_string'] = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)';

        $appList = M(C('DB_NAME').'.'.'app_list', C('DB_PREFIX'))->alias('alist')
            ->field(
                'alist.`app_id`, alist.publish_time, list.`status`, list.`sj_time`, list.create_time, 
                IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, 
                s.supplier_id, s.supplier_name,
                (list.app_down_num + list.cardinal) as app_down_num, 
                IF(alist.final_hot_sort=0, 9999999, IFNULL( alist.final_hot_sort, 9999999 ) ) as final_hot_sort,
                IF(alist.final_new_sort=0, 9999999, IFNULL( alist.final_new_sort, 9999999 ) ) as final_new_sort'
            )
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'supplier s on s.supplier_id = IFNULL(alib.supplier_id, lib.supplier_id)')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'article a on a.app_id = alist.app_id')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->where($where)
            ->order('final_hot_sort ASC, app_down_num DESC')
            ->group('alist.app_id')
            ->limit($limit)
            ->select();
        if($appList === false){
            return $this->setError('查询失败');
        }
        return $appList;
    }

    /**
     * 分页获取指定游戏厂商有游戏攻略的游戏列表
     * @author xy
     * @since 2017/09/18 11:36
     * @param int $supplierId 游戏厂商id
     * @param int $currentPage 当前页
     * @param int $pageSize 每页大小
     * @return bool|array
     */
    public function getStrategyAppListBySupplierIdPage($supplierId, $currentPage, $pageSize){
        $supplierId = intval($supplierId);
        if(empty($supplierId)){
            $this->setError('游戏厂商id错误');
        }
        $cateList = self::getAllColumnInfoFromRedis();
        if(empty($cateList)){
            $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
                ->field('catid, cat_name, parent_id')
                ->select();
        }
        //游戏攻略的分类id
        $cateId = self::ARTICLE_STRATEGY;
        //递归获取指定分类以及该分类子类id
        $catIdArr = get_id_array_recursive($cateList,$cateId,$newArray,'parent_id','catid');
        $cateIdStr = implode(',', $catIdArr);
        //媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));
        //指定的游戏厂商
        $where['IFNULL(alib.supplier_id, lib.supplier_id)'] = $supplierId;
        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where['a.status'] = self::ART_STATUS_PUBLISH;
        $where['a.is_delete'] = self::ART_DELETE_NO;
        $where['a.catid'] = array('IN', $cateIdStr);
        $where['_string'] = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)';

        $appList = M(C('DB_NAME').'.'.'app_list', C('DB_PREFIX'))->alias('alist')
            ->field(
                'alist.`app_id`, alist.publish_time, list.`status`, list.`sj_time`, list.create_time, 
                IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, 
                s.supplier_id, s.supplier_name,
                (list.app_down_num + list.cardinal) as app_down_num, 
                IF(alist.final_hot_sort=0, 9999999, IFNULL( alist.final_hot_sort, 9999999 ) ) as final_hot_sort,
                IF(alist.final_new_sort=0, 9999999, IFNULL( alist.final_new_sort, 9999999 ) ) as final_new_sort'
            )
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'supplier s on s.supplier_id = IFNULL(alib.supplier_id, lib.supplier_id)')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'article a on a.app_id = alist.app_id')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id = a.id')
            ->where($where)
            ->order('final_hot_sort ASC, app_down_num DESC')
            ->group('alist.app_id')
            ->limit($currentPage, $pageSize)
            ->select();
        if($appList === false){
            return $this->setError('查询失败');
        }
        return $appList;
    }

    /**
     * 计算指定游戏厂商的有游戏攻略的游戏的数量
     * @author xy
     * @since 2017/09/18 11:45
     * @param int $supplierId 游戏厂商id
     * @return boolean|int
     */
    public function countStrategyAppListBySupplierIdNum($supplierId){
        $supplierId = intval($supplierId);
        if(empty($supplierId)){
            $this->setError('游戏厂商id错误');
        }
        $cateList = self::getAllColumnInfoFromRedis();
        if(empty($cateList)){
            $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
                ->field('catid, cat_name, parent_id')
                ->select();
        }
        //游戏攻略的分类id
        $cateId = self::ARTICLE_STRATEGY;
        //递归获取指定分类以及该分类子类id
        $catIdArr = get_id_array_recursive($cateList,$cateId,$newArray,'parent_id','catid');
        $cateIdStr = implode(',', $catIdArr);
        //媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));
        //指定的游戏厂商
        $where['IFNULL(alib.supplier_id, lib.supplier_id)'] = $supplierId;
        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where['a.status'] = self::ART_STATUS_PUBLISH;
        $where['a.is_delete'] = self::ART_DELETE_NO;
        $where['a.catid'] = array('IN', $cateIdStr);
        $where['_string'] = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)';


        $appList = M(C('DB_NAME').'.'.'app_list', C('DB_PREFIX'))->alias('alist')
            ->field(
                'alist.`app_id`, alist.publish_time, list.`status`, list.`sj_time`, list.create_time, 
                IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, 
                s.supplier_id, s.supplier_name'
            )
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'supplier s on s.supplier_id = IFNULL(alib.supplier_id, lib.supplier_id)')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'article a on a.app_id = alist.app_id')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->where($where)
            ->order('final_hot_sort ASC, app_down_num DESC')
            ->group('alist.app_id')
            ->select();
        if($appList === false){
            return $this->setError('查询失败');
        }
        return count($appList);
    }

    /**
     * 根据分类id以及游戏id计算该游戏分类以及子类下文章的数量
     * @author xy
     * @since 2017/09/18 15:42
     * @param int $cateId 栏目分类id
     * @param int $appId 游戏id
     * @return bool|int
     */
    public function countArticleByCateIdAndAppIdNum($cateId, $appId){
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
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            'a.catid' => array('IN', $cateIdStr),
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
        );
        if($appId){
            $where['app_id'] = $appId;
        }

        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->field('a.*, IF(a.attr = 0, 999999999, a.attr) as new_attr')
            ->where($where)
            ->order('new_attr ASC, a.release_time DESC')
            ->select();
        if($articleList === false){
            return $this->setError('查询失败');
        }
        return count($articleList);
    }


    /**
     * 分页获取指定游戏栏目以及子栏目下的文章列表
     * @author xy
     * @since 2017/09/18 15:44
     * @param int $cateId 栏目分类id
     * @param int $appId 游戏id
     * @param int $currentPage 当前页
     * @param int $pageSize 每页大小
     * @return bool|array
     */
    public function getArticleByCateIdAndAppIdByPage($cateId, $appId, $currentPage, $pageSize = 5){
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
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            'a.catid' => array('IN', $cateIdStr),
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
        );
        if($appId){
            $where['app_id'] = $appId;
        }

        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->field('a.*, IF(a.attr = 0, 999999999, a.attr) as new_attr')
            ->where($where)
            ->limit($currentPage, $pageSize)
            ->order('new_attr ASC, a.release_time DESC')
            ->select();
        if($articleList === false){
            return $this->setError('查询失败');
        }
        return $articleList;
    }

    /**
     * 通过id获取展示在媒体站的文章详情
     * @author xy
     * @since 2017/09/19 14:02
     * @param int $id 文章id
     * @return bool|array
     */
    public function getArticleDetailByPk($id){
        $id = intval($id);
        if(empty($id)){
            return $this->setError('必填参数缺失');
        }
        //指定id,展示在媒体站的文章
        $where = array(
            'a.id' => $id,
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)'
        );
        $article = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->where($where)
            ->find();
        if($article === false){
            return $this->setError('查询文章失败');
        }
        if(empty($article)){
            return $this->setError('未找到对应id的文章');
        }
        return $article;
    }

    /**
     * 通过id获取展示在媒体站已发布的未删除的文章
     * @author xy
     * @since 2017/09/19 14:19
     * @param int $id 文章id
     * @return array|bool
     */
    public function getValidArticleDetailByPk($id){
        $article = $this->getArticleDetailByPk($id);
        if($article === false){
            return false;
        }
        //1.判断文章是否是发布状态
        if(empty($article['status']) || $article['status'] != self::ART_STATUS_PUBLISH){
            return $this->setError('文章不存在在或者未发布');
        }
        //2.判断文章是否为删除状态
        if($article['is_delete'] == self::ART_DELETE_YES){
            return $this->setError('文章不存在在或者未发布');
        }
        return $article;
    }

    /**
     * 根据分类id以及游戏id获取阅读量最高的几篇文章列表
     * @author xy
     * @since 2017/09/19 14:34
     * @param int $cateId 栏目分类id
     * @param int $appId  游戏id
     * @param int $limit 获取的数据条数
     * @return bool|array
     */
    public function getMostReadArticleListByCateIdAndAppId($cateId, $appId, $limit = 4){
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
        //展示在媒体站未删除已发布的文章 按点击量倒序 发布时间倒序排序
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            'a.catid' => array('IN', $cateIdStr),
            'a.app_id' => $appId,
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
        );
        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id = a.id')
            ->where($where)
            ->limit($limit)
            ->order('a.click_num DESC, a.release_time DESC')
            ->select();

        if($articleList === false){
            return $this->setError('查询失败');
        }
        return $articleList;
    }

    /**
     * 获取在今天之前发布的文章
     * @author xy
     * @since 2017/09/20 15:10
     * @param int $cateId 栏目分类id
     * @param int $appId  游戏id
     * @param int $limit 获取的数据条数
     * @return bool|array
     */
    public function getArticleReleaseBeforeTodayByCateIdAndAppId($cateId, $appId, $limit = 4){
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
        //展示在媒体站未删除已发布的文章 按点击量倒序 发布时间倒序排序
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            'a.catid' => array('IN', $cateIdStr),
            'a.app_id' => $appId,
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
        );
        //获取今天的开始时间
        $todayStartTime = strtotime(date('Y-m-d', time));
        $where['a.release_time'] = array('lt', $todayStartTime);
        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->where($where)
            ->limit($limit)
            ->order('a.click_num DESC, a.release_time DESC')
            ->select();
        if($articleList === false){
            return $this->setError('查询失败');
        }
        return $articleList;
    }

    /**
     * 通过栏目分类id获取属于该栏目分类且有发布文章的游戏
     * @param int $cateId 栏目分类id
     * @param null $limit
     * @return bool|array
     */
    public function getArticleAppListByCateId($cateId, $limit = NULL){
        $cateId = intval($cateId);
        if(empty($cateId)){
            $this->setError('栏目id参数缺失');
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
        //媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));
        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where['a.status'] = self::ART_STATUS_PUBLISH;
        $where['a.is_delete'] = self::ART_DELETE_NO;
        $where['a.catid'] = array('IN', $cateIdStr);
        $where['_string'] = 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)';

        $appList = M(C('DB_NAME').'.'.'app_list', C('DB_PREFIX'))->alias('alist')
            ->field(
                'alist.`app_id`, alist.publish_time, list.`status`, list.`sj_time`, list.create_time, 
                IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, 
                s.supplier_id, s.supplier_name,
                (list.app_down_num + list.cardinal) as app_down_num, 
                IF(alist.final_hot_sort=0, 9999999, IFNULL( alist.final_hot_sort, 9999999 ) ) as final_hot_sort,
                IF(alist.final_new_sort=0, 9999999, IFNULL( alist.final_new_sort, 9999999 ) ) as final_new_sort'
            )
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'supplier s on s.supplier_id = IFNULL(alib.supplier_id, lib.supplier_id)')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'article a on a.app_id = alist.app_id')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id = a.id')
            ->where($where)
            ->order('final_hot_sort ASC, app_down_num DESC')
            ->group('alist.app_id')
            ->limit($limit)
            ->select();
        if($appList === false){
            return $this->setError('查询失败');
        }
        return $appList;
    }

    /**
     * 根据关键词分页搜索文章列表
     * @author xy
     * @since 2017/09/22 18:01
     * @param string $keyword 关键字
     * @param int $currentPage 当前页
     * @param int $pageSize 每页大小
     * @return bool|array
     */
    public function getArticleByKeywordByPage($keyword, $currentPage, $pageSize = 5){
        if(empty($keyword)){
            return $this->setError('必填参数缺失');
        }

        $map['a.title'] = array('like', '%'.$keyword.'%');
        $map['a.keywords'] = array('like', '%'.$keyword.'%');
        $map['a.description'] = array('like', '%'.$keyword.'%');
        $map['_logic'] = 'OR';

        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
            '_complex' => $map,
        );

        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.*')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->field('a.*, IF(a.attr = 0, 999999999, a.attr) as new_attr')
            ->where($where)
            ->limit($currentPage, $pageSize)
            ->order('new_attr ASC, a.catid DESC')
            ->select();
        if($articleList === false){
            return $this->setError('查询失败');
        }
        return $articleList;
    }

    /**
     * 计算根据关键词搜索文章的数量
     * @author xy
     * @since 2017/09/22 18:01
     * @param string $keyword 关键字
     * @return bool|array
     */
    public function countArticleByKeywordNum($keyword){
        if(empty($keyword)){
            return $this->setError('必填参数缺失');
        }

        $map['a.title'] = array('like', '%'.$keyword.'%');
        $map['a.keywords'] = array('like', '%'.$keyword.'%');
        $map['a.description'] = array('like', '%'.$keyword.'%');
        $map['_logic'] = 'OR';

        //展示在媒体站未删除已发布的文章 按创建时间倒序排序
        $where = array(
            'a.status' => self::ART_STATUS_PUBLISH,
            'a.is_delete' => self::ART_DELETE_NO,
            '_string' => 'FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)',
            '_complex' => $map,
        );

        $artNum = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.id')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives arc on arc.article_id=a.id')
            ->field('a.*, IF(a.attr = 0, 999999999, a.attr) as new_attr')
            ->where($where)
            ->count();
        if($artNum === false){
            return $this->setError('查询失败');
        }
        return $artNum;
    }

    /**
     * 根据子栏目id获取顶级栏目id
     * @author xy
     * @since 2017/09/25 15:43
     * @param int $cateId 栏目id
     * @return mixed
     */
    public function getTopCateIdByChildCateId($cateId){
        $cateList = self::getAllColumnInfoFromRedis();
        if(empty($cateList)){
            $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
                ->field('catid, cat_name, parent_id')
                ->select();
        }
        $topParentId = null;
        foreach ($cateList as $value){
            if($cateId == $value['catid']){
                if($value['parent_id'] == 0){
                    $topParentId = $value['catid'];
                    break;
                }else{
                    $topParentId = $this->getTopCateIdByChildCateId($value['parent_id']);
                }
            }
        }
        return $topParentId;
    }

    /**
     * 记录文章的访问量
     * @author xy
     * @since 2017/09/26 11:56
     * @param $artId
     * @param bool $isPreview 是否为预览，是则不增加访问量
     * @return bool
     */
    public function updateArticleClickNum($artId, $isPreview = false){
        $artId = intval($artId);
        if(empty($artId)){
            $this->setError('必填参数缺失');
        }
        if(!$isPreview){
            $article = $this->getArticleDetailByPk($artId);
            if(!$article){
                return false;
            }
            $result = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))
                ->where(array('id' => $artId))
                ->setInc('click_num');
            if($result === false){
                return $this->setError('记录文章访问量失败');
            }
        }
        return true;
    }

    /**
     * 获取文章攻略类型数组
     * @author xy
     * @since 2017/09/07 18:37
     * @return array
     */
    public static function getArticleLeverArr(){
        return array(
            self::ARTICLE_LEVEL_LOWER => '新手',
            self::ARTICLE_LEVEL_MIDDLE => '进阶',
            self::ARTICLE_LEVEL_HIGH => '高阶',
        );
    }

    /**
     * 获取文章攻略类型id数组
     * @author xy
     * @since 2017/09/18 10:34
     * @return array
     */
    public static function getArticleLevelIdArr(){
        return array(
            self::ARTICLE_LEVEL_LOWER ,
            self::ARTICLE_LEVEL_MIDDLE,
            self::ARTICLE_LEVEL_HIGH,
        );
    }
}