<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/27
 * Time: 9:31
 */

namespace Home\Service;


use Common\Service\BaseService;
use Common\Service\SmsService;

class UserService extends BaseService
{
    /**
     * 通过用户名或者用户手机获取用户信息
     * @author xy
     * @since 2017/09/27 09:34
     * @param string $usernameOrPhone 用户名或者手机
     * @return mixed
     */
    public function getUser ($usernameOrPhone) {
        $user = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->where("username = '{$usernameOrPhone}' OR phone = '{$usernameOrPhone}'")->find();
        if($user === false){
            return $this->setError('查询失败，未找到对应的用户');
        }
        return $user;
    }

    /**
     * 通过用户名获取用户信息
     * @author xy
     * @since 2017/09/27 09:35
     * @param string $username 用户名
     * @return mixed
     */
    public function getUserByUsername ($username) {
        $user = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->where("username = '{$username}'")->find();
        if($user === false){
            return $this->setError('查询失败，未找到对应的用户');
        }
        return $user;
    }

    /**
     * 通过用户手机获取用户信息
     * @author xy
     * @since 2017/09/27 09:35
     * @param string $phone 用户手机号码
     * @return mixed
     */
    public function getUserByPhone ($phone) {
        $user = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->where("phone = '{$phone}'")->find();
        if($user === false){
            return $this->setError('查询失败，未找到对应的用户');
        }
        return $user;
    }

    /**
     * 通过用户id 获取用户信息
     * @author xy
     * @since 2017/09/27 11:52
     * @param int $userId 用户id
     * @return bool|mixed
     */
    public function getUserByUserId($userId){
        $user = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->where("uid = '{$userId}'")->find();
        if($user === false){
            return $this->setError('查询失败，未找到对应的用户');
        }
        return $user;
    }

    /**
     * 验证用户名是否符合规则
     * @author xy
     * @since 2017/09/27/ 10:16
     * @param $userName
     * @return bool
     */
    public function validateUserName($userName){
        if(empty($userName)){
            return $this->setError('请输入用户名');
        }
        //用户名长度3-20字符，只允许英文、数字、下划线以及英文句号
        //$pattern = '/^[a-zA-Z0-9\_\.]{3,20}$/';
        $pattern = '/^[\w_.]{3,20}$/';
        if (!preg_match($pattern, $userName)){
            return $this->setError('用户名不符合规则');
        }
        $pattern = '/^\\d{11}$/';
        if (preg_match($pattern, $userName)){
            return $this->setError('用户名不能为11位的数字');
        }
        return true;
    }

    /**
     * 验证密码是否符合规制
     * @author xy
     * @since 2017/09/27 11:03
     * @param $password
     * @return bool
     */
    public function validatePassword($password){
        if(empty($password)){
            return $this->setError('请输入密码');
        }
        $pattern = '/^[a-zA-Z0-9\_\.\!\@\#\$\%\^\&\*\(\)\~\-\`\:\;\+\=\/\\\{\}\[\]]{6,16}$/';
        if (!preg_match($pattern, $password)){
            return $this->setError('密码不符合规则');
        }
        return true;
    }

    /**
     * 根据提交的信息注册用户
     * @author xy
     * @since 2017/09/27 11:34
     * @param array $userInfo 用户注册的信息
     * @return boolean
     */
    public function registerUser(array $userInfo){
        if(empty($userInfo)){
            return $this->setError('参数信息缺失，无法完成注册');
        }
        //添加注册信息
        $userId = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->add($userInfo);
        if(empty($userId)){
            return $this->setError('注册失败');
        }
        //生成用户昵称
        $sys_config = get_config_info();
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->where(array('uid' => $userId))->save(array('nickname' => $sys_config['nickname_prefix'] . $userId));
        if($result === false){
            return $this->setError('生成用户昵称失败');
        }
        return $userId;
    }

    /**
     * 用户登录日志
     * @param  array $user 用户数组
     * @return void
     */
    public function userLoginLog($user = array()) {
        $data = array(
            'uid' => $user['uid'],
            'udid' => $user['udid'],
            'channel_id' => $user['channel_id'],
            'ip_info' => $user['ip_info'],
            'ip_address' => $user['ip_address'],
            'login_time' => time(),
        );
        if($user['province_code']) {
            $data['province_code'] = $user['province_code'];
        }
        if ($user['city_code'])
            $data['city_code'] = $user['city_code'];

        M(C('DB_ZHIYU.DB_NAME') . '.' . 'user_loginlog', C('DB_ZHIYU.DB_PREFIX'))->add($data);
    }

    /**
     * 登录后的数据处理,添加用户登录记录，保存最后登录信息
     * @param  array $user 用户数组
     * @param  int $isReg 是否注册
     * @return boolean
     */
    public function afterLogin ($user, $isReg = 0) {
        $user['head'] = format_url($user['head']);

        // 获取用户ip信息
        $ip_info = get_ip_data();
        if ($ip_info) {
            $user['ip_info'] = $ip_info['ip'];
            $user['ip_address'] = $ip_info['region'].' '.$ip_info['city'];
            //$user['city_code'] = $ip_info['region'].' '.$ip_info['city'];
        }
        if ($isReg) {
            $user['province_code'] = $ip_info['region_id'];
            $user['city_code'] = $ip_info['city_id'];
        }

        // 记录登录日志
        $this->userLoginlog($user);
        $save = array(
            'last_login' => time(),
            'token' => $user['token'],
            'ip_info' => $user['ip_info'],
            'ip_address' => $user['ip_address'],
        );
        if($isReg) {
            $save['province_code'] = $user['province_code'];
            $save['city_code'] = $user['city_code'];
        }
        $flag = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->where(array('uid' => $user['uid']))->save($save);//记录登陆时间
        if($flag === false){
            return $this->setError('记录用户登录时间失败');
        }
    }

    /**
     * 计算用户的优惠券的数量信息
     * @author xy
     * @since 2017/09/27 14:06
     * @param $userId
     * @return bool
     */
    public function countUserCouponInfo($userId){
        if(empty($userId)){
            return $this->setError('必填参数缺失');
        }
        // 优惠券信息,
        $where = array(
            'uid' => $userId,
            'status' => 0,
            'create_time' => array('lt',time()),
            'active_time' => array('gt',time())
        );
        //可用的优惠券数量
        $use_coupon = M(C('DB_ZHIYU.DB_NAME') . '.' . 'coupon_user', C('DB_ZHIYU.DB_PREFIX'))->where($where)->count();
        if($use_coupon === false){
            return $this->setError('计算优惠券可用数量失败');
        }
        //总的优惠券数量
        $all_coupon = M(C('DB_ZHIYU.DB_NAME') . '.' . 'coupon_user', C('DB_ZHIYU.DB_PREFIX'))->where(array('uid' => $userId, 'is_del' => array('neq',1)))->count();
        if($all_coupon === false){
            return $this->setError('计算优惠券总数量失败');
        }

        $couponInfo['use_coupon'] = $use_coupon;
        $couponInfo['all_coupon'] = $all_coupon;

        return $couponInfo;
    }

    /**
     * 判断用户今天是否已经签到
     * @author xy
     * @since 2017/09/27 14:21
     * @param $userId
     * @return bool
     */
    public function isUserSign($userId){
        // 签到
        $todayBegin = strtotime(date('Y-m-d') . " 00:00:00");
        $todayEnd = strtotime(date('Y-m-d') . " 23:59:59");
        $checkSign = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->where("uid = {$userId} AND signtime < {$todayEnd} AND signtime > {$todayBegin}")->find();
        return $isSign = empty($checkSign) ? false : true;
    }

    /**
     * 验证账号是否被惩罚停封
     * @param array $user 用户信息
     * @param int $channelId 渠道id
     * @param int $sdkChannelCode sdk渠道码
     * @param int $gameId 游戏id
     * @return bool
     */
    public function checkPunish (array $user, $channelId, $sdkChannelCode, $gameId) {
        $punishUdid = array('D8FC62CA766831D7DB887C5513BC790B','01B77B2E807210FD25044BC6DAF7DAEB');
        if ( in_array($user['udid'], $punishUdid)) {
            return $this->setError('账号停封中');
        }
        if (($sdkChannelCode && $gameId) || $user['sdk_channel_code']) {    // sdk登录
            // 判断sdk渠道封停   封包和用户渠道
            $where = [
                'object_id' => ['IN', "{$sdkChannelCode},{$user['sdk_channel_code']}"],
                'channel_type' => 1,// sdK渠道
                'type' => 2,        // 渠道封停
                'stop_type' => 3,   // 账号封停
                'status' => ['neq', 2],
                'execute_time' => array('lt',time()),
                'end_time' => array('gt',time()),
            ];

            $punish = M(C('DB_ZHIYU.DB_NAME') . '.' . 'punish', C('DB_ZHIYU.DB_PREFIX'))->where($where)->find();
            if ($punish) {
                return $this->setError('该渠道封停中');
            }
        } else {    // APP登录
            $where = [
                'object_id' => $channelId,
                'channel_type' => 2,// APP渠道
                'type' => 2,        // 渠道封停
                'stop_type' => 3,   // 账号封停
                'status' => ['neq', 2],
                'execute_time' => array('lt',time()),
                'end_time' => array('gt',time()),
            ];
            $punish = M(C('DB_ZHIYU.DB_NAME') . '.' . 'punish', C('DB_ZHIYU.DB_PREFIX'))->where($where)->find();
            if ($punish) {
                return $this->setError('该渠道封停中');
            }
        }
        // 判断账号封停
        $where = [
            'object_id' => $user['uid'],
            'type' => 1,        // 账号封停
            'stop_type' => 3,   // 账号封停
            'status' => ['neq', 2],
            'execute_time' => array('lt',time()),
            'end_time' => array('gt',time()),
        ];
        $punish = M(C('DB_ZHIYU.DB_NAME') . '.' . 'punish', C('DB_ZHIYU.DB_PREFIX'))->where($where)->find();
        if ($punish) {
            return $this->setError('该账号封停中');
        }
        return true;
    }

    /**
     * 用户登录逻辑
     * @author xy
     * @since 2017/09/29 16:26
     * @param $username
     * @param $password
     * @return bool|mixed
     */
    public function login($username, $password) {
        $user = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))->where("username = '{$username}' OR phone = '{$username}'")->find();
        if (!$user){
            return $this->setError('用户不存在');
        }
        if ($user['password']!= multiMD5($password)){
            return $this->setError('用户名或密码错误');
        }
        if($user['status'] == 1){//用户被锁定
            if (time() >= $user['allow_login_time']){ //封号时间已过
                $user['status'] = 0;
                $flag = M(M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX')))->where(array('username'=>$username))->save(array('status' => 0));
                if (!$flag) {
                    return $this->setError('用户解锁失败');
                }
            }else{
                return $this->setError('用户被锁定!截止时间到:'.date("Y-m-d H:i:s",$user['allow_login_time']));
            }
        }

        return $user;
    }

    /**
     * 通过验证旧密码来设置新密码
     * @author xy
     * @since 2017/09/29 16:33
     * @param int $userId 用户id
     * @param string $oldPassword 旧密码
     * @param string $newPassword 新密码
     * @param string $newPassword2 确认新密码
     * @return bool
     */
    public function changePasswordByValidOldPass($userId, $oldPassword, $newPassword, $newPassword2){
        if(empty($userId)){
            return $this->setError('请先登录');
        }
        if(empty($oldPassword)||empty($newPassword)||empty($newPassword2)){
            return $this->setError('新旧密码不能为空');
        }
        $userInfo = $this->getUserByUserId($userId);
        if(empty($userInfo)){
            return $this->setError('用户不存在');
        }
        if($userInfo['password'] != multiMD5(strtoupper(md5($oldPassword)))){
            return $this->setError('输入的旧密码不正确');
        }
        if($newPassword != $newPassword2){
            return $this->setError('输入的两次输入的新密码不一致');
        }
        if($oldPassword == $newPassword){
            return $this->setError('新密码与旧密码重复，无需修改');
        }
        $data = array(
            'update_time' => time(),
            'password' => multiMD5(strtoupper(md5($newPassword))),
        );
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))
            ->where(array('uid' => $userId))
            ->save($data);
        if(!$result){
            return $this->setError('密码修改失败');
        }
        return true;
    }

    /**
     * 通过验证手机号码来修改账号密码
     * @author xy
     * @since 2017/09/28 16:59
     * @param int $userId 用户id
     * @param string $phone 手机号码
     * @param string $captcha 手机验证码
     * @param string $newPassword 新密码
     * @return bool
     */
    public function changePasswordByValidBindPhone($phone, $captcha, $newPassword){
        if(empty($phone) || empty($captcha) || empty($newPassword)){
            return $this->setError('请填写手机验证码或新密码');
        }
        if(!isPhone($phone)){
            return $this->setError('手机号码格式不正确');
        }
        $userInfo = $this->getUser($phone);
        if(empty($userInfo)){
            return $this->setError('用户不存在');
        }
        if(empty($userInfo['phone'])){
            return $this->setError('用户未绑定手机号码');
        }
        if($userInfo['phone'] != $phone){
            return $this->setError('手机号码与用户绑定手机号码不一致');
        }
        //验证发送的手机验证码
        $smsService = new SmsService();
        $result = $smsService->mobileCaptchaGet($phone, $captcha, 0, 0);
        if ($result['status'] != 1) {
            return $this->setError($result['msg']);
        }
        $data = array(
            'update_time' => time(),
            'password' => multiMD5(strtoupper(md5($newPassword))),
        );
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))
            ->where(array('phone' => $phone))
            ->save($data);
        if(!$result){
            return $this->setError('密码重置失败');
        }
        return true;

    }

    /**
     * 通过验证手机号码来重置账号密码
     * @author xy
     * @since 2017/09/29 16:46
     * @param string $phone 手机号码
     * @param string $captcha 手机验证码
     * @param string $newPassword 新密码
     * @return bool
     */
    public function resetPasswordByValidPhone($phone, $captcha, $newPassword){
        if(empty($phone) || empty($captcha) || empty($newPassword)){
            return $this->setError('请填写手机验证码或新密码');
        }
        if(!isPhone($phone)){
            return $this->setError('手机号码格式不正确');
        }
        $userInfo = $this->getUserByPhone($phone);
        if(!$userInfo){
            return $this->setError('此手机号码未绑定用户');
        }
        //验证输入的手机验证码是否与发送的一致
        $smsService = new SmsService();
        $result = $smsService->mobileCaptchaGet($phone, $captcha, 1, 0);
        if ($result['status'] != 1) {
            return $this->setError($result['msg']);
        }
        $data = array(
            'update_time' => time(),
            'password' => multiMD5(strtoupper(md5($newPassword))),
        );
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user', C('DB_ZHIYU.DB_PREFIX'))
            ->where(array('uid' => $userInfo['uid']))
            ->save($data);
        if(!$result){
            return $this->setError('密码重置失败');
        }
        return true;
    }

    /**
     * 用户绑定手机
     * @author xy
     * @since 2017/09/29 17:03
     * @param string $phone 手机号码
     * @param string $captcha 验证码
     * @return bool
     */
     public function bindUserPhone($phone, $captcha){
         if(empty($phone) || empty($captcha)){
             return $this->setError('请填写填写手机或者验证码');
         }
         if(!isPhone($phone)){
             return$this->setError('手机号码格式不正确');
         }
         // 查看手机是否被绑定
         $user = $this->getUserByPhone($phone);
         if($user) {
             return $this->setError('该手机号码已被绑定');
         }
         $loginUserInfo = get_user_info();
         if(!$loginUserInfo){
             return $this->setError('请先登录');
         }
         $paramArr = array(
             'phone' => $phone,
             'captcha' => $captcha,
             'token' => $loginUserInfo['token'],
             'login_name' => session('login_name'),
             'timestamp' => time(),
         );
         $paramArr['sign'] = make_sign($paramArr, array('sign'));
         $url = C('URL.ZHIYU_URL').U('Api/Mycenter/bind_phone_media');
         $result = http($url, $paramArr, 'POST');
         $result = json_decode($result, true);
         if(empty($result)){
             $this->outputJSON(true, 'false',  '未知错误');
         }
         if(isset($result['flag']) && $result['flag'] == 'success'){
             //更新session中的手机信息
             $userInfo = get_user_info();
             $userInfo['phone'] = $phone;
             session('media_web_user', $userInfo);
             return true;
         }elseif (isset($result['flag']) && $result['flag'] == 'login'){
             unset_user_login_info();
             return $this->setError($result['info']);
         }else{
             return $this->setError($result['info']);
         }
     }

    /**
     * 发送短信验证码
     * @author xy
     * @since 2017/09/29 17:30
     * @param string $phone 手机号码
     * @param int $sendType 发送类型
     * @return bool
     */
     public function sendSmsCaptcha($phone, $sendType){
         if(empty($phone) || !isset($sendType)){
             return $this->setError( '必填参数缺失');
         }
         if(!isPhone($phone)){
             return $this->setError( '手机号码格式不正确');
         }
         $user = $this->getUserByPhone($phone);
         if(($sendType == 1 || $sendType == 5) && $user){
             return $this->setError( '该手机已被注册');
         }
         if ($sendType == 0) {//找回密码
             if (!$user) {
                 return $this->setError('用户未注册');
             }
         }
         if ($sendType == 2) { # 邀请好友(分享礼券)
             if ($user) {
                 return $this->setError('用户已注册');
             }
         }
         if ($sendType == 6) {//绑定手机验证
             if ($user) {
                 return $this->setError('您输入的手机号已被绑定');
             }
         }
         //发送短信验证码
         $smsService = new SmsService();
         $result = $smsService->mobileCaptchaSet($phone, $sendType);
         if ($result['status']) {
             return true;
         } else {
             return $this->setError($result['msg']);
         }
     }

    /**
     * 根据用户id获取用户账户余额
     * @author xy
     * @since 2017/09/29
     * @param int $userId 用户id
     * @return bool|int
     */
     public function getUserBalance($userId){
         if(empty($userId)){
             return $this->setError('用户id缺失');
         }
         $where['uid'] = $userId;

         $balance = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user_income', C('DB_ZHIYU.DB_PREFIX'))
             ->field('income_id,cur_money')
             ->where($where)
             ->order('income_id desc')
             ->find();
         if($balance === false){
             return $this->setError('查询用户账户余额失败');
         }
         if(empty($balance)){
             return 0;
         }
         return $balance['cur_money'];
     }

    /**
     * 计算用户优惠券的总数
     * @author xy
     * @since 2017/09/30 11:19
     * @param int $userId 用户id
     * @return bool|int
     */
     public function countUserAllCouponNum($userId){
         //总的优惠券数量
         $allCoupon = M(C('DB_ZHIYU.DB_NAME') . '.' . 'coupon_user', C('DB_ZHIYU.DB_PREFIX'))
             ->where(array('uid' => $userId, 'is_del' => array('neq',1)))
             ->count();
         if($allCoupon === false){
             return $this->setError('计算优惠券总数量失败');
         }
         return $allCoupon;
     }

    /**
     * 获取用户的优惠券
     * @author xy
     * @since 2017/09/30 13:44
     * @param int $userId 用户id
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @return bool|array
     */
     public function getUserAllCouponByPage($userId, $currentPage, $pageSize){
         $where = array();
         $where['u.uid'] = $userId;
         $coupon_list = $my_list = M(C('DB_ZHIYU.DB_NAME') . '.' . 'coupon_user', C('DB_ZHIYU.DB_PREFIX'))
             ->alias('u')
             ->field(
                 'c.money, c.title, c.relation_app_id, c.discount, c.full_money, c.less_money, 
                 u.remark AS remarks, c.coupon_type,c.coupon_id, c.coupon_scope, u.create_time, 
                 u.active_time, u.status, u.id'
             )
             ->join(C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') .  'coupon c on c.coupon_id = u.coupon_id', 'left')
             ->where($where)
             ->order('u.id DESC')
             ->select();
         if($coupon_list === false){
             return $this->setError('查询优惠券失败');
         }
         if(!empty($coupon_list)){
             $sort = array();
             foreach ($coupon_list as $k => $v) {
                 if ($v['coupon_scope'] == 0) {# 通用游戏
                     $coupon_list[$k]['zd_type'] = 0;
                     $coupon_list[$k]['zd_text'] = '适用于所有游戏';
                 } elseif ($v['coupon_scope'] == 1) {# 指定
                     $coupon_list[$k]['zd_type'] = 1;
                     $coupon_list[$k]['zd_text'] = '仅限某些游戏可用';
                     $remarks = '';
                     if(!empty($v['relation_app_id'])) {
                         $app_list = M(C('DB_ZHIYU.DB_NAME') . '.' . 'app_lib', C('DB_ZHIYU.DB_PREFIX'))
                             ->field('app_name')
                             ->where("app_id in({$v['relation_app_id']})")
                             ->select();
                         if(!empty($app_list)) {
                             foreach ($app_list as $al_key => $al_value) {
                                 $remarks .= $al_value['app_name'].',';
                             }
                             $remarks = substr($remarks, 0, -1);
                         }
                     }
                     $coupon_list[$k]['remarks'] = $remarks;
                 } else {    # 限制
                     $coupon_list[$k]['zd_type'] = 2;
                     $coupon_list[$k]['zd_text'] = '仅限某些游戏不可用';
                     $remarks = '';
                     if(!empty($v['relation_app_id'])) {
                         $app_list = M(C('DB_ZHIYU.DB_NAME') . '.' . 'app_lib', C('DB_ZHIYU.DB_PREFIX'))
                             ->field('app_name')
                             ->where("app_id in({$v['relation_app_id']})")
                             ->select();
                         if(!empty($app_list)) {
                             foreach ($app_list as $al_key => $al_value) {
                                 $remarks .= $al_value['app_name'].',';
                             }
                             $remarks = substr($remarks, 0, -1);
                         }
                     }
                     $coupon_list[$k]['remarks'] = $remarks;
                 }
                 $coupon_list[$k]['coupon_type'] = empty($v['coupon_type']) ? 0 : $v['coupon_type'];
                 if($coupon_list[$k]['coupon_type'] == 0){
                     $coupon_list[$k]['discount_text'] = explode('.',round(($v['discount']/10), 1));
                 }
                 //未使用的优惠券，到期时间小于当前时间 则设置为过期
                 if($v['status'] == 0 && $v['active_time'] < strtotime(date('Y-m-d'))) {
                     $coupon_list[$k]['status'] = 2;
                 }
                 else {
                     $coupon_list[$k]['status'] = empty($v['status']) ? 0 : $v['status'];
                 }
                 $coupon_list[$k]['coupon_scope'] = empty($v['coupon_scope']) ? 0 : $v['coupon_scope'];
                 $coupon_list[$k]['create_time'] = date('Y.m.d', $v['create_time']);
                 $coupon_list[$k]['active_time'] = date('Y.m.d', $v['active_time']);
                 unset($coupon_list[$k]['relation_app_id']);
                 $sort[$k]=$coupon_list[$k]['status'];
             }
             array_multisort($sort, SORT_ASC, $coupon_list);
             $coupon_list = array_slice($coupon_list, ($currentPage - 1) * $pageSize, $pageSize);
         }
         return $coupon_list;
     }

    /**
     * 计算通知消息的数量
     * @author xy
     * @since 2017/09/30 15:02
     * @param array $where 查询条件
     * @return bool|int
     */
     public function countNoticeNum(array $where = array()){
         if(!is_array($where)){
             return $this->setError('参数类型错误');
         }
         $count = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user_notice', C('DB_ZHIYU.DB_PREFIX'))->alias('u_n')
             ->field('u_n.is_read, n.notice_type, n.title, n.content')
             ->join(C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') .   'notice as n on u_n.notice_id = n.notice_id')
             ->where($where)
             ->count();
         if($count === false){
             return $this->setError('获取通知消息数量失败');
         }
         return $count;
     }

    /**
     * 获取通知消息列表
     * @author xy
     * @since 2017/09/30 15:09
     * @param array $where 查询条件
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @return bool|array
     */
     public function getNoticeListByPage(array $where = array(), $currentPage, $pageSize){
         if(!is_array($where)){
             return $this->setError('参数类型错误');
         }

         $noticeList = M(C('DB_ZHIYU.DB_NAME') . '.' . 'user_notice', C('DB_ZHIYU.DB_PREFIX'))->alias('u_n')
             ->field(
                 'u_n.notice_id, u_n.is_read, n.notice_type, n.parent_type, u_n.create_time, n.title, 
                n.content, n.level1, n.level2, n.level3, n.relation_param, n.extend_param'
             )
             ->join(C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') .  'notice as n on u_n.notice_id = n.notice_id')
             ->where($where)
             ->limit($currentPage, $pageSize)
             ->order('u_n.create_time DESC')
             ->select();

         if($noticeList === false){
             return $this->setError('查询用户通知消息失败');
         }

         if(!empty($noticeList)){
             foreach ($noticeList as $k => $v) {
                 //通知消息父类型
                 $noticeList[$k]['type'] = $v['notice_type'];
                 if(!empty($v['relation_param'])) {
                     $noticeList[$k]['relation_param'] = json_decode($v['relation_param'], true);
                 }
                 else {
                     $noticeList[$k]['relation_param'] = (object)array();
                 }
                 // 1:系统消息，2：指娱福利菌
                 switch ($v['parent_type']) {
                     case 1:
                         $noticeList[$k]['type_name'] = '【系统消息】';
                         break;
                     case 2:
                         $noticeList[$k]['type_name'] = '【指娱福利菌】';
                         break;
                     default :
                         $noticeList[$k]['type_name'] = '【默认消息】';
                         break;
                 }
                 unset($noticeList[$k]['parent_type']);
                 unset($noticeList[$k]['notice_type']);
                 if(!empty($v['extend_param'])) {
                     $noticeList[$k]['extend_param'] = json_decode($v['extend_param'], true);
                 }
                 else {
                     $noticeList[$k]['extend_param'] = (object)array();
                 }
                 $noticeList[$k]['create_time'] = date('Y.m.d', $v['create_time']);
             }
         }
         return $noticeList;
     }

    /**
     * 激活码兑换
     * @author xy
     * @since 2017/010/09 16:05
     * @param string $code 激活码
     * @return bool
     */
     public function exchangeCode($code){
         if(empty($code)){
             return $this->setError('请填写填写激活码');
         }
         $loginUserInfo = get_user_info();
         if(!$loginUserInfo){
             return $this->setError('请先登录');
         }
         $paramArr = array(
             'code' => $code,
             'token' => $loginUserInfo['token'],
             'login_name' => session('login_name'),
             'timestamp' => time(),
         );
         $paramArr['sign'] = make_sign($paramArr, array('sign'));
         $url = C('URL.ZHIYU_URL').U('Api/Activationcode/exchange_code');
         $result = http($url, $paramArr, 'POST');
         $result = json_decode($result, true);
         if(empty($result)){
             $this->outputJSON(true, 'false',  '未知错误');
         }
         if(isset($result['flag']) && $result['flag'] == 'success'){
             return true;
         }elseif(isset($result['flag']) && $result['flag'] == 'login'){
             unset_user_login_info();
             return $this->setError($result['info']);
         }else{
             return $this->setError($result['info']);
         }
     }

    /**
     * 用户签到的记录,以及连续签到天数
     * @author xy
     * @since 2017/10/09 17:18
     */
     public function userDailySignLog(){
         $loginUserInfo = get_user_info();
         $currentTime = time();
         //连续签到次数
         $signNum = $this->getUserConstantDailySignNum();
         $signNum = $signNum + 1;
         $data = array(
             'uid' =>  $loginUserInfo['uid'],
             'sign_time' => $currentTime,
             'num' => $signNum,
         );
         M('user_daily_sign')->add($data);
         $signDayInfo = M('user_daily_sign')->where(array('uid'=>$loginUserInfo['uid']))->select();
         $signDayArr = array();

         if(!empty($signDayInfo)){
             foreach ($signDayInfo as $key=>$value){
                 $signDay = date('j', $value['sign_time']);
                 $signDayArr[] = $signDay;
             }
         }
         $signDayArr = array_unique($signDayArr);
         $weekArr = array(
             0 => '日',1 => '一',2 => '二',3 => '三',4 => '四',5 => '五',6 => '六',
         );
         $currentMonthCalendarArr = current_month_calendar_array();
         $newMonthCalendarArr = array();
         foreach ($currentMonthCalendarArr as $k_week_num => $v_week){
             $weekIntArr = array_keys($v_week);
             $tempWeekDateInfo = array();
             foreach ($v_week as $week => $day){
                 foreach ($weekArr as $k_w => $v_w){
                     if(in_array($k_w, $weekIntArr)){
                         $tempWeekDateInfo[$week]['day'] = $day;
                         $tempWeekDateInfo[$week]['week'] = $weekArr[$week];
                         if(in_array($day, $signDayArr)){
                             $tempWeekDateInfo[$week]['is_sign'] = 1;
                         }else{
                             $tempWeekDateInfo[$week]['is_sign'] = 0;
                         }
                     }else{
                         $tempWeekDateInfo[$k_w]['day'] = '';
                         $tempWeekDateInfo[$k_w]['week'] = $weekArr[$k_w];
                         $tempWeekDateInfo[$k_w]['is_sign'] = 0;
                     }
                 }
             }
             ksort($tempWeekDateInfo);
             $newMonthCalendarArr[$k_week_num] = $tempWeekDateInfo;
         }
         $returnInfo = array(
             'today' => array(
                 'year' => date('Y'),
                 'month' => date('m'),
                 'day' => date('d'),
             ),
             'sign_num' => $signNum,
             'sign_days' => $signDayArr,
             'current_month' => $newMonthCalendarArr,
         );
         return $returnInfo;
     }

    /**
     * 获取用的连续签到次数
     * @author xy
     * @since 2017/10/09 18:06
     * @return mixed
     */
     public function getUserConstantDailySignNum(){
         $beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
         $endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
         $where = array(
             'sign_time' => array(array('egt', $beginYesterday), array('lt', $endYesterday), 'AND'),
         );
         //连续签到次数
         $signNum =  M('user_daily_sign')
             ->where($where)
             ->getField('num');
         return $signNum;
     }
}