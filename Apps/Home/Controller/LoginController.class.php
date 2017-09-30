<?php
/**
 * 登录注册相关控制器
 * User: xy
 * Date: 2017/9/27
 * Time: 9:28
 */

namespace Home\Controller;


use Home\Service\UserService;

class LoginController extends HomeBaseController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 注册操作
     * @author xy
     * @since 2017/09/27 09:29
     */
    public function do_register(){
        if($this->userInfo){
            $this->outputJSON(true, '', '已经登录');
        }
        $userName = trim(I('post.user_name'));
        $password = trim(I('post.password'));
        $password2 = trim(I('post.password2'));
        $isAgree = intval(I('post.is_agree', 0));
        if(empty($userName)){
            $this->outputJSON(true, '100001', '请填写用户名');
        }
        if(empty($password) || empty($password2)){
            $this->outputJSON(true, '100002', '密码不能为空');
        }
        if(empty($isAgree)){
            $this->outputJSON(true, '100003', '请同意注册协议');
        }
        $userService = new UserService();
        //验证用户名是否符合规制
        if(!$userService->validateUserName($userName)){
            $this->outputJSON(true, '100001', $userService->getFirstError());
        }
        //判读该用户名是否被注册
        $existUser = $userService->getUserByUsername($userName);
        if($existUser === false){
            $this->outputJSON(true, '100001', $userService->getFirstError());
        }
        if(!empty($existUser)){
            $this->outputJSON(true, '100001', '该用户名已被注册');
        }
        //验证密码是否符合规则
        if($password != $password2){
            $this->outputJSON(true, '100002', '两次输入的密码不一致');
        }
        if(!$userService->validatePassword($password)){
            $this->outputJSON(true, '100002', $userService->getFirstError());
        }
        //执行注册用户
        $userData = array(
            'username' => $userName,
            'password' => multiMD5(strtoupper(md5($password))),
            'user_type' => 1,
            'register_ip' => get_client_ip(),
            'status' => 0,
            'create_time' => time(),
        );
        $userId = $userService->registerUser($userData);
        if(empty($userId)){
            $this->outputJSON(true, '100001', $userService->getFirstError());
        }
        //保存用户信息到session
        $fullUserInfo = $userService->getUserByUserId($userId);
        if(empty($fullUserInfo)){
            $this->outputJSON(true, '100001', '注册失败');
        }
        unset($fullUserInfo['password']);

        session('media_web_user', $fullUserInfo);

        $userService->afterLogin($fullUserInfo,1);

        $this->outputJSON(false, '000000', '注册成功');
    }

    /**
     * 用户登录操作
     * @author xy
     * @since 2017/09/27 14:56
     */
    public function do_login(){
        if($this->userInfo){
            $this->outputJSON(true, '', '已经登录');
        }
        $loginName = trim(I('post.login_name')); // 手机号或用户名
        $password = trim(I('post.password'));

        if (empty($loginName) || empty($password)){
            $this->outputJSON(true, '100001', '用户名或密码不能为空');
        }
        $password = strtoupper(md5(I('post.password')));

        $userService = new UserService();
        //判断用户是否存在
        $userInfo = $userService->getUser($loginName);
        if (!$userInfo) {
            $this->outputJSON(true, '100001', '用户不存在');
        }

        // 判断封停惩罚
        if(!$userService->checkPunish ($userInfo, 0, '', 0)){
            $this->outputJSON(true, '100001', $userService->getFirstError());
        }

        $user = $userService->login($loginName,  $password);

        if ($user) {
            unset($user['password']);
            session('media_web_user', $user);

            $userService->afterLogin($user,0);
            $this->outputJSON(false, '000000', '登录成功');
        } else {
            $this->outputJSON(true, '', $userService->getFirstError());
        }
    }

    /**
     * 用户登录页
     * @author xy
     * @since 2017/09/27 15:13
     */
    public function login(){
        if($this->getUserInfo()){
            $this->redirect('/');
        }else{
            $this->display();
        }
    }

    /**
     * 退出登录
     * @author xy
     * @since 2017/09/27 15:02
     */
    public function logout(){
        if(session('media_web_user')){
            session('media_web_user', null);
        }
        $this->redirect('/');
    }

    /**
     * ajax方式验证注册时填写的用户名
     * @author xy
     * @since 2017/09/27 16:15
     */
    public function ajax_check_reg_user_name(){
        $userName = trim(I('user_name'));
        if(empty($userName)){
            $this->outputJSON(true, '100001', '请输入用户名');
        }
        $userService = new UserService();
        if(!$userService->validateUserName($userName)){
            $this->outputJSON(true, '100001', $userService->getFirstError());
        }
        $existUser = $userService->getUserByUsername($userName);
        if($existUser === false){
            $this->outputJSON(true, '100001', $userService->getFirstError());
        }
        if(!empty($existUser)){
            $this->outputJSON(true, '100001', '该用户名已被注册');
        }
        $this->outputJSON(false, '000000', '验证通过');
    }

    /**
     * ajax方式验证密码是否符合规制
     * @author xy
     * @since 2017/09/27 16:58
     */
    public function ajax_check_password(){
        $password = trim(I('password'));
        if(empty($password)){
            $this->outputJSON(true, '', '请输入密码');
        }
        $userService = new UserService();
        if(!$userService->validatePassword($password)){
            $this->outputJSON(true, '', $userService->getFirstError());
        }
        $this->outputJSON(false, '', '验证通过');
    }
}