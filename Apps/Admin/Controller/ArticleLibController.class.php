<?php
/**
 * 文章库控制器
 * @author xy
 * @since 2017/07/25 14:02
 */

namespace Admin\Controller;

use Think\Controller;
use Admin\Controller\AdminBaseController;
use Admin\Service\ArticleService;
use Admin\Service\AppService;

class ArticleLibController extends AdminBaseController
{
    const ART_SHOW_POSITION_APP = 1;        //文章展示平台 1 APP
    const ART_SHOW_POSITION_MEDIA = 2;      //文章展示平台 2 媒体站

    const ARTICLE_TYPE_APP = 0;         //文章分类类型 0 游戏专题
    const ARTICLE_TYPE_NEWS = 1;        //文章分类类型 1 新闻中心

    public function __construct() {
        //控制器初始化
        parent::__construct();
    }

    /**
     * 获取游戏列表
     * @author xy
     * @since 2017/07/25 14:26
     */
    public function app_list() {
        //媒体站已上架的游戏
        $where['alist.is_publish'] = array('IN', array(1));
        // 分页
        $service = new AppService();
        $totalCount = $service->getAppListFromMediaTotalCount($where); //获取总条数
        $page = intval(I('p'));
        $pageSize = intval(I('pagesize'));
        $pageSize = $pageSize > 0 ? $pageSize : DEFAULT_PAGE_SIZE; //每页显示条数
        $totalPages = ceil($totalCount / $pageSize);        //总页数
        $page = $page > $totalPages ? $totalPages : $page;
        $page = $page > 0 ? $page : 1;
        echo $currentPage = $pageSize * ($page - 1);
        $this->assign('firstRow', $currentPage);
        $this->assign('page', $page);
        $this->assign('pages', $totalPages);
        $this->assign('pagesize', $pageSize);
        //根据游戏列表的类型获取游戏列表
        $service = new AppService();
        $appList = $service->getAppSortListFromMediaByPage(1, $currentPage, $pageSize, $where);
        $this->assign('app_list', $appList);
        $this->display('app_list');
    }

    /**
     * 游戏资讯分类
     * @author xy
     * @since 2017/07/25 19:59；
     */
    public function column_list() {
        $appId = intval(I('app_id'));
        if (!empty($appId) && is_numeric($appId)) {
            //获取游戏的名称
            $appName = AppService::getAppNameByAppId($appId);
            if (!empty($appName)) {
                $this->assign('appName', $appName);
            }
            //获取游戏专题的栏目数组
            $column = ArticleService::getAppArticleColumn($appId);
            $this->assign('column', $column);
        }
        $this->assign('appId', $appId);
        $this->display();
    }

    /**
     * 编辑栏目名称
     * @author xy
     * @since 2017/07/27 10:18
     */
    public function column_name_edit() {
        //栏目分类id
        $catId = intval(I('record_id'));
        //栏目名称
        $catName = trim(I('field_value'));
        //修改的字段
        $field = trim(I('field_tag'));
        //查询是否存在符合条件的栏目分类，存在则修改名称，不存在则报错
        $category = M(C('DB_ZHIYU.DB_NAME') . '.' . 'category', C('DB_ZHIYU.DB_PREFIX'))
            ->where('catid = ' . $catId)
            ->find();
        //修改栏目名称，统一在指娱库修改
        if (!empty($category)) {
            if ($field == 'title' && !empty($catName)) {
                $data['cat_name'] = $catName;
                $data['admin_id'] = $this->user_info['id'];
                $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'category', C('DB_ZHIYU.DB_PREFIX'))
                    ->where('catid = ' . $catId)
                    ->save($data);
                if ($result) {
                    $res['new_value'] = $catName;
                    $res['code'] = 0;
                    $res['error'] = false;
                    $res['message'] = '栏目修改成功';
                    $this->ajaxReturn($res);
                }
            }
        }
        $res['code'] = 2;
        $res['error'] = true;
        $res['message'] = '栏目修改失败';
        $this->ajaxReturn($res);
    }

    /**
     * 编辑栏目排序
     * @author xy
     * @since 2017/07/27 11:12
     */
    public function column_sort_edit() {
        //arctype表id
        $arcId = intval(I('record_id'));
        //新的排序
        $sortRank = trim(I('field_value'));
        //修改的字段
        $field = trim(I('field_tag'));
        //游戏id
        $appId = intval(I('app_id'));
        //指娱文章库栏目id
        $catId = intval(I('cat_id'));

        if (empty($appId) || empty($catId)) {
            $res['code'] = 2;
            $res['error'] = true;
            $res['message'] = '参数错误，排序修改失败';
            $this->ajaxReturn($res);
        }
        //判断栏目是否存在，不存在则报错，存在则设置新排序（在关联表中设置，不影响原排序）
        $category = M(C('DB_ZHIYU.DB_NAME') . '.' . 'category', C('DB_ZHIYU.DB_PREFIX'))
            ->where('catid = ' . $catId)
            ->find();
        if (empty($category)) {
            $res['code'] = 2;
            $res['error'] = true;
            $res['message'] = '栏目不存在，排序修改失败';
            $this->ajaxReturn($res);
        }

        if (empty($arcId)) {
            //根据游戏id与栏目id判读arctype表是否有存在记录
            $arcInfo = M('arctype')->where(array('app_id' => $appId, 'cat_id' => $catId))->find();
        } else {
            $arcInfo = M('arctype')->where(array('id' => $arcId, 'app_id' => $appId, 'cat_id' => $catId))->find();
        }

        //如果不存在则添加记录
        if (empty($arcInfo)) {
            if ($field == 'c_sort' && $sortRank > 0) {
                $data = array(
                    'app_id' => $appId,
                    'cat_id' => $catId,
                    'sort_rank' => $sortRank
                );
                $result = M('arctype')->add($data);
                if ($result) {
                    $res['code'] = 0;
                    $res['error'] = false;
                    $res['message'] = '修改成功';
                    $this->ajaxReturn($res);
                }
            }
            $res['code'] = 2;
            $res['error'] = true;
            $res['message'] = '修改失败';
            $this->ajaxReturn($res);

        } else {
            //存在则修改排序
            if ($field == 'c_sort' && $sortRank > 0) {
                $data = array(
                    'sort_rank' => $sortRank
                );
                $result = M('arctype')->where(array('id' => $arcId, 'app_id' => $appId, 'cat_id' => $catId))->save($data);
                if ($result) {
                    $res['code'] = 0;
                    $res['error'] = false;
                    $res['message'] = '修改成功';
                    $this->ajaxReturn($res);
                }
            }
            $res['code'] = 2;
            $res['error'] = true;
            $res['message'] = '修改失败';
            $this->ajaxReturn($res);
        }
    }

    /**
     * 每个二级栏目的seo编辑
     * @author xy
     * @since 2017/07/27 14:18
     */
    public function column_seo_edit() {
        $appId = intval(I('app_id'));
        $catId = intval(I('cat_id'));
        if (IS_AJAX) {
            if (empty($appId) || empty($catId)) {
                $this->outputJSON(true, '100001', '参数错误，无法编辑');
            }
            //seo的信息允许为空
            $seoTitle = trim(I('seo_title'));
            $seoKeywords = trim(I('seo_keywords'));
            $seoDescription = trim(I('seo_description'));
            //游戏id以及栏目id都不为空表示对游戏的某个栏目分类做seo
            $arcInfo = M('arctype')->field('id,app_id,cat_id,seo_title,seo_keywords,seo_description')
                ->where(array('app_id' => $appId, 'cat_id' => $catId))
                ->find();

            if (empty($arcInfo)) {
                //不存在则执行插入信息操作
                $data = array(
                    'app_id' => $appId,
                    'cat_id' => $catId,
                    'seo_title' => $seoTitle,
                    'seo_keywords' => $seoKeywords,
                    'seo_description' => $seoDescription,
                );
                $result = M('arctype')->add($data);
            } else {
                //存在则执行修改信息操作
                $data = array(
                    'seo_title' => $seoTitle,
                    'seo_keywords' => $seoKeywords,
                    'seo_description' => $seoDescription,
                );
                $result = M('arctype')->where(array('app_id' => $appId, 'cat_id' => $catId))->save($data);
            }
            if ($result) {
                $this->outputJSON(false, '000000', '编辑成功');
            } else {
                $this->outputJSON(true, '100001', '编辑失败');
            }
        } else {
            if (empty($appId) || empty($catId)) {
                $this->error('参数错误，无法编辑');
            }
            $arcInfo = M('arctype')->field('id,app_id,cat_id,seo_title,seo_keywords,seo_description')
                ->where(array('app_id' => $appId, 'cat_id' => $catId))
                ->find();
            $this->assign('app_id', $appId);
            $this->assign('cat_id', $catId);
            $this->assign('arcInfo', $arcInfo);
            $this->display();
        }

    }

    /**
     * 专题的高级设置（关于seo）
     * @author xu
     * @since 2017/07/27 23:05
     */
    public function advance_seo_edit() {
        $appId = intval(I('app_id'));
        if (IS_AJAX) {
            if (empty($appId)) {
                $this->outputJSON(true, '100001', '参数错误，无法编辑');
            }
            //seo的信息允许为空
            $seoTitle = trim(I('seo_title'));
            $seoKeywords = trim(I('seo_keywords'));
            $seoDescription = trim(I('seo_description'));

            //游戏id不为空，栏目id为0表示是针对某款游戏做seo
            $arcInfo = M('arctype')->field('id,app_id,cat_id,seo_title,seo_keywords,seo_description')
                ->where(array('app_id' => $appId, 'cat_id' => 0))
                ->find();

            if (empty($arcInfo)) {
                //不存在则执行插入信息操作
                $data = array(
                    'app_id' => $appId,
                    'cat_id' => 0,
                    'seo_title' => $seoTitle,
                    'seo_keywords' => $seoKeywords,
                    'seo_description' => $seoDescription,
                );
                $result = M('arctype')->add($data);
            } else {
                //存在则执行修改信息操作
                $data = array(
                    'seo_title' => $seoTitle,
                    'seo_keywords' => $seoKeywords,
                    'seo_description' => $seoDescription,
                );
                $result = M('arctype')->where(array('app_id' => $appId, 'cat_id' => 0))->save($data);
            }
            if ($result) {
                $this->outputJSON(false, '000000', '编辑成功');
            } else {
                $this->outputJSON(true, '100001', '编辑失败');
            }
        } else {
            if (empty($appId)) {
                $this->error('参数错误，无法编辑');
            }
            $seoInfo = M('arctype')->field('id,app_id,cat_id,seo_title,seo_keywords,seo_description')
                ->where(array('app_id' => $appId, 'cat_id' => 0))
                ->find();
            $this->assign('app_id', $appId);
            $this->assign('seoInfo', $seoInfo);
            $this->display();
        }
    }

    /**
     * 添加子类栏目
     * @author xy
     * @since 2017/07/27 23:36
     */
    public function child_column_add() {
        //当前要添加子类的栏目
        $catId = intval(I('cat_id'));
        //要添加子栏目的游戏id
        $appId = intval(I('app_id'));
        $service = new ArticleService();
        if (IS_AJAX) {
            if (empty($catId) || empty($appId)) {
                $this->outputJSON(true, '100001', '参数错误，无法编辑');
            }
            //子栏目名称
            $catName = trim(I('cat_name'));
            if (empty($catName)) {
                $this->outputJSON(true, '100001', '请填写子栏目名称');
            }
            $sort = intval(I('sort'));
            //执行插入指娱zy_category表的操作
            $insertData = array(
                'app_id' => $appId,
                'parent_id' => $catId,
                'cat_name' => $catName,
                'sort' => $sort,
                'create_time' => time(),
                'type' => self::ARTICLE_TYPE_APP,
            );

            $result = $service->addAppArticleColumnCategory($insertData);
            if (!$result) {
                $this->outputJSON(true, '100001', '添加失败');
            }
            $this->outputJSON(false, '000000', '添加成功');


        } else {
            if (empty($catId) || empty($appId)) {
                $this->error('参数错误，无法编辑');
            }
            //通过app_id与栏目id获取栏目名称
            $catName = $service->getColumnNameByCatId($catId, $appId);
            $this->assign('catId', $catId);
            $this->assign('catName', $catName);
            //获取游戏名称
            $appName = AppService::getAppNameByAppId($appId);
            $this->assign('appName', $appName);
            $this->assign('appId', $appId);
            $this->display();
        }

    }

    /**
     * 添加一级栏目
     * @author xy
     * @since 2017/07/28 14:02
     */
    public function top_column_add()
    {
        //要添加子栏目的游戏id
        $appId = intval(I('app_id'));
        if (IS_AJAX) {
            if (empty($appId)) {
                $this->outputJSON(true, '100001', '参数错误，无法添加');
            }
            $catName = trim(I('cat_name'));
            if (empty($catName)) {
                $this->outputJSON(true, '100001', '请填写栏目名称');
            }
            $sort = intval(I('sort'));
            //执行插入指娱zy_category表的操作
            $insertData = array(
                'app_id' => $appId,
                'parent_id' => 0,
                'cat_name' => $catName,
                'sort' => $sort,
                'create_time' => time(),
                'type' => self::ARTICLE_TYPE_APP,
            );

            $service = new ArticleService();
            $result = $service->addAppArticleTopColumnCategory($insertData);
            if (!$result) {
                $this->outputJSON(true, '100001', '添加失败');
            }
            $this->outputJSON(false, '000000', '添加成功');

        } else {
            if (empty($appId)) {
                $this->error('参数错误，无法添加');
            }
            $this->assign('appId', $appId);
            $this->display();
        }
    }

    /**
     * 查看文章列表
     * @author xy
     * @since 2017/07/28 14:40
     */
    public function article_list() {
        //分类id
        $catId = intval(I('cat_id'));
        //游戏id
        $appId = intval(I('app_id'));
        if (empty($catId) || empty($appId)) {
            $this->error('参数错误，无法获取文章列表');
        }
        //获取游戏的名称
        $appName = AppService::getAppNameByAppId($appId);
        if (!empty($appName)) {
            $this->assign('appName', $appName);
        }
        //文章标题
        $title = trim(I('title'));
        if (!empty($title)) {
            $where['a.title'] = array('like', '%' . $title . '%');
        }
        $where['a.app_id'] = $appId;
        //根据分类获取该分类以及该分类子类的所有文章
        $service = new ArticleService();
        $cateIdArr = $service->getAppArticleColumnIdAndChildColumnIdByCatIdAndAppId($catId, $appId);
        if (empty($cateIdArr)) {
            //当前栏目分类下，且游戏id为$appId的文章
            $where['_string'] = 'a.catid =' . $catId . ' AND FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)';
        } else {
            //当前栏目分类以及子分类下，且游戏id为$appId的文章
            $where['_string'] = 'a.catid IN (' . implode(',', $cateIdArr) . ') AND FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)';
        }

        $totalCount = $service->getArticleListTotalNum($where); //获取总条数
        // 分页
        $page = intval(I('p'));
        $pageSize = intval(I('pagesize'));
        $pageSize = $pageSize > 0 ? $pageSize : DEFAULT_PAGE_SIZE; //每页显示条数
        $totalPages = ceil($totalCount / $pageSize);        //总页数
        $page = $page > $totalPages ? $totalPages : $page;
        $page = $page > 0 ? $page : 1;
        $currentPage = $pageSize * ($page - 1);
        $this->assign('firstRow', $currentPage);
        $this->assign('page', $page);
        $this->assign('pages', $totalPages);
        $this->assign('pagesize', $pageSize);

        //根据分页条件获取文章列表 , 按自定义升序排序
        $articleList = $service->getArticleListByPage($where, $currentPage, $pageSize);
        $this->assign('title', $title);
        $this->assign('articleList', $articleList);
        $this->assign('catId', $catId);
        $this->assign('appId', $appId);
        $this->display();
    }

    /**
     * 所有文章列表 按创建时间倒序排序
     * @author xy
     * @since 2017/07/31 10:44
     */
    public function all_article_list() {
        $where = array();
        $gameOrTitle = trim(I('game_or_title'));
        $status = intval(I('status'));
        $where['_string'] = ' FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position) ';
        if (!empty($gameOrTitle)) {
            $where['_string'] .= '(alb.app_name like "%' . $gameOrTitle . '%" OR a.title like "%' . $gameOrTitle . '%" )';
        }
        //文章状态，0未发布，1发布
        if ($status > 0) {
            $where['a.status'] = $status - 1;
        }
        //根据分类获取该分类以及该分类子类的所有文章
        $service = new ArticleService();

        $totalCount = $service->getAllArticleListTotalNum($where); //获取总条数
        // 分页
        $page = intval(I('p'));
        $pageSize = intval(I('pagesize'));
        $pageSize = $pageSize > 0 ? $pageSize : 20; //每页显示条数
        $totalPages = ceil($totalCount / $pageSize);        //总页数
        $page = $page > $totalPages ? $totalPages : $page;
        $page = $page > 0 ? $page : 1;
        $currentPage = $pageSize * ($page - 1);
        $this->assign('firstRow', $currentPage);
        $this->assign('page', $page);
        $this->assign('pages', $totalPages);
        $this->assign('pagesize', $pageSize);

        //根据分页条件获取文章列表,按创建时间倒序排序
        $articleList = $service->getAllArticleListByPage($where, $currentPage, $pageSize);

        $this->assign('articleList', $articleList);
        $this->assign('gameOrTitle', $gameOrTitle);
        $this->assign('status', $status);

        $this->display();
    }

    /**
     * 修改文章状态，发布文章
     * @author xy
     * @since 2017/07/28 19:01
     */
    public function ajax_shelve_article() {
        $articleId = intval(I('article_id'));
        if (empty($articleId)) {
            $this->outputJSON(true, '100001', '获取文章id参数错误');
        }
        //从文章库获取文章
        $article = M(C('DB_ZHIYU.DB_NAME') . '.' . 'article', C('DB_ZHIYU.DB_PREFIX'))->where('id = ' . $articleId)->find();
        if (empty($article)) {
            $this->outputJSON(true, '100001', '未找到id为' . $articleId . '的文章');
        }
        if (!empty($article['status'])) {
            $this->outputJSON(false, '000000', '文章已是发布状态');
        } else {
            $data['admin_id'] = $this->user_info['id'];
            $data['status'] = 1;
            $data['update_time'] = time();
            $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'article', C('DB_ZHIYU.DB_PREFIX'))->where('id = ' . $articleId)->save($data);
            if (empty($result)) {
                $this->outputJSON(true, '100001', '发布失败');
            }
            $this->outputJSON(false, '000000', '发布成功');
        }
    }

    /**
     * 下架文章
     * @author xy
     * @since 2017/07/30 12:27
     */
    public function ajax_unshelve_article() {
        $articleId = intval(I('article_id'));
        if (empty($articleId)) {
            $this->outputJSON(true, '100001', '获取文章id参数错误');
        }
        //从文章库获取文章
        $article = M(C('DB_ZHIYU.DB_NAME') . '.' . 'article', C('DB_ZHIYU.DB_PREFIX'))->where('id = ' . $articleId)->find();
        if (empty($article)) {
            $this->outputJSON(true, '100001', '未找到id为' . $articleId . '的文章');
        }
        if (empty($article['status'])) {
            $this->outputJSON(false, '000000', '文章已是下架状态');
        } else {
            $data['admin_id'] = $this->user_info['id'];
            $data['status'] = 0;
            $data['update_time'] = time();
            $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'article', C('DB_ZHIYU.DB_PREFIX'))->where('id = ' . $articleId)->save($data);
            if (empty($result)) {
                $this->outputJSON(true, '100001', '下架失败');
            }
            $this->outputJSON(false, '000000', '下架成功');
        }
    }

    /**
     * 删除文章
     * @author xy
     * @since 2017/07/30 13:12
     */
    public function article_delete_status_change() {
        $articleId = intval(I('article_id'));
        if (empty($articleId)) {
            $this->outputJSON(true, '100001', '获取文章id参数错误');
        }
        //从文章库获取文章
        $article = M(C('DB_ZHIYU.DB_NAME') . '.' . 'article', C('DB_ZHIYU.DB_PREFIX'))->where('id = ' . $articleId)->find();
        if (empty($article)) {
            $this->outputJSON(true, '100001', '未找到id为' . $articleId . '的文章');
        }
        $data['admin_id'] = $this->user_info['id'];
        if (empty($article['is_delete'])) {
            //如果是未删除状态则执行删除操作
            $data['is_delete'] = 1;
            $data['update_time'] = time();
        } else {
            //如果是删除状态则执行恢复操作
            $data['is_delete'] = 0;
            $data['update_time'] = time();
        }
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'article', C('DB_ZHIYU.DB_PREFIX'))->where('id = ' . $articleId)->save($data);
        if (empty($result)) {
            $this->outputJSON(true, '100001', '执行失败');
        }
        $this->outputJSON(false, '000000', '执行成功');

    }


    /**
     * ajax方式获取一级栏目
     * @author xy
     * @since 2017/07/28 10:40
     */
    public function ajax_get_level_one_column() {
        $catId = intval(I('cat_id'));
        //栏目类型 0 游戏专题， 1新闻中心
        $type = intval(I('type'));
        $columns = array();
        if (empty($catId)) {
            $columns = ArticleService::getAllLevelOneColumnByType($type);
        } else {
            $column = ArticleService::getLevelOneColumnByCatIdAndType($catId, $type);
            if (!empty($column)) {
                $columns[] = $column;
            }
        }
        if (!$columns) {
            $this->outputJSON(true, '100001', '获取一级栏目失败');
        }
        $this->outputJSON(false, '000000', '获取一级栏目成功', $columns);
    }

    /**
     * ajax方式获取游戏的信息
     * @author xy
     * @since 2017/07/28 11:27
     */
    public function ajax_get_app_info() {
        $appId = intval(I('app_id'));
        $apps = array();
        //获取游戏名称与id
        if (empty($appId)) {
            $apps = AppService::getAllAppIdAndName();
        } else {
            $app = AppService::getAppIdAndNameByAppId($appId);
            if (!empty($app)) {
                $apps[] = $app;
            }
        }
        if (!$apps) {
            $this->outputJSON(true, '100001', '获取游戏失败');
        }
        $this->outputJSON(false, '000000', '获取游戏成功', $apps);
    }

    /**
     * 新闻中心列表
     * @author xy
     * @since 2017/08/02 09:28
     */
    public function news_list() {
        $catId = intval(I('cat_id'));
        //文章标题
        $title = trim(I('title'));
        if (!empty($title)) {
            $where['a.title'] = array('like', '%' . $title . '%');
        }
        //根据分类获取该分类以及该分类子类的所有文章
        $service = new ArticleService();
        $cateIdArr = $service->getNewsArticleColumnIdAndChildColumnIdByCatId($catId);
        if (empty($cateIdArr)) {
            //当前栏目分类下，且游戏id为$appId的展示在媒体站文章
            $where['_string'] = 'a.catid =' . $catId . ' AND a.app_id = 0 AND FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)';
        } else {
            //当前栏目分类以及子分类下，且游戏id为$appId的展示在媒体站文章
            $where['_string'] = 'a.catid IN (' . implode(',', $cateIdArr) . ') AND a.app_id = 0 AND FIND_IN_SET(\''.self::ART_SHOW_POSITION_MEDIA.'\', a.show_position)';
        }

        $totalCount = $service->getArticleListTotalNum($where); //获取总条数
        // 分页
        $page = intval(I('p'));
        $pageSize = intval(I('pagesize'));
        $pageSize = $pageSize > 0 ? $pageSize : DEFAULT_PAGE_SIZE; //每页显示条数
        $totalPages = ceil($totalCount / $pageSize);        //总页数
        $page = $page > $totalPages ? $totalPages : $page;
        $page = $page > 0 ? $page : 1;
        $currentPage = $pageSize * ($page - 1);
        $this->assign('firstRow', $currentPage);
        $this->assign('page', $page);
        $this->assign('pages', $totalPages);
        $this->assign('pagesize', $pageSize);

        //根据分页条件获取文章列表 , 按自定义升序排序
        $articleList = $service->getArticleListByPage($where, $currentPage, $pageSize);

        $catName = $service->getColumnNameByCatId($catId, 0);
        $this->assign('catName', $catName);
        $this->assign('title', $title);
        $this->assign('articleList', $articleList);
        $this->assign('catId', $catId);

        $this->display();
    }

    /**
     * 新闻栏目的seo设置
     * @author xy
     * @since 2017/08/02 10:23
     */
    public function news_column_seo_edit() {
        $catId = intval(I('cat_id'));
        if (IS_AJAX) {
            if (empty($catId)) {
                $this->outputJSON(true, '100001', '参数错误，无法编辑');
            }
            //seo的信息允许为空
            $seoTitle = trim(I('seo_title'));
            $seoKeywords = trim(I('seo_keywords'));
            $seoDescription = trim(I('seo_description'));

            //游戏id空，栏目id不为0表示是针对新闻栏目做seo
            $arcInfo = M('arctype')->field('id,app_id,cat_id,seo_title,seo_keywords,seo_description')
                ->where(array('cat_id' => $catId, 'app_id' => 0))
                ->find();

            if (empty($arcInfo)) {
                //不存在则执行插入信息操作
                $data = array(
                    'app_id' => 0,
                    'cat_id' => $catId,
                    'seo_title' => $seoTitle,
                    'seo_keywords' => $seoKeywords,
                    'seo_description' => $seoDescription,
                );
                $result = M('arctype')->add($data);
            } else {
                //存在则执行修改信息操作
                $data = array(
                    'seo_title' => $seoTitle,
                    'seo_keywords' => $seoKeywords,
                    'seo_description' => $seoDescription,
                );
                $result = M('arctype')->where(array('cat_id' => $catId, 'app_id' => 0))->save($data);
            }
            if ($result) {
                $this->outputJSON(false, '000000', '编辑成功');
            } else {
                $this->outputJSON(true, '100001', '编辑失败');
            }
        } else {
            if (empty($catId)) {
                $this->error('参数错误，无法编辑');
            }
            $seoInfo = M('arctype')->field('id, app_id, cat_id, seo_title, seo_keywords, seo_description')
                ->where(array('cat_id' => $catId, 'app_id' => 0))
                ->find();
            $this->assign('cat_id', $catId);
            $this->assign('seoInfo', $seoInfo);
            $this->display();
        }
    }

    /**
     * 新闻栏目添加子栏目
     * @author xy
     * @since 2017/078/02 10:34
     */
    public function news_child_column_add() {
        //当前要添加子类的栏目
        $catId = intval(I('cat_id'));
        $service = new ArticleService();
        if (IS_AJAX) {
            if (empty($catId)) {
                $this->outputJSON(true, '100001', '参数错误，无法编辑');
            }
            //子栏目名称
            $catName = trim(I('cat_name'));
            if (empty($catName)) {
                $this->outputJSON(true, '100001', '请填写子栏目名称');
            }
            $sort = intval(I('sort'));
            //执行插入指娱zy_category表的操作
            $insertData = array(
                'admin_id' => $this->user_info['id'],
                'app_id' => 0,
                'parent_id' => $catId,
                'cat_name' => $catName,
                'sort' => $sort,
                'create_time' => time(),
                'type' => self::ARTICLE_TYPE_NEWS,
            );

            $result = $service->addNewsArticleColumnCategory($insertData);
            if (!$result) {
                $this->outputJSON(true, '100001', '添加失败');
            }
            $this->outputJSON(false, '000000', '添加成功');

        } else {
            if (empty($catId)) {
                $this->error('参数错误，无法编辑');
            }
            //1.如果有父级栏目id则去对应id的那条数据
            $catName = $service->getColumnNameByCatId($catId, 0);
            $this->assign('catId', $catId);
            $this->assign('catName', $catName);

            $this->display();
        }

    }

    /**
     * 新闻中心添加一级栏目
     * @author xy
     * @since 2017/08/02 10:35
     */
    public function news_top_column_add() {
        if (IS_AJAX) {
            $catName = trim(I('cat_name'));
            if (empty($catName)) {
                $this->outputJSON(true, '100001', '请填写栏目名称');
            }
            $sort = intval(I('sort'));
            //执行插入指娱zy_category表的操作
            $insertData = array(
                'admin_id' => $this->user_info['id'],
                'app_id' => 0,
                'parent_id' => 0,
                'cat_name' => $catName,
                'sort' => $sort,
                'create_time' => time(),
                'type' => self::ARTICLE_TYPE_NEWS,
            );

            $service = new ArticleService();
            $result = $service->addNewsArticleTopColumnCategory($insertData);
            if (!$result) {
                $this->outputJSON(true, '100001', '添加失败');
            }
            $this->outputJSON(false, '000000', '添加成功');

        } else {

            $this->display();
        }
    }
}
