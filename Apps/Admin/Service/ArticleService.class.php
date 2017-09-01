<?php
/**
 * 文章库业务层
 * @author xy
 * @since : 2017/7/25 15:19
 */
namespace Admin\Service;
use Common\Service\BaseService;

class ArticleService extends BaseService
{
    const ART_SHOW_POSITION_APP = 1;        //文章展示平台 1 APP
    const ART_SHOW_POSITION_MEDIA = 2;      //文章展示平台 2 媒体站

    const ARTICLE_TYPE_APP = 0;         //文章分类类型 0 游戏专题
    const ARTICLE_TYPE_NEWS = 1;        //文章分类类型 1 新闻中心

    /**
     * 获取游戏文章分类
     * @author xy
     * @since 2017/07/25 15:24
     * @param int $appId 游戏id
     * @return array|bool
     */
    public static function getAppArticleColumn($appId){
        if(empty($appId) || !is_numeric($appId)){
            return array();
        }
        $userInfo = self::getUserInfo();
        if($userInfo){
            //子查询，查询分类下展示在媒体站文章的数量
            $subQuery =
                ' SELECT app_id, catid, COUNT(*) as total_num FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'article'.
                ' WHERE FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\',show_position) and app_id <> 0'.
                ' GROUP BY `catid` ';
            $query =
                ' SELECT c.*, IF(c.`sort` = 0,99999999,c.`sort`) as `self_sort`, a.`nickname`, IFNULL(t.total_num,0) as total_num, arc.`id` as arcid, arc.`sort_rank`,IF(arc.`sort_rank` = 0,99999999,IFNULL(arc.`sort_rank`,99999999)) as `self_sort_rank` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'category AS c'.
                ' LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'admin_user AS a ON a.`id` = c.`admin_id`'.
                ' LEFT JOIN ('.$subQuery.') AS t ON t.`catid` = c.`catid`'.
                ' LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'arctype AS arc ON arc.`app_id` = c.`app_id` AND arc.`cat_id` = c.`catid`'.
                ' WHERE ( c.`parent_id` = 0 and c.`app_id` = 0 and c.`type` = '.self::ARTICLE_TYPE_APP.') OR (c.`app_id` = '.$appId.' AND c.`type`= '.self::ARTICLE_TYPE_APP.')'.
                ' ORDER BY `self_sort` ASC, `self_sort_rank` ASC';
            $columnInfo = M()->query($query);
            //var_dump($columnInfo);
            $column = array();
            //递归获取分类以及子分类
            if(!empty($columnInfo)){
                //子分类没有文章改分类不显示
                foreach ($columnInfo as $ky=>$column){
                    if(empty($column['total_num']) && !empty($column['parent_id'])){
                        unset($columnInfo[$ky]);
                    }
                }

                $column = get_children_category_tree($columnInfo, 0, 'catid', 'parent_id','level','cat_child');

                foreach ($column as $key=>$value){
                    if($value['has_child']) {
                        foreach ($value['cat_child'] as $k => $val) {
                            $column[$key]['total_num'] += $val['total_num'];
                        }
                    }
                }
            }
            return $column;
        }
        return false;
    }

    /**
     * 通过分类id与栏目类型获取一级栏目
     * @author xy
     * @since 2017/07/28
     * @param int $catId 栏目id
     * @param int $type 栏目类型 0游戏专题，1新闻中心
     * @return bool|mixed
     */
    public static function getLevelOneColumnByCatIdAndType($catId, $type = self::ARTICLE_TYPE_APP){
        $column = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
            ->field('catid, catid as value, cat_name')
            ->where('catid = '.$catId.' AND parent_id = 0 AND `type` = '.$type)
            ->find();
        if(!empty($column)){
            return $column;
        }
        return false;
    }

    /**
     * 根据类型获取所有一级栏目的id以及名称
     * @author xy
     * @since2017/07/28 10:38
     * @param int $type 栏目类型 0游戏专题，1新闻中心
     * @return bool|mixed
     */
    public static function getAllLevelOneColumnByType($type = self::ARTICLE_TYPE_APP){
        $columns = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
            ->field('catid, catid as value, cat_name')
            ->where('parent_id = 0 AND type = '.$type)
            ->select();
        if(!empty($columns)){
            return $columns;
        }
        return false;
    }


    /**
     * 执行添加游戏专题文章栏目
     * @author xy
     * @since 2017/07/28 11:57
     * @param array $data 添加数据表的数据
     * @return bool
     */
    public function addAppArticleColumnCategory(array $data){
        if(empty($data)){
            $this->setError('添加的数据为空，无法执行添加操作');
            return false;
        }
        if(empty($data['app_id']) || empty($data['parent_id'] || empty($data['cat_name']))){
            $this->setError('添加的数据的参数缺失，无法执行添加操作');
            return false;
        }
        if(!empty($data['type'])){
            $data['type'] = self::ARTICLE_TYPE_APP;
        }
        if(empty($data['create_time'])){
            $data['create_time'] = time();
        }
        if(empty($data['admin_id'])){
            $userInfo = self::getUserInfo();
            $data['admin_id'] = $userInfo['id'];
        }

        M()->startTrans();
        $result = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))->add($data);
        if(empty($result)){
            $this->setError('添加失败');
            M()->rollback();
            return false;
        }
        $insertData['cat_id'] = $result;
        $insertData['app_id'] = $data['app_id'];
        $insertData['sort_rank'] = empty($data['sort']) ? 0 : $data['sort'];
        $result = M('arctype')->add($insertData);
        if(empty($result)){
            $this->setError('添加失败');
            M()->rollback();
            return false;
        }
        M()->commit();
        //更新redis缓存中的栏目
        self::updateColumnInfoInRedis();
        return true;
    }

    /**
     * 添加游戏专题顶级栏目
     * @author xy
     * @since 2017/07/28 14:16
     * @param array $data 添加到数据表的数据
     * @return bool
     */
    public function addAppArticleTopColumnCategory(array $data){
        if(empty($data)){
            return false;
        }
        if(empty($data['app_id']) || empty($data['cat_name'])){
            $this->setError('添加的数据的参数缺失，无法执行添加操作');
            return false;
        }
        if(!empty($data['parent_id'])){
            $data['parent_id'] = 0;
        }
        if(!empty($data['type'])){
            $data['type'] = self::ARTICLE_TYPE_APP;
        }
        if(empty($data['create_time'])){
            $data['create_time'] = time();
        }
        if(empty($data['admin_id'])){
            $userInfo = self::getUserInfo();
            $data['admin_id'] = $userInfo['id'];
        }

        M()->startTrans();
        $result = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))->add($data);
        if(empty($result)){
            $this->setError('添加失败');
            M()->rollback();
            return false;
        }
        M()->commit();
        //更新redis缓存中的栏目
        self::updateColumnInfoInRedis();
        return true;
    }

    /**
     * 添加新闻中心子栏目
     * @author xy
     * @since 2017/08/02 14:56
     * @param array $data 添加到数据表的数据
     * @return bool
     */
    public function addNewsArticleColumnCategory(array $data){
        if(empty($data)){
            $this->setError('添加添加的数据为空，无法执行添加操作失败');
            return false;
        }
        if(empty($data['parent_id'] || empty($data['cat_name']))){
            $this->setError('添加的数据的参数缺失，无法执行添加操作');
            return false;
        }
        if(empty($data['type']) || $data['type'] != self::ARTICLE_TYPE_NEWS){
            $data['type'] = self::ARTICLE_TYPE_NEWS;
        }

        if(empty($data['create_time'])){
            $data['create_time'] = time();
        }
        if(empty($data['admin_id'])){
            $userInfo = self::getUserInfo();
            $data['admin_id'] = $userInfo['id'];
        }

        M()->startTrans();
        $result = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))->add($data);
        if(empty($result)){
            $this->setError('添加失败');
            M()->rollback();
            return false;
        }
        $insertData['cat_id'] = $result;
        $insertData['app_id'] = 0;
        $insertData['sort_rank'] = empty($data['sort']) ? 0 : $data['sort'];
        $result = M('arctype')->add($insertData);
        if(empty($result)){
            $this->setError('添加失败');
            M()->rollback();
            return false;
        }
        M()->commit();
        //更新redis缓存中的栏目
        self::updateColumnInfoInRedis();
        return true;
    }

    /**
     * 添加新闻中心顶级栏目
     * @author xy
     * @since 2017/08/02 10:43
     * @param array $data 添加都数据表的数据
     * @return bool
     */
    public function addNewsArticleTopColumnCategory(array $data){
        if(empty($data)){
            $this->setError('添加添加的数据为空，无法执行添加操作');
            return false;
        }
        if(empty($data['cat_name'])){
            $this->setError('添加的数据的参数缺失，无法执行添加操作');
            return false;
        }
        if(!empty($data['app_id'])){
            $data['app_id'] = 0;
        }
        if(!empty($data['parent_id'])){
            $data['parent_id'] = 0;
        }
        if(empty($data['type']) || $data['type'] != self::ARTICLE_TYPE_NEWS){
            $data['type'] = self::ARTICLE_TYPE_NEWS;
        }
        if(empty($data['create_time'])){
            $data['create_time'] = time();
        }
        if(empty($data['admin_id'])){
            $userInfo = self::getUserInfo();
            $data['admin_id'] = $userInfo['id'];
        }

        M()->startTrans();
        $result = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))->add($data);
        if(empty($result)){
            $this->setError('添加失败');
            M()->rollback();
            return false;
        }
        M()->commit();

        //更新redis缓存中的栏目
        self::updateColumnInfoInRedis();
        return true;
    }

    /**
     * 通过栏目id与游戏id获取栏目以及其子栏目id数组
     * @author xy
     * @since 2017/07/28 17:24
     * @param integer $catId 栏目分类id
     * @param integer $appId 游戏id
     * @return array|bool
     */
    public function getAppArticleColumnIdAndChildColumnIdByCatIdAndAppId($catId, $appId){
        if(empty($catId)||empty($appId)){
            return false;
        }

        //1.先判断是否是顶级的分类
        $category = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
            ->field('catid, parent_id')
            ->where('catid = '.$catId.' AND type = '.self::ARTICLE_TYPE_APP)
            ->find();
        //var_dump($category);die;
        if(empty($category)){
            $this->setError('未找到对应栏目分类');
            return false;
        }
        if($category['parent_id'] == 0){
            $parentId = 0;

        }else{
            $parentId = $catId;
        }
        $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
            ->field('catid, app_id, parent_id')
            ->where('( `parent_id` = 0 AND `catid` = '.$catId.' AND `type` = '.self::ARTICLE_TYPE_APP.') OR ((`parent_id` <> 0 OR `parent_id` = 0 ) AND `app_id` = '.$appId.' AND `type` = '.self::ARTICLE_TYPE_APP.')')
            ->select();
        //递归获取分类id
        $catIdArr = get_id_array_recursive($cateList,$parentId,$newArray,'parent_id','catid');
        return $catIdArr;
    }

    /**
     * 通过条件获取文章的数量
     * @author xy
     * @since 2017/07/28 17:28
     * @param NULL $where 查询条件
     * @return bool
     */
    public function getArticleListTotalNum($where = NULl){
        //获取文章的数量
        $totalNum = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.id')
            ->where($where)
            ->count();
        return $totalNum;
    }

    /**
     * 通过条件获取文章的列表
     * @author xy
     * @since 2017/07/28 17:14
     * @param null $where 查询条件
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @return mixed
     */
    public function getArticleListByPage($where = NULL, $currentPage, $pageSize){
        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.id, a.app_id, a.catid, a.title, a.status, IF(a.sort = 0, 999999999, IFNULL(a.sort, 999999999)) as self_sort, a.release_time, a.create_time, a.update_time, a.is_delete, alb.app_name, u.nickname, arc.id as arc_id, IF(arc.pre_sort_rank = 0,999999999, arc.pre_sort_rank) as pre_sort_rank, arc.page_view')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib AS alb ON alb.app_id = a.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'admin_user AS u ON u.`id` = a.`admin_id`')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives AS arc ON arc.`article_id` = a.`id`')
            ->where($where)
            ->order('a.create_time DESC')
            ->limit($currentPage,$pageSize)
            ->select();
        return $articleList;
    }

    /**
     * 通过条件获取所有文章的数量
     * @author xy
     * @since 2017/07/30 11:52
     * @param NULL $where 查询条件
     * @return bool
     */
    public function getAllArticleListTotalNum($where = NULl){
        //获取文章的数量
        $totalNum = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib AS alb ON alb.app_id = a.app_id')
            ->field('a.id')
            ->where($where)
            ->count();
        return $totalNum;
    }

    /**
     * 通过条件获取所有文章列表 按创建时间排序
     * @author xy
     * @since 2017/07/30 11:39
     * @param null $where 查询条件
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @return mixed
     */
    public function getAllArticleListByPage($where = NULL, $currentPage, $pageSize){
        $articleList = M(C('DB_ZHIYU.DB_NAME').'.'.'article', C('DB_ZHIYU.DB_PREFIX'))->alias('a')
            ->field('a.id, a.app_id, a.catid, a.title, a.status, IF(a.sort = 0, 999999999, IFNULL(a.sort, 999999999)) as self_sort, a.release_time, a.create_time, a.update_time, a.is_delete, a.show_position, alb.app_name, u.nickname, arc.id as arc_id, IF(arc.pre_sort_rank = 0,999999999, arc.pre_sort_rank) as pre_sort_rank, arc.page_view')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib AS alb ON alb.app_id = a.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'admin_user AS u ON u.`id` = a.`admin_id`')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'archives AS arc ON arc.`article_id` = a.`id`')
            ->where($where)
            ->order('a.create_time DESC')
            ->limit($currentPage,$pageSize)
            ->select();
        if(!empty($articleList)){
            $columns = self::getAllColumnInfoFromRedis();
            foreach ($articleList as $key=>$value){
                $categoryNameArray = array();
                //获取所属的栏目分类
                $categoryNameArray = get_category_name_recursive($columns, $value['catid'], $categoryNameArray, 'parent_id', 'catid', 'cat_name');
                $articleList[$key]['category_str'] = implode('-',array_reverse($categoryNameArray));
                //显示文章是否显示在APP
                if(!empty($value['show_position'])){
                    $value['show_position'] = explode(',',$value['show_position']);
                    if(in_array(self::ART_SHOW_POSITION_APP,$value['show_position'])){
                        $articleList[$key]['show_app'] = 1;
                    }
                }
            }
        }
        return $articleList;
    }

    /**
     * 通过分类id获取栏目名称
     * @author xy
     * @since 2017/08/01 14:45
     * @param int $catId 栏目分类id
     * @param int $appId 游戏app_id
     * @return bool
     */
    public function getColumnNameByCatId($catId,$appId){
        $catId = intval($catId);
        $appId = intval($appId);
        $subQuery = 'SELECT * FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'arctype WHERE `cat_id` = '.$catId.' AND `app_id` = '.$appId;
        $categoryInfo = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))->alias('c')
            ->field('c.cat_name, a.column_name')
            ->join('LEFT JOIN ('.$subQuery.') AS a ON c.catid = a.cat_id')
            ->where('c.catid = '.$catId)
            ->find();
        if(!empty($categoryInfo['column_name'])){
            return $categoryInfo['column_name'];
        }else if(!empty($categoryInfo['cat_name'])){
            return $categoryInfo['cat_name'];
        }else{
            return false;
        }
    }

    /**
     * 通过栏目id获取新闻栏目以及其子栏目id数组
     * @author xy
     * @since 2017/08/02 09:31
     * @param integer $catId 栏目分类id
     * @return array|bool
     */
    public function getNewsArticleColumnIdAndChildColumnIdByCatId($catId){
        if(empty($catId)){
            $this->setError('参数不能为空');
            return false;
        }

        //1.先判读是否是顶级的分类
        $category = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
            ->field('catid, parent_id')
            ->where('catid = '.$catId.' AND type <> '.self::ARTICLE_TYPE_APP.' AND app_id = 0')
            ->find();
        //var_dump($category);die;
        if(empty($category)){
            $this->setError('未找到对应的栏目分类');
            return false;
        }
        if($category['parent_id'] == 0){
            $parentId = 0;

        }else{
            $parentId = $catId;
        }
        $cateList = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
            ->field('catid, app_id, parent_id')
            ->where('( `parent_id` = 0 AND `catid` = '.$catId.' AND `type` <> '.self::ARTICLE_TYPE_APP.' AND `app_id` = 0) OR ((`parent_id` <> 0 OR `parent_id` = 0) AND `app_id` = 0 AND `type` <> '.self::ARTICLE_TYPE_APP.')')
            ->select();
        //递归获取分类id
        $catIdArr = get_id_array_recursive($cateList,$parentId,$newArray,'parent_id','catid');
        return $catIdArr;
    }

}