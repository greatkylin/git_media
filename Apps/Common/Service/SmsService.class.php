<?php
/**
 * 短信接口
 * User: Administrator
 * Date: 2017/9/29
 * Time: 14:03
 */

namespace Common\Service;


class SmsService extends BaseService
{

    //私有参数不可修改
// 	public $strReg = "101100-WEB-HUAX-716714";   //注册号(由华兴软通提供)
// 	public $strPwd = "dmfive123";                 //密码(由华兴软通提供)
// 	public $strSourceAdd = "";                   //子通道号，可为空（预留参数一般为空）
// 	public $strRegUrl = "http://www.stongnet.com/sdkhttp/reg.aspx";
// 	public $strBalanceUrl = "http://www.stongnet.com/sdkhttp/getbalance.aspx";
// 	public $strSmsUrl = "http://www.stongnet.com/sdkhttp/sendsms.aspx";
// 	public $strSchSmsUrl = "http://www.stongnet.com/sdkhttp/sendschsms.aspx";
// 	public $strStatusUrl = "http://www.stongnet.com/sdkhttp/getmtreport.aspx";
// 	public $strUpPwdUrl = "http://www.stongnet.com/sdkhttp/uptpwd.aspx";

    public function postSend($url, $param) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 验证手机验码
     * @param string $phone 手机号码
     * @param string $captcha 手机验证码
     * @param int $used 验证类型 0:验证码未使用掉 1:验证码使用掉
     * @param int $send_type 发送类型1:注册 0:找回密码, 2:邀请好友 3：sdk财务, 4：后台登录验证 5:手机快速注册登录 6：绑定手机验证 7：更换手机验证
     * @return array()
     * @author dw
     */
    public function mobileCaptchaGet($phone, $captcha, $used = 0, $send_type = -1) {
        // $redis = myclass('RedisExt', 'Redis');
        // $captch_res = $redis->get('mobile_captch'.$phone);

        $where = array();
        $where['phone'] = $phone;
        $where['code'] = $captcha;
        // $where['status'] = 0;//未验证
        //$where['create_time'] = array('egt', time() - 60 * 5); //期限时间大于等于当前时间
        if ($send_type != -1) {
            $where['type'] = $send_type;
        }

        $codes = M(C('DB_ZHIYU.DB_NAME') . '.' . 'code', C('DB_ZHIYU.DB_PREFIX'))
            ->field('phone,status,type,create_time,code')
            ->where($where)
            ->order('create_time desc')
            ->find();


        // echo M()->getLastSql();exit();

        if ($codes) {
            if ($codes['create_time'] < time() - 60 * 5 && $codes['status'] == 0) {
                $data['status'] = 10;
                $data['msg'] = '验证码已超时，请重新获取';
                return $data;
            }

            if ($codes['status'] == 1) {
                $data['status'] = 0;
                $data['msg'] = '验证码已失效!';
                return $data;
            }

            if ($used) {
                $data['status'] = 1; //验证码未使用过 0:待验证 1:使用过  2:待二次验证
            } else {
                $data['status'] = 2;
            }

            if ($codes['code'] != $captcha) {
                $data['status'] = 0;
                $data['msg'] = '输入的验证码不正确!';
                return $data;
            }

            M('code')->where($where)->save(array('status' => $data['status']));
            $data['msg'] = '验证成功!';
        } else {
            $data['status'] = 0;
            $data['msg'] = '验证失败!';
        }

        return $data;
    }

    /**
     * 发送验证码
     * @param string $phone 手机号码
     * @param int $type 发送类型 （1:注册 0:找回密码, 2:邀请好友 3：sdk财务, 4：后台登录验证 5:手机快速注册登录）
     * @return array
     */
    public function mobileCaptchaSet($phone, $type) {
        $ip = get_client_ip();

        //防刷机制
        $where = array();
        $where['phone'] = $phone;
        $where['type'] = $type;

        $codes = M(C('DB_ZHIYU.DB_NAME') . '.' . 'code', C('DB_ZHIYU.DB_PREFIX'))
            ->field('phone,create_time')
            ->where($where)
            ->order('create_time desc')
            ->find();

        if (time() - $codes['create_time'] < 90) {
            $data['status'] = 0;
            $data['msg'] = '不能频繁发送!';
            return $data;
        }

        $captcha = rand(500000, 999999);
        if ($type != 2) {
            $strContent = "验证码:" . $captcha . "如非本人操作,切勿将验证码告知他人,并请立即修改账号密码!【指娱游戏】";
        } else {
            $sys_config = get_config_info();
            $strContent = "验证码:" . $captcha . "如非本人操作,切勿将验证码告知他人!下载请戳：{$sys_config['app_url']} 【指娱游戏】";
        }

        #http://h.1069106.com:1210/services/msgsend.asmx/SendMsg?userCode=string&userPass=string&DesNo=string&Msg=string&Channel=1
        #$strSmsParam = "reg=".C("sms.Reg")."&pwd=".C("sms.Pwd")."&sourceadd=".C("sms.SourceAdd")."&phone=".$phone."&content=".$strContent;
        $strSmsParam = "userCode=" . C("sms.userCode") . "&userPass=" . C("sms.userPass") . "&DesNo=" . $phone . "&Msg=" . $strContent . "&Channel=0";
        $result_str = $this->postSend(C("sms.smsUrl"), $strSmsParam);

        $xmlResult = simplexml_load_string($result_str);
        $res = (int) $xmlResult;

        if ($res > 0) {
            //添加新记录
            $add = array();
            $add['code'] = $captcha;
            $add['ip'] = $ip;
            $add['type'] = $type;
            $add['phone'] = $phone;
            $add['create_time'] = time();
            $flag = M(C('DB_ZHIYU.DB_NAME') . '.' . 'code', C('DB_ZHIYU.DB_PREFIX'))->add($add);

            if ($flag) {
                $data['status'] = 1;
                $data['msg'] = '发送成功!';
            } else {
                $data['status'] = 0;
                $data['msg'] = '发送失败!';
            }
        } else {
            $data['status'] = 0;
            $data['msg'] = '发送失败!';
        }
        /* $result = array();
          $a = split('&', $result_str);
          foreach ($a as $v){
          $b = split('=', $v);
          $result[$b[0]] = $b[1];
          }

          if($result['result'] == 0){

          //添加新记录
          $add = array();
          $add['code'] = $captcha;
          $add['ip'] = $ip;
          $add['type'] = $type;
          $add['phone'] = $phone;
          $add['create_time'] = time();
          $flag = M(C('DB_ZHIYU.DB_NAME') . '.' . 'code', C('DB_ZHIYU.DB_PREFIX'))->add($add);

          if($flag){
          $data['status'] = 1;
          $data['msg'] = '发送成功!';

          }else{
          $data['status'] = 0;
          $data['msg'] = '发送失败!';
          }

          }else{
          $data['status'] = 0;
          $data['msg'] = '发送失败!';
          }
         */
        return $data;
    }
}