<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/12
 * Time: 14:28
 */

namespace Home\Controller;
use Home\Controller\HomeBaseController;
use Home\Service\AppService;
use Home\Service\GiftService;
use Think\NewPage;

class GiftController extends HomeBaseController
{
    /**
     * 礼包中心页, 获取具体的礼包
     * @author xy
     * @since 2017/09/12 14:29
     */
    public function index(){
        //TODO 1.获取轮播图片

        //TODO 2.获取右侧图片文字
        $giftService = new GiftService();
        //3.获取热门礼包数8款
        $hotGiftList = $giftService->getHotGiftList(8);
        if($hotGiftList === false){
            $this->error($giftService->getFirstError());
        }
        $appIdArr = array();
        foreach ($hotGiftList as $gift){
            $appIdArr[] = $gift['app_id'];
        }
        //4.获取新游礼包
        $newGiftList = $giftService->getNewGiftList(8);
        if($newGiftList === false){
            $this->error($giftService->getFirstError());
        }
        foreach ($newGiftList as $nGift){
            $appIdArr[] = $nGift['app_id'];
        }
        $appIdArr = array_unique($appIdArr);
        $appIdStr= implode(',', $appIdArr);

        $this->assign('appIdStr', $appIdStr);
        $this->assign('hotGiftList', $hotGiftList);
        $this->assign('newGiftList', $newGiftList);
        $this->display();
    }

    /**
     * 游戏礼包页
     * @author xy
     * @since 2017/09/12 18:08
     */
    public function gift_page(){
        $appId = intval(I('app_id'));
        if(empty($appId)){
            $this->error('必填参数缺失');
        }
        //1.游戏icon等数据
        $appService = new AppService();
        $appInfo = $appService->getAppDetailInfoByAppId($appId);
        if($appInfo === false){
            $this->error($appService->getFirstError());
        }
        $giftService = new GiftService();
        //2.游戏礼包页图片
        $banner = $giftService->getGiftBannerByAppId($appId);
        if($banner === false){
            $this->error($giftService->getFirstError());
        }
        //3.该游戏下的所有礼包 ajax分类
        if(IS_AJAX){
            $this->ajaxGetGiftBelongAppList();
        }
        $this->assign('appId', $appId);
        $this->assign('banner', $banner);
        $this->assign('appInfo', $appInfo);
        $this->display();

    }

    /**
     * 礼包大全页
     * @author xy
     * @since 2017/09/12 17:59
     */
    public function all_gift(){
        //1.获取热门礼包 8款
        $giftService = new GiftService();
        $hotGiftList = $giftService->getHotGiftList(8);
        if($hotGiftList === false){
            $this->error($giftService->getFirstError());
        }

        $this->assign('hotGiftList', $hotGiftList);
        $this->display();
    }

    /**
     * ajax方式获取礼包中心的其他列表列表
     * @author xy
     * @since 2017/09/12 17:05
     */
    public function ajax_get_index_other_gift_list(){
        $appIds = I('app_ids');
        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage = 1;
        }
        $where = array();
        if(!empty($appIds)){
            $where = array(
                'gl.app_id' => array('NOT IN', $appIds),
            );

        }
        $giftService = new GiftService();
        //计算媒体站有礼包的游戏总量
        $totalNum = $giftService->countAppGiftTotalNum($where);
        if($totalNum === false){
            $this->error($giftService->getFirstError());
        }
        $page = new NewPage($totalNum, 1);
        // 分页显示输出
        $show = $page->show();
        //计算媒体站有礼包的游戏列表
        $giftList = $giftService->getAppGiftListByPage($where, $page->firstRow, $page->listRows);
        //var_dump($giftList);
        $this->assign('appIds', $appIds);
        $this->assign('currentPage', $currentPage);
        $this->assign('show', $show);
        $this->assign('giftList', $giftList);

        $html = $this->fetch('index_other_gift_list');
        $this->ajaxReturn($html);
    }

    /**
     * ajax方式获取游戏礼包页该礼包下的礼包
     * @author xy
     * @since 2017/09/13 17:14
     */
    public function ajax_get_gift_belong_app_list(){
        $appId = intval(I('app_id'));
        if(empty($appId)){
            $this->error('必填参数缺失');
        }
        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage = 1;
        }
        $giftService = new GiftService();
        //计算媒体站有礼包的游戏总量
        $totalNum = $giftService->countAllGiftBelongAppId($appId);
        if($totalNum === false){
            $this->error($giftService->getFirstError());
        }
        $urlParam = array();
        if(!empty($appId)){
            $urlParam['app_id'] = $appId;
        }
        $page = new NewPage($totalNum, 6, $urlParam);
        // 分页显示输出
        $show = $page->show();
        //计算媒体站有礼包的游戏列表
        $giftList = $giftService->getAllGiftBelongAppIdByPage($appId, $page->firstRow, $page->listRows);

        $this->assign('appId', $appId);
        $this->assign('currentPage', $currentPage);
        $this->assign('show', $show);
        $this->assign('giftList', $giftList);

        $html = $this->fetch('app_gift_list');
        $this->ajaxReturn($html);
    }

    /**
     * ajax方式获取礼包大全页礼包列表
     * @author xy
     * @since 2017/09/14 10:52
     * @return bool
     */
    public function ajax_get_all_gift_by_letter(){

        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage = 1;
        }
        $currentLetter = strtoupper(trim(I('letter')));
        $where = array();
        $allowLetterArr = array(
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        );
        if(!empty($currentLetter)){
            if(!in_array($currentLetter, $allowLetterArr)){
                return false;
            }
            $where = array(
                'alist.pin_yin' => strtoupper($currentLetter),
            );
        }
        $giftService = new GiftService();
        //计算媒体站有礼包的游戏总量
        $totalNum = $giftService->countAppGiftTotalNum($where);
        if($totalNum === false){
            $this->error($giftService->getFirstError());
        }
        $urlParam = array();
        if(!empty($currentLetter)){
            $urlParam['letter'] = $currentLetter;
        }
        $page = new NewPage($totalNum, 10, $urlParam);
        // 分页显示输出
        $show = $page->show();
        //计算媒体站有礼包的游戏列表
        $giftList = $giftService->getAppGiftListByPage($where, $page->firstRow, $page->listRows);

        $this->assign('currentLetter', $currentLetter);
        $this->assign('currentPage', $currentPage);
        $this->assign('show', $show);
        $this->assign('giftList', $giftList);
        $this->assign('allowLetterArr', $allowLetterArr);

        $html = $this->fetch('all_gift_list');
        $this->ajaxReturn($html);
    }
}