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
use Home\Service\SearchService;
use Think\Controller;
use Home\Controller\HomeBaseController;
use Think\NewPage;

class SearchController extends HomeBaseController
{

    /**
     * 搜索的首页
     * @author xy
     * @since 2017/09/25 10:31
     */
    public function index(){
        $keyword = trim(I('keyword'));
        $controller = trim(I('controller'));
        if(!in_array($controller, array('App', 'Rank', 'Gift'))){
            $controller = 'App';
        }
        //记录搜索的关键字
        $service = new SearchService();
        if(!$service->recordSearchLog($keyword)){
            $this->error($service->getFirstError());
        }
        //计算关键词搜索的次数
        if(!$service->countSearchKeywordNum($keyword)){
            $this->error($service->getFirstError());
        }

        $this->assign('keyword', $keyword);
        $this->assign('controller', $controller);
        $this->display();
    }

    /**
     * ajax方式搜索文章
     * @author xy
     * @since 2017/09/25 09:27
     */
    public function ajax_search_art(){
        $artService = new ArticleService();
        $keyword = trim(I('keyword'));
        $currentPage = intval(I('p'));
        //sql的查询条件
        $where = array();
        if(empty($currentPage)){
            $currentPage = 1;
        }
        $totalNum = $artService->countArticleByKeywordNum($keyword);
        // 实例化分页类 传入总记录数和每页显示的记录数
        $pageParams =  array();
        if(!empty($keyword)){
            $pageParams = array(
                'keyword' => $keyword
            );
        }
        $page = new NewPage($totalNum,10, $pageParams);
        // 分页显示输出
        $show = $page->show();
        $artList = $artService->getArticleByKeywordByPage($keyword, $page->firstRow, $page->listRows);
        if(!empty($artList)){
            foreach ($artList as &$art){
                //获取顶级分类id,根据顶级分类id生成对应的详情页链接
                $art['top_parent_id'] = $artService->getTopCateIdByChildCateId($art['catid']);
                if($art['top_parent_id'] == $artService::ARTICLE_STRATEGY){
                    $art['url'] = U('Home/Article/strategy_detail', array('id' => $art['id']));
                }else if($art['top_parent_id'] == $artService::ARTICLE_NEWS){
                    $art['url'] = U('Home/Article/news_detail', array('id' => $art['id']));
                }else if($art['top_parent_id'] == $artService::ARTICLE_EVALUATION){
                    $art['url'] = U('Home/Article/evaluate_detail', array('id' => $art['id']));
                }else if($art['top_parent_id'] == $artService::ARTICLE_QUESTION){
                    $art['url'] = U('Home/Article/ques_detail', array('id' => $art['id']));
                }
            }
        }
        //var_dump($artList);
        $this->assign('currentPage', $currentPage );
        $this->assign('keyword', $keyword );
        $this->assign('totalNum', $totalNum );
        $this->assign('artList', $artList );
        $this->assign('show', $show );
        $html = $this->fetch('ajax_search_art_list');
        $this->ajaxReturn($html);
    }

    /**
     * ajax方式搜索礼包
     * @author xy
     * @since 2017/09/25 09:31
     */
    public function ajax_search_gift(){
        $giftService = new GiftService();
        $keyword = trim(I('keyword'));
        $currentPage = intval(I('p'));
        //sql的查询条件
        $where = array();
        if(empty($currentPage)){
            $currentPage = 1;
        }
        // 实例化分页类 传入总记录数和每页显示的记录数
        $pageParams =  array();
        if(!empty($keyword)){
            $pageParams = array(
                'keyword' => $keyword
            );
        }
        $map = array(
            'CONCAT(lib.app_name, gl.gift_name, gl.original_name)' => array('like', '%'.$keyword.'%'),
            'IFNULL(alib.app_name, lib.app_name)' =>  array('like', '%'.$keyword.'%'),
            'IFNULL(sgl.gift_detail, gl.gift_desc)' =>  array('like', '%'.$keyword.'%'),
            '_logic' => 'OR'
        );
        $where['_complex'] = $map;
        $totalNum = $giftService->countAppGiftTotalNum($where);
        $page = new NewPage($totalNum,8, $pageParams);
        // 分页显示输出
        $show = $page->show();
        $giftList = $giftService->getAppGiftListByPage($where, $page->firstRow, $page->listRows);
        $this->assign('currentPage', $currentPage );
        $this->assign('keyword', $keyword );
        $this->assign('totalNum', $totalNum );
        $this->assign('giftList', $giftList );
        $this->assign('show', $show );
        $html = $this->fetch('ajax_search_gift_list');
        $this->ajaxReturn($html);
    }

    /**
     * ajax方式搜索游戏
     * @author xy
     * @since 2017/09/25 09:50
     */
    public function ajax_search_app(){
        $appService = new AppService();
        $keyword = trim(I('keyword'));
        $currentPage = intval(I('p'));
        //sql的查询条件
        $where = array();
        if(empty($currentPage)){
            $currentPage = 1;
        }
        // 实例化分页类 传入总记录数和每页显示的记录数
        $pageParams =  array();
        if(!empty($keyword)){
            $pageParams = array(
                'keyword' => $keyword
            );
        }
        $map = array(
            'IFNULL(alib.app_name, lib.app_name)' => array('like', '%'.$keyword.'%'),
            'IFNULL(alib.introduct, lib.introduct)' =>  array('like', '%'.$keyword.'%'),
            '_logic' => 'OR'
        );
        $where['_complex'] = $map;
        $orderBy = 'final_hot_sort ASC, app_down_num DESC';
        $totalNum = $appService->countPublishAppNumByCondition($where);
        $page = new NewPage($totalNum,4, $pageParams);
        // 分页显示输出
        $show = $page->show();
        $appList = $appService->getPublishAppByPage($where, $page->firstRow, $page->listRows, $orderBy);
        $this->assign('currentPage', $currentPage );
        $this->assign('keyword', $keyword );
        $this->assign('totalNum', $totalNum );
        $this->assign('appList', $appList );
        $this->assign('show', $show );
        $html = $this->fetch('ajax_search_app_list');
        $this->ajaxReturn($html);
    }
}