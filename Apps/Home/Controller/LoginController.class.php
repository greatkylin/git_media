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
            $this->outputJSON(false, '', '已经登录');
        }
        $userName = trim(I('user_name'));
        $password = trim(I('password'));
        $password2 = trim(I('password2'));
        $isAgree = intval(I('is_agree', 0));
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
        $paramArr = array(
            'username' => $userName,
            'password' => $password,
            'timestamp' => time(),
        );
        $paramArr['sign'] = make_sign($paramArr, array('sign'));
        //指娱用户媒体站登录接口
        $url = C('URL.ZHIYU_URL').U('Api/UserCenter/user_register_media');
        $result = http($url, $paramArr, 'POST');
        $result = json_decode($result, true);

        if(isset($result['flag']) && $result['flag'] == 'success'){
            $userInfo = $result['data']['obj'];
            session('login_name', $userName);
            session('media_web_user', $userInfo);
            $this->outputJSON(false, '000000', '注册成功');
        }else{
            $this->outputJSON(true, '100000', $result['info']);
        }
    }

    /**
     * 用户登录操作
     * @author xy
     * @since 2017/09/27 14:56
     */
    public function do_login(){
        if($this->userInfo){
            $this->outputJSON(false, 'success', '已经登录');
        }
        $loginName = trim(I('login_name')); // 手机号或用户名
        $password = trim(I('password'));

        if (empty($loginName) || empty($password)){
            $this->outputJSON(true, 'false', '用户名或密码不能为空');
        }
        $paramArr = array(
            'login_name' => $loginName,
            'password' => $password,
            'timestamp' => time(),
        );
        $paramArr['sign'] = make_sign($paramArr, array('sign'));
        //指娱用户媒体站登录接口
        $url = C('URL.ZHIYU_URL').U('Api/UserCenter/user_login_media');
        $result = http($url, $paramArr, 'POST');
        $result = json_decode($result, true);

        if(isset($result['flag']) && $result['flag'] == 'success'){
            $userInfo = $result['data']['obj'];
            session('login_name', $loginName);
            session('media_web_user', $userInfo);
            $this->outputJSON(false, 'success', '登录成功');
        }else{
            $this->outputJSON(false, 'false',  $result['info']);
        }
    }

    /**
     * 用户登录页
     * @author xy
     * @since 2017/09/27 15:13
     */
    public function login(){
        if(get_user_info()){
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
        unset_user_login_info();
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
            $this->outputJSON(true, '100002', '请输入密码');
        }
        $userService = new UserService();
        if(!$userService->validatePassword($password)){
            $this->outputJSON(true, '100002', $userService->getFirstError());
        }
        $this->outputJSON(false, '000000', '验证通过');
    }
}