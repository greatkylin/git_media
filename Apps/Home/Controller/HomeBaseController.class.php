<?php
namespace Home\Controller;
use Common\Service\SmsService;
use Endroid\QrCode\QrCode;
use Home\Service\UserService;
use Think\Controller;
class HomeBaseController extends Controller {

    protected $userInfo;

    public function _initialize(){
        session_start();
        //获取网站的友情链接
        $this->getFriendLink(10);
        //获取网站默认设置信息，seo关键字，网站名称，备案信息等
        $this->getDefaultSiteInfo();
        //获取推荐的热词
        $this->getRecommendSearchKeyword(5);
        //获取用户信息
        $this->userInfo = get_user_info();
        $this->assign('userInfo', $this->userInfo);
    }

    /**
     * 获取用户的id
     * @author xy
     * @since 2017/09/29 11:25
     * @return bool
     */
    protected function getUserId(){
        return empty($this->userInfo) ? false : $this->userInfo['uid'];
    }

    /**
     * 获取用户的昵称
     * @author xy
     * @since 2017/10/11 11:29
     * @return bool
     */
    protected function getUserNickName(){
        $userId = $this->getUserId();
        if(!$userId){
            return false;
        }
        $service = new UserService();
        $userInfo = $service->getUserByUserId($userId);
        return empty($userInfo) ? false : $userInfo['nickname'];
    }

    /**
     * 获取友情链接
     * @author xy
     * @since 2017/09/22 15:40
     * @param int $limit
     */
    protected function getFriendLink($limit = 10){
        $where = array(
            'is_show' => 1,
        );
        $friendLink = M('links')->where($where)->limit($limit)->select();
        if($friendLink === false){
            $this->error('获取友情链接失败');
        }
        $this->assign('friendLink', $friendLink);
    }

    /**
     * 获取网站默认设置信息，seo关键字，网站名称，备案信息等
     * @author xy
     * @since 2017/09/22 15:42
     */
    protected function getDefaultSiteInfo(){
        $siteConfigKeywordArr = array(
            'SITE_NAME',        //网站名称
            'IPC_INFO',         //IPC备案信息
            'SITE_SEO_TITLE',   //网站默认seo标题
            'SITE_SEO_KEYWORD', //网站默认关键字
            'SITE_SEO_DESCRIPTION', //网站默认描述
        );
        $where = array(
            'is_delete' => 1,
            'keyword' => array('IN', $siteConfigKeywordArr),
        );
        $configList = M('sys_config')->where($where)->select();
        if($configList === false){
            $this->error('获取网站默认配置信息失败');
        }
        if(!empty($configList)){
            $siteInfo = array();
            foreach ($configList as $config){
                $siteInfo[$config['keyword']] = $config['config_value'];
            }
            if(!empty($siteInfo)){
                $this->assign('siteInfo', $siteInfo);
            }
        }
    }

    /**
     * 获取搜索框下的搜索热词
     * @author xy
     * @since 2017/09/25 18:10
     * @param int $limit
     */
    protected function getRecommendSearchKeyword($limit = 5){
        $where = array(
            's.type' => 1,
            's.start_time' => array('lt', time()),
            's.end_time' => array('gt', time()),
        );

        $hotKeywordList = M('search_recommend')->where($where)->alias('s')
            ->field('s.*, IF(s.sort = 0, 999999999, s.sort) as new_sort, au.nickname')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'admin_user au ON s.admin_id = au.id')
            ->where($where)
            ->order('new_sort ASC')
            ->limit($limit)
            ->select();
        if($hotKeywordList === false){
            $this->error('获取友情链接失败');
        }
        $this->assign('hotKeywordList', $hotKeywordList);
    }

    /**
     * 自定义设置网站的seo信息
     * @author xy
     * @since 2017/09/28 15:28
     * @param null $title
     * @param null $keywords
     * @param null $description
     */
    protected function setSiteSeoInfo($title = NULL, $keywords = NULL, $description = NULL){
        if(!empty($title)){
            $this->assign('siteTitle', $title);
        }
        if(!empty($keywords)){
            $this->assign('siteKeyword', $keywords);
        }
        if(!empty($description)){
            $this->assign('siteDescription', $description);
        }
    }

    /**
     * ajax发送短信验证码
     * @author xy
     * @since 2017/09/29 15:00
     */
    public function ajax_send_sms_captcha(){
        $phone = trim(I('phone'));
        $sendType = I('send_type', null);
        if(empty($phone) || !isset($sendType)){
            $this->outputJSON(true, 'false', '必填参数缺失');
        }
        //设置session防止频繁发送
        if(session('user_last_send_sms_time')){
            $lastSendTime = session('user_last_send_sms_time');
            $currentTime = time();
            if($lastSendTime + 60 > $currentTime){
                $this->outputJSON(true, 'false', '发送太过频繁');
            }else{
                session('user_last_send_sms_time', 0);
            }
        }

        $paramArr = array(
            'phone' => $phone,
            'send_type' => $sendType,
            'timestamp' => time(),
        );
        $paramArr['sign'] = make_sign($paramArr, array('sign'));
        $url = C('URL.ZHIYU_URL').U('Api/UserCenter/send_captcha');
        $result = http($url, $paramArr, 'POST');
        $result = json_decode($result, true);
        if(isset($result['flag']) && $result['flag'] == 'success'){
            session('user_last_send_sms_time', time());
            $this->outputJSON(false, 'success', '发送成功');
        }else{
            $this->outputJSON(true, 'false', $result['info']);
        }
    }

    /**
     * ajax方式验证短信验证码是否正确
     * @author xy
     * @since 2017/10/09 13:53
     */
    public function ajax_verify_sms_captcha(){
        $phone = trim(I('phone'));
        $captcha = trim(I('captcha'));
        $sendType = I('send_type', null);
        if (!$phone || !isset($sendType) || !$captcha) {
            $this->outputJSON(true, '', '必填参数缺失');
        }
        $paramArr = array(
            'phone' => $phone,
            'captcha' => $captcha,
            'send_type' => $sendType,
            'timestamp' => time(),
        );
        $paramArr['sign'] = make_sign($paramArr, array('sign'));
        $url = C('URL.ZHIYU_URL').U('Api/UserCenter/verify_captcha');
        $result = http($url, $paramArr, 'POST');
        $result = json_decode($result, true);
        if(isset($result['flag']) && $result['flag'] == 'success'){
            $this->outputJSON(false, '', $result['info']);
        }else{
            $this->outputJSON(true, '', $result['info']);
        }
    }
}