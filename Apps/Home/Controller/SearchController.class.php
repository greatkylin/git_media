<?php
/**
 * 搜索控制层.
 * User: xy
 * Date: 2017/9/22
 * Time: 17:26
 */

namespace Home\Controller;

use Home\Service\AppService;
use Home\Service\ArticleService;
use Home\Service\GiftService;
use Think\Controller;
use Home\Controller\HomeBaseController;

class SearchController extends HomeBaseController
{
    public function index(){

    }

    public function ajax_search_art(){
        $artService = new ArticleService();
        $totalNum = $artService->countArticleByKeywordNum('阴阳');
        $artList = $artService->getArticleByKeywordByPage('阴阳', 0, 10);
    }

    public function ajax_search_gift(){
        $giftService = new GiftService();
        $keyword = '乱';
        $map = array(
            'CONCAT(lib.app_name, gl.gift_name, gl.original_name)' => array('like', '%'.$keyword.'%'),
            'IFNULL(alib.app_name, lib.app_name)' =>  array('like', '%'.$keyword.'%'),
            'IFNULL(sgl.gift_detail, gl.gift_desc)' =>  array('like', '%'.$keyword.'%'),
            '_logic' => 'OR'
        );
        $where['_complex'] = $map;
        $totalNum = $giftService->countAppGiftTotalNum($where);
        $giftList = $giftService->getAppGiftListByPage($where,0, 10);
    }

    public function ajax_search_app(){
        $keyword = '阴';
        $map = array(
            'IFNULL(alib.app_name, lib.app_name)' => array('like', '%'.$keyword.'%'),
            'IFNULL(alib.introduct, lib.introduct)' =>  array('like', '%'.$keyword.'%'),
            '_logic' => 'OR'
        );
        $where['_complex'] = $map;
        $orderBy = 'final_hot_sort ASC, app_down_num DESC';
        $appService = new AppService();
        $appTotalNum = $appService->countPublishAppNumByCondition($where);
        $appList = $appService->getPublishAppByPage($where, 0, 10, $orderBy);
        var_dump($appList[0]['app_size']);
    }
}