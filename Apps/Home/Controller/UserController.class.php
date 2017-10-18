<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29
 * Time: 11:11
 */

namespace Home\Controller;


use Common\Service\SmsService;
use Home\Service\AppService;
use Home\Service\GiftService;
use Home\Service\UserService;
use Think\UserPage;

class UserController extends HomeBaseController
{
    public function _initialize()
    {
        parent::_initialize();
        if(!$this->userInfo){
            if(IS_AJAX){
                $this->outputJSON(true, 'login', '请先登录');
            }else{
                $this->error('请先登录', '/');
            }
        }
        //判断是否已签到
        $signTime = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->where("uid='".$this->userInfo['uid']."'")->getField('signtime');
        $nowSignTime = strtotime(date('Y-m-d'));
        if($nowSignTime - $signTime < 0){
            $isSign = true;
            $this->assign('isSign', $isSign);
        }
    }

    /**
     * 个人中心首页
     * @author xy
     * @since 2017/09/29 17:38
     */
    public function index(){
        $userId = $this->getUserId();
        //1.获取账户余额
        $userService = new UserService();
        $money = $userService->getUserBalance($userId);
        if($money === false){
             $this->error($userService->getFirstError());
        }
        $money = round($money, 2);
        $money = explode('.', $money);
        //2.获取优惠券数量
        $couponNumInfo = $userService->countUserCouponInfo($userId);
        if($couponNumInfo === false){
            $this->error($userService->getFirstError());
        }
        //3.获取领取的礼包数量
        $giftService = new GiftService();
        $giftNum = $giftService->countUserGiftNum($userId);
        //4.我的游戏
        $appService = new AppService();
        $appList = $appService->getUserAppList($userId, 0, 6);
        if($appList === false){
            $this->error('获取我的游戏失败');
        }
        $this->assign('appList', $appList);
        $this->assign('money', $money);
        $this->assign('couponNumInfo', $couponNumInfo);
        $this->assign('giftNum', $giftNum);
        $this->display();
    }

    /**
     * 个人中心我的礼包页
     * @author xy
     * @since 2017/09/30
     */
    public function my_gift_list(){
        $userId = $this->getUserId();
        if(!$userId){
            if(IS_AJAX){
                $this->outputJSON(true, 'login', '请先登录');
            }else{
                $this->error('请先登录', '/');
            }
        }
        //当前页
        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage = 1;
        }
        $pageSize = 4;

        if(IS_AJAX){
            $service = new GiftService();
            // 查询满足要求的总记录数
            $totalNum = $service->countUserGiftNum($userId);
            if($totalNum === false){
                $this->outputJSON(true, 'false', $service->getFirstError());
            }
            // 进行分页游戏数据查询 注意limit方法的参数要使用Page类的属性
            $page = new UserPage($totalNum, $pageSize);
            $show = $page->show();
            $giftList = $service->getUserGiftListByPage($userId, $page->firstRow, $page->listRows);
            if($giftList === false){
                $this->outputJSON(true, 'false', $service->getFirstError());
            }
            if(empty($giftList)){
                $this->outputJSON(false, 'success', '您还没有领取礼包');
            }
            $data = array(
                'currentPage' => $currentPage,
                'pageSize' => $pageSize,
                'giftList' => $giftList,
                'show' => $show,
            );
            $this->outputJSON(false,'success','获取成功', $data);
        }
        $this->assign('currentPage', $currentPage);
        $this->assign('pageSize', $pageSize);
        $this->display();
    }

    /**
     * 个人中心我的优惠券
     * @author xy
     * @since 2017/09/30 11:13
     */
    public function my_coupon_list(){
        $userId = $this->getUserId();
        if(!$userId){
            if(IS_AJAX){
                $this->outputJSON(true, 'login', '请先登录');
            }else{
                $this->error('请先登录', '/');
            }
        }
        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage = 1;
        }
        $pageSize = 4;

        if(IS_AJAX){
            $service = new UserService();
            $totalNum = $service->countUserAllCouponNum($userId);
            if($totalNum === false){
                $this->outputJSON(true, 'false', $service->getFirstError());
            }
            $page = new UserPage($totalNum, $pageSize);
            $show = $page->show();
            $couponList = $service->getUserAllCouponByPage($userId, $page->firstRow, $page->listRows);
            if($couponList === false){
                $this->outputJSON(true, 'false', $service->getFirstError());
            }
            if(empty($couponList)){
                $this->outputJSON(false, 'success', '您还没有优惠券');
            }
            $data = array(
                'page' => $page,
                'pageSize' => $pageSize,
                'couponList' => $couponList,
                'show' => $show
            );
            $this->outputJSON(false,'success','获取成功', $data);
        }
        $this->assign('currentPage', $currentPage);
        $this->assign('pageSize', $pageSize);
        $this->display();

    }

    /**
     * 个人中心我的通知
     * @author xy
     * @since 2017/09/30 14:30
     */
    public function my_notice_list(){
        $userId = $this->getUserId();
        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage= 1;
        }
        $pageSize = 3;

        if(IS_AJAX){
            $where = array();
            $where['u_n.uid'] = $userId;
            $where['u_n.is_del'] = 0; //未被删除的

            $service = new UserService();
            $totalNum = $service->countNoticeNum($where);
            if($totalNum === false){
                $this->outputJSON(true, 'false', $service->getFirstError());
            }
            $page = new UserPage($totalNum, $pageSize);
            // 分页显示输出
            $show = $page->show();
            $noticeList = $service->getNoticeListByPage($where, $page->firstRow, $page->listRows);

            if($noticeList === false){
                $this->outputJSON(true, 'false', $service->getFirstError());
            }
            if(empty($noticeList)){
                $this->outputJSON(false, 'success', '暂时没有消息');
            }

            $data = array(
                'currentPage' => $currentPage,
                'pageSize' => $pageSize,
                'noticeList' => $noticeList,
                'show' => $show,
            );
            $this->outputJSON(false,'success','获取成功', $data);
        }
        $this->assign('currentPage', $currentPage);
        $this->assign('pageSize', $pageSize);
        $this->display();
    }

    /**
     * 我的消息标记为已读
     * @author xy
     * @since 2017/09/30 15:38
     */
    public function my_notice_read_by_id(){
        $notice_id = intval(I('notice_id'));
        if (!$notice_id) {
            $this->outputJSON(true, 'false', '参数不完整');
        }
        $userId = $this->getUserId();
        if(!$userId){
            if(IS_AJAX){
                $this->outputJSON(true, 'login', '请先登录');
            }else{
                $this->error('请先登录', '/');
            }
        }
        $where = array();
        $where['uid'] = $userId;
        $where['notice_id'] = $notice_id;
        $save['is_read'] = 1;
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user_notice', C('DB_ZHIYU.DB_PREFIX'))
            ->where($where)
            ->save($save);

        if ($result !== false) {
            $this->outputJSON(false,'success','已阅');
        } else {
            $this->outputJSON(true,'false','失败');
        }
    }

    /**
     * 单条删除我的消息通知
     * @author xy
     * @since 2017/09/30 15:28
     */
    public function my_notice_delete_by_id(){
        $notice_id = intval(I('notice_id'));
        if (!$notice_id) {
            $this->outputJSON(true, 'false', '参数不完整');
        }
        $userId = $this->getUserId();
        if(!$userId){
            if(IS_AJAX){
                $this->outputJSON(true, 'login', '请先登录');
            }else{
                $this->error('请先登录', '/');
            }
        }
        $where = array();
        $where['uid'] = $userId;
        $where['notice_id'] = $notice_id;
        $save['is_del'] = 1;
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user_notice', C('DB_ZHIYU.DB_PREFIX'))
            ->where($where)
            ->save($save);

        if ($result) {
            $this->outputJSON(false,'success','删除成功');
        } else {
            $this->outputJSON(true,'false','删除失败');
        }
    }

    /**
     * 我的游戏，包括下载过，收藏的，与预约的游戏
     * @author xy
     * @since 2017/10/10 13:52
     */
    public function my_app_list(){
        $userId = $this->getUserId();
        if(!$userId){
            if(IS_AJAX){
                $this->outputJSON(true, 'login', '请先登录');
            }else{
                $this->error('请先登录', '/');
            }
        }
        //当前页
        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage = 1;
        }
        $pageSize = 6;

        if(IS_AJAX){
            $appService = new AppService();
            $totalNum = $appService->countUserAppNum($userId);
            if($totalNum === false){
                $this->outputJSON(true, 'false', $appService->getFirstError());
            }
            $page = new UserPage($totalNum, $pageSize);
            // 分页显示输出
            $show = $page->show();
            $appList = $appService->getUserAppList($userId, $page->firstRow, $page->listRows);
            if($appList === false){
                $this->outputJSON(true, 'false', '查询失败');
            }
            $data = array(
                'currentPage' => $currentPage,
                'pageSize' => $pageSize,
                'app_list' => $appList,
                'show' => $show,
            );
            $this->outputJSON(false, 'success', '查询成功', $data);
        }

        $this->assign('currentPage', $currentPage);
        $this->assign('pageSize', $pageSize);
        $this->display();
    }

    /**
     * 激活码兑换
     * @author xy
     * @since 2017/09/30 15:50
     */
    public function exchange_code(){
        if(IS_POST){
            $url = U('Home/User/exchange_code');
            $code = trim(I('code', null));
            if(empty($code)){
                $this->outputJSON(true,'false','请填写激活码');
            }
            $service = new UserService();
            //执行兑换激活码操作
            if($service->exchangeCode($code)){
                $this->outputJSON(false,'false','兑换成功');
            }else{
                $this->outputJSON(true, 'success', $service->getFirstError());
            }
        }
        $this->display();

    }

    /**
     * 修改用户昵称
     * @author xy
     * @since 2017/09/29 17:22
     */
    public function change_nickname(){
        if(IS_AJAX){
            $userId = $this->getUserId();
            if(!$userId){
                $this->outputJSON(true, 'false',  '请先登录');
            }
            $nickname = trim(I('nickname'));
            if(empty($nickname)){
                $this->outputJSON(true, 'false',  '请输入名称');
            }
            $originalNickname = $this->getUserNickName();
            if($nickname == $originalNickname){
                $this->outputJSON(true, 'false',  '新名称与旧名称一致，无需修改');
            }

            $paramArr = array(
                'token' => $this->userInfo['token'],
                'login_name' => session('login_name'),
                'timestamp' => time(),
                'nickname' => $nickname,
            );
            $paramArr['sign'] = make_sign($paramArr, array('sign'));

            $url = C('URL.ZHIYU_URL').U('Api/Mycenter/modify_user');
            $result = http($url, $paramArr, 'POST');
            $result = json_decode($result, true);

            if(empty($result)){
                $this->outputJSON(true, 'false',  '未知错误');
            }
            if(isset($result['flag']) && $result['flag'] == 'success'){
                //更新session中的手机信息
                $this->userInfo['nickname'] = $nickname;
                session('media_web_user', $this->userInfo);
                $this->outputJSON(false, 'success', '修改成功');
            }else if(isset($result['flag']) && $result['flag'] == 'login'){
                unset_user_login_info();
                $this->outputJSON(true, 'false', $result['info']);
            }else{
                $this->outputJSON(true, 'false',  $result['info']);
            }

        }
        $this->display();
    }

    /**
     * 修改用户密码
     * @author xy
     * @since 2017/09/29 14:43
     */
    public function change_password(){
        if(IS_AJAX){
            $userId = $this->getUserId();
            if(empty($userId)){
                $this->outputJSON(true, 'false',  '请先登录');
            }
            $userService = new UserService();
            $userInfo = $userService->getUserByUserId($userId);
            if($userInfo === false){
                $this->outputJSON(true, 'false',  $userService->getFirstError());
            }
            $oldPassword = trim(I('post.old_password'));
            $newPassword = trim(I('post.new_password'));
            $newPassword2 = trim(I('post.new_password2'));
            $result = $userService->changePasswordByValidOldPass($userId, $oldPassword, $newPassword, $newPassword2);
            if (!$result) {
                $this->outputJSON(true, 'false', $userService->getFirstError());
            }
            $this->outputJSON(false, 'success',  '密码修改成功');
        }
        $this->display();
    }

    /**
     * 用户手机绑定
     * @author xy
     * @since 2017/09/29 16:14
     */
    public function bind_phone(){
        if(IS_AJAX){
            $phone = trim(I('phone'));
            $captcha = trim(I('captcha'));
            $userService = new UserService();
            $result = $userService->bindUserPhone($phone, $captcha);
            if(!$result){
                $this->outputJSON(true, 'false', $userService->getFirstError());
            }
            session('user_last_send_sms_time', 0);
            $this->outputJSON(false, 'success', '手机绑定成功');
        }
        //距离下一次可以发验证还剩余的时间
        $remainTime = 0;
        $lastSendTime = session('user_last_send_sms_time');
        if($lastSendTime){
            $remainTime = 60 - (time() - $lastSendTime);
            if($remainTime<0){
                $remainTime = 0;
            }
        }
        $this->assign('remainTime', $remainTime);
        $this->display();
    }

    /**
     * 每日签到
     * @author xy
     * @since 2017/10/09 16:17
     */
    public function ajax_daily_sign(){
        if(!$this->userInfo){
            $this->outputJSON(true, 'login', '请先登录');
        }
        $paramArr = array(
            'token' => $this->userInfo['token'],
            'login_name' => session('login_name'),
            'timestamp' => time(),
        );
        $paramArr['sign'] = make_sign($paramArr, array('sign'));
        $url = C('URL.ZHIYU_URL').U('Api/Mycenter/everyday_sign');
        $result = http($url, $paramArr, 'POST');
        $result = json_decode($result, true);

        if(empty($result)){
            $this->outputJSON(true, 'false',  '未知错误');
        }
        if(isset($result['flag']) && $result['flag'] == 'success'){
            //记录用户的签到的时间,并获取当月已签到时间以及签到天数
            $service = new UserService();
            $signInfoArr = $service->userDailySignLog();
            //var_dump($signInfoArr);die;
            $this->outputJSON(false, 'success', $result['info'], $signInfoArr);
        }else if(isset($result['flag']) && $result['flag'] == 'login'){
            unset_user_login_info();
            $this->outputJSON(true, 'false', $result['info']);
        }else{
            $this->outputJSON(true, 'false',  $result['info']);
        }
    }

    /**
     * 用户领取礼包
     * @author xy
     * @since 2017/10/10 10:59
     */
    public function receive_gift(){
        if(!$this->userInfo){
            $this->outputJSON(true, 'login', '请先登录');
        }
        //礼包id
        $giftId = intval(I('gift_id'));
        if(empty($giftId)){
            $this->outputJSON(true, 'false', '必填参数缺失');
        }
        $paramArr = array(
            'token' => $this->userInfo['token'],
            'login_name' => session('login_name'),
            'timestamp' => time(),
            'gift_id' => $giftId,
        );
        $paramArr['sign'] = make_sign($paramArr, array('sign'));
        $url = C('URL.ZHIYU_URL').U('Api/Gift/get_gift_media');
        $result = http($url, $paramArr, 'POST');
        $result = json_decode($result, true);
        //var_dump($result);die;
        if(empty($result)){
            $this->outputJSON(true, 'false',  '未知错误');
        }
        if(isset($result['flag']) && $result['flag'] == 'success'){
            $this->outputJSON(false, 'success', $result['data']['obj']['info'], $result['data']['obj']);
        }else if(isset($result['flag']) && $result['flag'] == 'login'){
            unset_user_login_info();
            $this->outputJSON(true, 'false', $result['info']);
        }else{
            $this->outputJSON(true, 'false',  $result['info']);
        }
    }

    /**
     * 修改头像页
     * @author xy
     * @since 2017/10/11 09:22
     */
    public function modify_avatar(){
        $this->display();
    }

    /**
     * ajax方式调用修改头像接口
     * @author xy
     * @since 2017/10/11 09:23
     */
    public function ajax_do_modify_avatar(){
        if(!$this->userInfo){
            $this->outputJSON(true, 'login', '请先登录');
        }
        $circleRadius = floatval(I('circle_r_scale'));
        if(empty($circleRadius)){
            $circleRadius = 0.5;
        }
        //图片先上传至本地
        $avatarPath = upload_user_avatar_temp($circleRadius);
        if(!$avatarPath){
            $this->outputJSON(true, 'false', '上传失败');
        }
        //php5.5以上推荐使用CURLFile上传文件，5.5以下可以使用
        if (class_exists('\CURLFile')) {
            $fileInfo =  new \CURLFile($avatarPath);
        } else {
            $fileInfo  = '@' . $avatarPath;
        }
        $paramArr = array(
            'token' => $this->userInfo['token'],
            'login_name' => session('login_name'),
            'timestamp' => time(),
        );
        $paramArr['sign'] = make_sign($paramArr, array('sign'));
        $paramArr['image']  = $fileInfo;

        $url = C('URL.ZHIYU_URL').U('Api/Mycenter/modify_head');
        $result = http($url, $paramArr, 'POST', array(), true);

        $result = json_decode($result, true);
        //上传到指娱后删除本地的图片
        if(is_file($avatarPath)){
            //unlink($avatarPath);
        }
        if(empty($result)){
            $this->outputJSON(true, 'false',  '未知错误');
        }
        if(isset($result['flag']) && $result['flag'] == 'success'){
            //更新session中保存的用户信息
            $this->userInfo['head'] = $result['data']['obj'];
            if(session('media_web_user')){
                session('media_web_user', $this->userInfo);
            }
            $this->outputJSON(false, 'success', $result['info'], array('head_url' => $result['data']['obj']));
        }else if(isset($result['flag']) && $result['flag'] == 'login'){
            unset_user_login_info();
            $this->outputJSON(true, 'false', $result['info']);
        }else{
            $this->outputJSON(true, 'false',  $result['info']);
        }
    }

    /**
     * 修改用户名
     * @author xy
     * @since 2017/10/11 10:51
     */
    public function change_username(){
        if(IS_AJAX){
            if(!$this->userInfo){
                $this->outputJSON(true, 'login', '请先登录');
            }
            $userName = trim(I('username'));
            $service = new UserService();
            if(!$service->validateUserName($userName)){
                $this->outputJSON(true, 'false', $service->getFirstError());
            }
            $paramArr = array(
                'token' => $this->userInfo['token'],
                'login_name' => session('login_name'),
                'timestamp' => time(),
                'username' => $userName,
            );
            $paramArr['sign'] = make_sign($paramArr, array('sign'));

            $url = C('URL.ZHIYU_URL').U('Api/Mycenter/bind_username');
            $result = http($url, $paramArr, 'POST');
            $result = json_decode($result, true);
            if(empty($result)){
                $this->outputJSON(true, 'false',  '未知错误');
            }
            if(isset($result['flag']) && $result['flag'] == 'success'){
                $this->outputJSON(false, 'success', $result['info']);
            }else if(isset($result['flag']) && $result['flag'] == 'login'){
                unset_user_login_info();
                $this->outputJSON(true, 'false', $result['info']);
            }else{
                $this->outputJSON(true, 'false',  $result['info']);
            }
        }
        //从数据库获取用户信息，用来判断修改用户名的表单是否可以修改
        $service = new UserService();
        $user = $service->getUserByUserId($this->userInfo['uid']);
        if(!$user){
            $this->error($service->getFirstError());
        }
        $this->assign('user', $user);
        $this->display();
    }
}