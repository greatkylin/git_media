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
            //若记住密码,保存用户名和加密后的密码到cookie
            if(intval(I('is_remember'))){
                cookie('login_name', $loginName, 7*86400);
                $password = strtoupper(md5($password));
                cookie(multiMD5($loginName), multiMD5(multiMD5($password)), 7*86400);
            }
            $this->outputJSON(false, 'success', '登录成功');
        }else{
            $this->outputJSON(true, 'false',  $result['info']);
        }
    }

    /**
     * 通过手机验证码重置密码
     * @author xy
     * @since 2017/10/15 14:32
     */
    public function reset_password(){
        if(IS_AJAX){
            $userService = new UserService();
            $phone = trim(I('post.phone'));
            $captcha = intval(I('post.captcha'));
            $newPassword = trim(I('post.password'));
            $result = $userService->changePasswordByValidBindPhone($phone, $captcha, $newPassword);
            if (!$result) {
                $this->outputJSON(true, 'false', $userService->getFirstError());
            }
            $this->outputJSON(false, 'success',  '密码修改成功');
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

    /**
     * 验证登录名是否为空
     * @author xy
     * @since 2017/10/14 17:09
     */
    public function ajax_check_login_name(){
        $loginName = trim(I('login_name'));
        if(empty($loginName)){
            $this->outputJSON(true, '100001', '请输入用户名');
        }
        $this->outputJSON(false, '000000', '验证通过');
    }
}