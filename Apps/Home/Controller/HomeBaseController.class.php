<?php
namespace Home\Controller;
use Endroid\QrCode\QrCode;
use Think\Controller;
class HomeBaseController extends Controller {

    public function _initialize(){
        //获取网站的友情链接
        $this->getFriendLink(10);
        //获取网站默认设置信息，seo关键字，网站名称，备案信息等
        $this->getDefaultSiteInfo();
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
}