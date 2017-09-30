<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29
 * Time: 11:11
 */

namespace Home\Controller;


use Common\Service\SmsService;
use Home\Service\GiftService;
use Home\Service\UserService;

class UserController extends HomeBaseController
{
    public function _initialize()
    {
        parent::_initialize();
        if(!$this->userInfo){
            $this->error('请先登录', '/');
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
        //2.获取优惠券数量
        $couponNumInfo = $userService->countUserCouponInfo($userId);
        if($couponNumInfo === false){
            $this->error($userService->getFirstError());
        }
        //3.获取领取的礼包数量
        $giftService = new GiftService();
        $giftNum = $giftService->countUserGiftNum($userId);
        //4.我的游戏

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
            $this->error('请先登录');
        }
        //当前页
        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage = 1;
        }
        $pageSize = intval(I('page_size', 4));
        if(IS_AJAX){
            $service = new GiftService();
            // 查询满足要求的总记录数
            $totalNum = $service->countUserGiftNum($userId);
            if($totalNum === false){
                $this->outputJSON(true, '', $service->getFirstError());
            }
            // 进行分页游戏数据查询 注意limit方法的参数要使用Page类的属性
            $giftList = $service->getUserGiftListByPage($userId, $currentPage, $pageSize);
            if($giftList === false){
                $this->outputJSON(true, '', $service->getFirstError());
            }
            if(empty($giftList)){
                $this->outputJSON(false, '', '您还没有领取礼包');
            }
            $data = array(
                'currentPage' => $currentPage,
                'pageSize' => $pageSize,
                'giftList' => $giftList
            );
            $this->outputJSON(false,'','获取成功', $data);
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
            $this->error('请先登录');
        }
        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage = 1;
        }
        $pageSize = intval(I('page_size', 4));
        if(IS_AJAX){
            $service = new UserService();
            $couponList = $service->getUserAllCouponByPage($userId, $currentPage, $pageSize);
            if($couponList === false){
                $this->outputJSON(true, '', $service->getFirstError());
            }
            if(empty($couponList)){
                $this->outputJSON(false, '', '您还没有优惠券');
            }
            $data = array(
                'currentPage' => $currentPage,
                'pageSize' => $pageSize,
                'couponList' => $couponList
            );
            $this->outputJSON(false,'','获取成功', $data);
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
            $currentPage = 1;
        }
        $pageSize = intval(I('page_size', 4));
        if(IS_AJAX){
            $where = array();
            $where['u_n.uid'] = $userId;
            $where['u_n.is_del'] = 0; //未被删除的

            $service = new UserService();
            $noticeList = $service->getNoticeListByPage($where, $currentPage, $pageSize);

            if($noticeList === false){
                $this->outputJSON(true, '', $service->getFirstError());
            }
            if(empty($noticeList)){
                $this->outputJSON(false, '', '暂时没有消息');
            }

            $data = array(
                'currentPage' => $currentPage,
                'pageSize' => $pageSize,
                'couponList' => $noticeList
            );
            $this->outputJSON(false,'','获取成功', $data);
        }
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
            $this->outputJSON(true, '', '参数不完整');
        }
        $userId = $this->getUserId();
        if(!$userId){
            $this->outputJSON(true, '', '请先登录');
        }
        $where = array();
        $where['uid'] = $userId;
        $where['notice_id'] = $notice_id;
        $save['is_read'] = 1;
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user_notice', C('DB_ZHIYU.DB_PREFIX'))
            ->where($where)
            ->save($save);

        if ($result !== false) {
            $this->outputJSON(false,'','删除成功');
        } else {
            $this->outputJSON(true,'','删除失败');
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
            $this->outputJSON(true, '', '参数不完整');
        }
        $userId = $this->getUserId();
        if(!$userId){
            $this->outputJSON(true, '', '请先登录');
        }
        $where = array();
        $where['uid'] = $userId;
        $where['notice_id'] = $notice_id;
        $save['is_del'] = 1;
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user_notice', C('DB_ZHIYU.DB_PREFIX'))
            ->where($where)
            ->save($save);

        if ($result) {
            $this->outputJSON(false,'','删除成功');
        } else {
            $this->outputJSON(true,'','删除失败');
        }
    }

    public function my_app_list(){

    }

    /**
     * 激活码兑换
     * @author xy
     * @since 2017/09/30 15:50
     */
    public function exchange_code(){

    }

    /**
     * 修改用户昵称
     * @author xy
     * @since 2017/09/29 17:22
     */
    public function change_nickname(){
        if(IS_POST){
            $userId = $this->getUserId();
            if(!$userId){
                $this->error('请先登录');
            }
            $nickname = trim(I('nickname'));
            if(empty($nickname)){
                $this->error('请输入名称');
            }
            if($nickname = $this->userInfo['nickname']){
                $this->error('新名称与旧名称一致，无需修改');
            }
            $data = array(
                'nickname' => $userId,
            );
            $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->where(array('uid' => $userId))->save($data);
            if(!$result){
                $this->error('修改名称失败');
            }
            //更新session中的手机信息
            $user['nickname'] = $nickname;
            unset($user['password']);
            session('media_web_user', $user);
        }
        $this->display();
    }

    /**
     * 修改用户密码
     * @author xy
     * @since 2017/09/29 14:43
     */
    public function change_password(){
        if(IS_POST){
            //修改手机的方式， 1验证旧密码，2验证手机
            $type = intval(I('post.type', 0));
            $userId = $this->getUserId();
            if(empty($userId)){
                $this->error('请先登录');
            }
            $userService = new UserService();
            $userInfo = $userService->getUserByUserId($userId);
            if($userInfo === false){
                $this->error($userService->getFirstError());
            }
            if($type == 1){
                $oldPassword = trim(I('post.old_password'));
                $newPassword = trim(I('post.new_password'));
                $newPassword2 = trim(I('post.new_password2'));
                $result = $userService->changePasswordByValidOldPass($userId, $oldPassword, $newPassword, $newPassword2);
                if(!$result){
                    $this->error($userService->getFirstError());
                }
            }else if($type == 2){
                $phone = trim(I('post.phone'));
                $captcha = intval(I('post.captcha'));
                $newPassword = trim(I('post.password'));
                $result = $userService->changePasswordByValidBindPhone($userId, $phone, $captcha, $newPassword);
                if(!$result){
                    $this->error($userService->getFirstError());
                }
            }else{
                $this->error('请选择正确的身份验证的方式');
            }
            $this->success('密码修改成功');
        }
        //TODO 模板还没有做
        $this->display();

    }

    /**
     * 用户手机绑定
     * @author xy
     * @since 2017/09/29 16:14
     */
    public function bind_phone(){
        if(IS_POST){
            $phone = trim(I('phone'));
            $captcha = trim(I('captcha'));
            $userService = new UserService();
            $result = $userService->bindUserPhone($phone, $captcha);
            if(!$result){
                $this->error($userService->getFirstError());
            }
            $this->success('手机绑定成功');
        }
        $this->display();
    }
}