<?php
namespace Admin\Controller;
use Admin\Service\MenuService;
use Think\Controller;
use Think\Auth;

class IndexController extends AdminBaseController {
    public function __construct() {
        //控制器初始化
        parent::__construct();
    }

    public function index(){

        if (!session('user_info')) {
            // 未登录跳到登录
            $this->redirect('Index/login');
            exit;
        } else {
            // 左菜单栏
            $left_menu = $this->left_menu();
//            $this->assign('keywords', $this->Config['sitekeywords']);
//            $this->assign('description', $this->Config['siteinfo']);
            $this->assign('left_menu', $left_menu);
            $this->display();
            // 已登录跳后台首页
        }
    }

    public function welcome(){
//        $username = $this->user_info['username'];
//        $count = M('admin_loginlog')->where(array('username' => $username))->count();
//        $last_login_time = $this->user_info['last_login_time'];
//        $last_login_ip = $this->user_info['last_login_ip'];
//        $this->assign('count', $count);
//        $this->assign('last_login_time', $last_login_time);
//        $this->assign('last_login_ip', $last_login_ip);
        $this->display();
    }

    /**
     * 获取左菜单栏目
     */
    public function left_menu()
    {
        // 菜单
        $my_auth = array(
            array(
                'name' => 'App/app_list',
                'title' => '落地页管理',
                'menu_type' => 4,
                'css' => 'Hui-iconfont-home',
                'sort' => 1,
                "isshow" => 1,
                "level" => 1,
                "has_child" => 1,
                "menu_child" => array(
                    array(
                        'name' => 'App/app_list',
                        'title' => '落地页列表',
                        'menu_type' => 4,
                        'css' => 'Hui-iconfont-home',
                        'sort' => 1,
                        "isshow" => 1,
                        "level" => 2,
                        "has_child" => 0,
                        "menu_child" => array(
//                            array(
//                                'name' => 'App/app_add',
//                                'title' => '添加落地页',
//                                'menu_type' => 4,
//                                'css' => 'Hui-iconfont-home',
//                                'sort' => 1,
//                                "isshow" => 0,
//                                "level" => 1,
//                                "has_child" => 0,
//                                "menu_child" => array(),
//                            ),
//                            array(
//                                'name' => 'App/app_edit',
//                                'title' => '编辑落地页',
//                                'menu_type' => 4,
//                                'css' => 'Hui-iconfont-home',
//                                'sort' => 2,
//                                "isshow" => 1,
//                                "level" => 1,
//                                "has_child" => 0,
//                                "menu_child" => array(),
//                            ),
//                            array(
//                                'name' => 'App/app_status',
//                                'title' => '落地页上下架',
//                                'menu_type' => 4,
//                                'css' => 'Hui-iconfont-home',
//                                'sort' => 3,
//                                "isshow" => 1,
//                                "level" => 1,
//                                "has_child" => 0,
//                                "menu_child" => array(),
//                            ),
                        ),
                    ),
                ),
            ),
        );

        return $my_auth;
    }


    /**
     * 新后台首页
     */
    public function nindex(){
        if (!session('user_info')) {
            // 未登录跳到登录
            $this->redirect('Index/login');
            exit;
        } else {
            $type = intval(I('type'));
            if(empty($type)){
                $type = 1;
            }
            $service = new MenuService();
            $topMenu = $this->getTopMenu();
            $leftMenu = $service->getLeftMenu($type);

            //var_dump($leftMenu);die;
            $this->assign('topMenu',$topMenu);
            $this->assign('type',$type);
            $this->assign('leftMenu',$leftMenu);
            $this->display();
        }
    }

    /**
     * 顶部菜单
     * @author xy
     * @since 2017/07/31
     * @return array
     */
    protected function getTopMenu(){
        return array(
            array('name'=>'首页管理','type'=>1),
            array('name'=>'游戏库管理','type'=>2),
            array('name'=>'游戏专题','type'=>3),
            array('name'=>'新闻中心','type'=>4),
            array('name'=>'单页内容管理','type'=>5),
            array('name'=>'其他设置','type'=>6),
        );
    }

    /**
     * 通过搜索游戏名称获取左侧游戏专题的游戏菜单
     * @author xy
     * @since 2017/08/01 16:22
     */
    public function ajax_get_article_column(){
        $appName = trim(I('app_name'));
        $service = new MenuService();
        $html = $service->generateArticleLeftMenuByAppName($appName);
        if(empty($html)){
            $this->outputJSON(true,'100001','获取失败');
        }
        $data['html'] = $html;
        $this->outputJSON(false,'000000','获取成功',$data);
    }


}