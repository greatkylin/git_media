<?php
/**
 * 后台右侧菜单相关操作
 * Date: 2017/8/1
 * Time: 17:29
 */

namespace Admin\Service;
use Common\Service\BaseService;

class MenuService extends BaseService
{
    /**
     * 获取左侧的菜单
     * @author xy
     * @since 2017/07/31 16:03
     * @param $type
     * @return array|mixed
     */
    public function getLeftMenu($type){
        $leftMenu = array();
        switch ($type){
            case 1:
                //获取首页管理的菜单
                $leftMenu = $this->getIndexMenu();
                break;
            case 2:
                //获取游戏库管理的菜单
                $leftMenu = $this->getAppLibMenu();
                break;
            case 3:
                //获取游戏专题的菜单
                $leftMenu = $this->getArticleMenu();
                break;
            case 4:
                //获取新闻中心的菜单
                $leftMenu = $this->getNewCenterMenu();
                break;
            case 5:
                //获取单页内容的菜单
                $leftMenu = $this->getContentMenu();
                break;
            default:
                break;
        }
        return $leftMenu;
    }

    /**
     * 获取图片分类作为左侧的菜单
     * @author xy
     * @since 2017/07/31 16:02
     * @return array|mixed
     */
    protected function getIndexMenu(){
        $category = M('index_column_category')
            ->field('id, keyword, is_delete, name')
            ->where('is_delete = 1')->select();
        if(!empty($category)){
            $newCategory = array();
            foreach ($category as $key => $value){
                $newCategory[$key]['id'] = $value['id'];
                $newCategory[$key]['title'] = $value['name'];
                $newCategory[$key]['name'] = U('Admin/IndexCont/content_list/category_id/'.$value['id']);
                $newCategory[$key]['menu_type'] = '';
                $newCategory[$key]['css'] = '';
                $newCategory[$key]['sort'] = $key;
                $newCategory[$key]['isshow'] = $value['status'];
                $newCategory[$key]['level'] = '1';
                $newCategory[$key]['has_child'] = '0';
                $newCategory[$key]['menu_child'] = array();
            }
            return $newCategory;
        }
        return $category;
    }


    /**
     * 获取文章管理的左侧菜单
     * @author xy
     * @since 2017/07/31 18:23
     * @param NULL $appName 游戏名称
     * @return array
     */
    protected function getArticleMenu( $appName= NULL){
        $where = array();
        if(!empty($appName)){
            $where['_string'] = ' alib.app_name like "%'.$appName.'%"';
        }
        //默认取已上架的或者测试上架的游戏
        //$where = array('list.status' => array('IN', array(1, 2)));
        //查询媒体站游戏专题的文章数量
        $subQuery =
            ' SELECT app_id, COUNT(*) as total_num FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'article'.
            ' WHERE FIND_IN_SET(\'2\',show_position) AND app_id <> 0'.
            ' GROUP BY `app_id` ';
        $appList = M('app_list')->alias('list')->cache(true,3600)
            ->field('alist.id, alist.app_id, alib.app_name')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list alist on alist.app_id=list.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id=list.app_id')
            ->join('LEFT JOIN ('.$subQuery.') AS an ON an.app_id = alist.app_id')
            ->where($where)
            ->order('(alist.app_down_num + alist.cardinal) DESC, list.is_publish ASC')
            ->select();

        if(!empty($appList)){
            $newList = array();
            foreach ($appList as $key => $value){
                $newList[$key]['id'] = $value['id'];
                $newList[$key]['title'] = $value['app_name'];
                $newList[$key]['name'] = U('Admin/ArticleLib/column_list/app_id/'.$value['app_id']);
                $newList[$key]['menu_type'] = '';
                $newList[$key]['css'] = '';
                $newList[$key]['sort'] = $key;
                $newList[$key]['isshow'] = '1';
                $newList[$key]['level'] = '1';
                $newList[$key]['has_child'] = '0';
                $newList[$key]['menu_child'] = array();
            }
            return $newList;
        }
        return $appList;
    }

    /**
     * 获取次导航的左侧菜单
     * @author xy
     * @since 2017/07/31 18:25
     */
    protected function getContentMenu(){
        $contentList = M('independent_content')->field('id, title, sort, is_delete')->select();
        if(!empty($contentList)){
            $newList = array();
            foreach ($contentList as $key => $value){
                $newList[$key]['id'] = $value['id'];
                $newList[$key]['title'] = $value['title'];
                $newList[$key]['name'] = U('Admin/Content/content_list/id/'.$value['id']);
                $newList[$key]['menu_type'] = '';
                $newList[$key]['css'] = '';
                $newList[$key]['sort'] = $value['sort'];
                $newList[$key]['isshow'] = '1';
                $newList[$key]['level'] = '1';
                $newList[$key]['has_child'] = '0';
                $newList[$key]['menu_child'] = array();
            }
            return $newList;
        }
        return $contentList;
    }

    /**
     * 获取新闻中心的左侧菜单
     * @author xy
     * @since 2017/08/02 14:45
     * @return array|bool
     */
    protected function getNewCenterMenu(){
        $newsColumn = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
            ->where('app_id = 0 AND type = 1')
            ->select();
        //递归获取菜单数组
        if(!empty($newsColumn)){
            $menuArr = $this->getNewsMenuArrayRecursive($newsColumn, 0, 1);
            return $menuArr;
        }
        return $newsColumn;

    }

    /**
     * 游戏库管理左侧菜单
     * @author xy
     * @since 2017/08/02 16:26
     * @return array
     */
    public function getAppLibMenu(){
        return $menuArr = array(
            array(
                'name' => 'AppLib/app_list',
                'title' => '游戏库管理',
                'menu_type' => 4,
                'css' => 'Hui-iconfont-home',
                'sort' => 1,
                "isshow" => 1,
                "level" => 1,
                "has_child" => 1,
                "menu_child" => array(
                    array(
                        'name' => 'AppLib/app_list',
                        'title' => '游戏列表',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 1,
                        "menu_child" => array(
                            array(
                                'name' => 'Admin/AppLib/app_list/type/1',
                                'title' => '人气最高',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(

                                ),
                            ),
                            array(
                                'name' => 'Admin/AppLib/app_list/type/2',
                                'title' => '近期更新',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(

                                ),
                            ),
                        ),
                    ),
                    array(
                        'name' => 'AppLib/applib_list',
                        'title' => '游戏详情列表',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 0,
                        "menu_child" => array(
                        ),
                    ),
                    array(
                        'name' => 'AppTopic/app_topic_list',
                        'title' => '游戏专题管理',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 1,
                        "menu_child" => array(
                            array(
                                'name' => 'AppTopic/app_topic_list',
                                'title' => '游戏专题列表',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(

                                ),
                            ),
                            array(
                                'name' => 'AppTopic/list_image',
                                'title' => '专题列表图片',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(

                                ),
                            ),
                        ),
                    ),
                ),
            ),
            array(
                'name' => 'Gift/gift_list',
                'title' => '礼包管理',
                'menu_type' => 4,
                'css' => 'Hui-iconfont-home',
                'sort' => 1,
                "isshow" => 1,
                "level" => 1,
                "has_child" => 1,
                "menu_child" => array(
                    array(
                        'name' => 'AppLib/app_list',
                        'title' => '礼包列表',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 1,
                        "menu_child" => array(
                            array(
                                'name' => 'Admin/Gift/gift_list/list_type/1',
                                'title' => '热门礼包',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(

                                ),
                            ),
                            array(
                                'name' => 'Admin/Gift/gift_list/list_type/2',
                                'title' => '新游礼包',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(

                                ),
                            ),
                        ),

                    ),
                    array(
                        'name' => 'Admin/Gift/app_gift_list',
                        'title' => '游戏礼包',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 0,
                        "menu_child" => array(),
                    ),
                    array(
                        'name' => 'Admin/Gift/gift_slide_list',
                        'title' => '礼包中心轮播',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 0,
                        "menu_child" => array(),
                    ),
                    array(
                        'name' => 'Admin/Gift/gift_index_ad_list',
                        'title' => '礼包中心右侧广告',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 0,
                        "menu_child" => array(),
                    )
                ),
            ),
            array(
                'name' => 'Admin/AppRank/download_rank_list',
                'title' => '排行榜管理',
                'menu_type' => 4,
                'css' => 'Hui-iconfont-home',
                'sort' => 1,
                "isshow" => 1,
                "level" => 1,
                "has_child" => 1,
                "menu_child" => array(
                    array(
                        'name' => 'Admin/AppRank/download_rank_list',
                        'title' => '热游榜',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 1,
                        "menu_child" => array(
                            array(
                                'name' => 'Admin/AppRank/download_rank_list/data_source/1',
                                'title' => '周榜',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(
                                ),
                            ),
                            array(
                                'name' => 'Admin/AppRank/download_rank_list/data_source/2',
                                'title' => '月榜',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(

                                ),
                            ),
                            array(
                                'name' => 'Admin/AppRank/download_rank_list/data_source/3',
                                'title' => '总榜',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(

                                ),
                            ),
                        ),

                    ),
                    array(
                        'name' => 'Admin/AppRank/popular_rank_list',
                        'title' => '畅销榜',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 1,
                        "menu_child" => array(
                            array(
                                'name' => 'Admin/AppRank/popular_rank_list/data_source/1',
                                'title' => '周榜',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(
                                ),
                            ),
                            array(
                                'name' => 'Admin/AppRank/popular_rank_list/data_source/2',
                                'title' => '月榜',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(

                                ),
                            ),
                            array(
                                'name' => 'Admin/AppRank/popular_rank_list/data_source/3',
                                'title' => '总榜',
                                'menu_type' => 4,
                                'css' => 'Hui-iconfont-home',
                                'sort' => 1,
                                "isshow" => 1,
                                "level" => 2,
                                "has_child" => 0,
                                "menu_child" => array(

                                ),
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Admin/AppRank/new_rank_list/',
                        'title' => '新游榜',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 0,
                        "menu_child" => array(),
                    )
                ),
            ),
            array(
                'name' => 'Admin/Activity/activity_list/',
                'title' => '热门活动',
                'menu_type' => 4,
                'css' => 'Hui-iconfont-home',
                'sort' => 1,
                "isshow" => 1,
                "level" => 1,
                "has_child" => 1,
                "menu_child" => array(
                    array(
                        'name' => 'Admin/Activity/activity_list/',
                        'title' => '活动列表',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 0,
                        "menu_child" => array(),
                    )
                ),
            )
        );
    }

    /**
     * 生成左侧菜单html
     * @author xy
     * @since 2017/08/02 12:01
     * @param array $data
     * @return string
     */
    protected function generateMenuHtml(array $data){
        $html = '';
        if(empty($data)){
            $this->setError('要操作的数组为空，无法继续操作');
            return $html;
        }
        foreach ($data as $key=>$menu){
            $html .=
                '<dl id="menu-'.$menu['id'].'">'.
                '<dt>';
            if(!empty($menu['css'])){
                $html .= '<i class="Hui-iconfont '.$menu['css'].'"></i>';
            }else{
                $html .= '<i class="Hui-iconfont Hui-iconfont-system"></i>';
            }
            if($menu['has_child'] == 1){
                $html .= $menu['title'].'<i class="Hui-iconfont menu_dropdown-arrow Hui-iconfont-arrow2-bottom"></i>';
            }else{
                $html .= '<a style="font-weight: bold;" data-href="'.$menu['name'].'" data-title="'.$menu['title'].'" >&nbsp;&nbsp;'.$menu['title'].'</a>';
            }
            $html .=
                '</dt>'.
                '<dd>';
            if($menu['has_child'] == 1){
                $html .='<ul>';
                foreach ( $menu['menu_child'] as $k=>$secMenu){
                    $html .='<li>';
                    if($secMenu['has_child'] == 1){
                        $html .='<span class="span-p" data-title="'.$secMenu['title'].'" href="javascript:void(0);"><i class="Hui-iconfont Hui-iconfont-add"></i>&nbsp;&nbsp;'.$secMenu['title'].'</span>';
                    }else{
                        $html .='<a style="font-weight: bold;" data-href="'.$secMenu['name'].'" data-title="'.$secMenu['title'].'">&nbsp;&nbsp;'.$secMenu['title'].'<a>';
                    }
                    if($secMenu['has_child'] == 1){
                        $html .='<ul class="child_menu">';
                        foreach ($secMenu['menu_child'] as $trdMenu){
                            $html .= '<li><a data-href="'.$trdMenu['name'].'" data-title="'.$trdMenu['title'].'"  href="javascript:void(0);"><i class="Hui-iconfont Hui-iconfont-arrow2-right"></i>&nbsp;&nbsp;'.$trdMenu['title'].'</a></li>';
                        }
                        $html .='</ul>';
                    }
                    $html .='</li>';
                }
                $html .='</ul>';
            }
            $html .=
                '</dd>'.
                '</dl>';

        }
        return $html;

    }

    /**
     * 通过游戏名称获取菜单项，并生成html
     * @author xy
     * @since 2017/08/01 18:07
     * @param $appName
     * @return bool
     */
    public function generateArticleLeftMenuByAppName($appName){
        $appList = $this->getArticleMenu($appName);
        $html = $this->generateMenuHtml($appList);
        return $html;
    }


    /**
     * 递归生成新闻中心菜单参数数组
     * @author xy
     * @since 2017/08/02 14:20
     * @param array $data 原数组
     * @param int $parentId 父级id
     * @param int $level 菜单层级
     * @return array|bool
     */
    protected function getNewsMenuArrayRecursive(array $data, $parentId = 0, $level = 1){
        if(empty($data)){
            $this->setError('要操作的数组为空，无法继续操作');
            return false;
        }
        $menuArr = array();
        foreach ($data as $key=>$value){
            $newArray = array();
            if( $value['parent_id'] == $parentId){
                $newArray['id'] = $value['catid'];
                $newArray['title'] = $value['cat_name'];
                $newArray['name'] = U('/Admin/ArticleLib/news_list/cat_id/'.$value['catid']);
                $newArray['menu_type'] = '';
                $newArray['css'] = '';
                $newArray['sort'] = $value['sort'];
                $newArray['isshow'] = '1';
                $newArray['level'] = $level;
                $newArray['has_child'] = '0';
                $newArray['menu_child'] = $this->getNewsMenuArrayRecursive($data,$value['catid'],$level+1);
                if(!empty($newArray['menu_child'])){
                    $newArray['has_child'] = '1';
                }
                $menuArr[] = $newArray;
            }
        }
        return $menuArr;

    }
}