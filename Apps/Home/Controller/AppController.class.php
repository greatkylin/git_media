<?php
namespace Home\Controller;
use Home\Service\AppService;
use Home\Service\ArticleService;
use Home\Service\IndexService;
use Think\Controller;
use Home\Controller\HomeBaseController;
use Think\NewPage;

class AppController extends HomeBaseController {

    /**
     * 游戏库首页
     * @author xy
     * @since 2017/09/06 14:45
     */
    public function index(){
        //1.获取游戏的游戏顶级分类
        $appService = new AppService();
        $appTypeList = $appService->getSecondLevelAppType();
        $this->assign('appTypeList', $appTypeList);
        if(IS_AJAX){
            //ajax分页
            $this->ajaxGetAppList();
        }
        $this->display();
    }

    /**
     * 下载游戏
     * @author xy
     * @since 2017/09/07 09:31
     */
    public function down () {
        $appId = I('app_id');
        if (!$appId) {
            $this->error('缺少请求参数');
        }
        # 下载地址
        $app_file_url = M(C('DB_ZHIYU.DB_NAME') . '.' . 'app_lib', C('DB_ZHIYU.DB_PREFIX'))->where(array('app_id' => $appId))->getField('app_file_url');
        # 下载量加一
        M(C('DB_ZHIYU.DB_NAME') . '.' . 'app_list', C('DB_ZHIYU.DB_PREFIX'))->where(array('app_id' => $appId))->setInc('app_down_num');
        /*
         //添加游戏下载的记录
         $data = array(
            'app_id' => $appId,
            'uid' => 0,
            'imei' => 0,
            'down_status' => 0,
            'down_time' => time(),
            'source' => 3, //来源媒体站
        );
        M(C('DB_ZHIYU.DB_NAME') . '.' . 'app_down', C('DB_ZHIYU.DB_PREFIX'))->add($data);*/
        $app_file_url = format_url($app_file_url);
        header("Location:{$app_file_url}");
        exit;
    }

    /**
     * 游戏详情页
     * @author
     * @since 2017/09/09 09:57
     */
    public function app_detail(){
        $appId = intval(I('app_id'));
        if(empty($appId)){
            $this->error('缺少请求参数');
        }
        $appService = new AppService();
        //1.获取游戏二级分类
        $appTypeList = $appService->getSecondLevelAppType();
        if(!$appTypeList){
            $this->error($appService->getFirstError());
        }
        //2.根据游戏id获取游戏信息
        $isExist = $appService->isMediaExistApp($appId);
        if(!$isExist){
            $this->error($appService->getFirstError());
        }
        $appInfo = $appService->getAppDetailInfoByAppId($appId);
        if(!$appInfo){
            $this->error($appService->getFirstError());
        }
        //3.根据游戏id获取精选攻略
        $guideList = $appService->getAppDetailGuideByAppId($appId);
        if($guideList === false){
            $this->error($appService->getFirstError());
        }
        //4.获取游戏新闻
        $artService = new ArticleService();
        $newsList = $artService->getAppArticleByCateIdAndAppId(2, $appId, 10);
        //5.获取游戏测评
        $testList = $artService->getAppArticleByCateIdAndAppId(3, $appId, 10);
        //6.获取游戏攻略
        $appGuideLowerList = $artService->getAppArticleGuideByCateIdAndAppId(1, $appId, $artService::ARTICLE_LEVEL_LOWER, 10);
        $appGuideMiddleList = $artService->getAppArticleGuideByCateIdAndAppId(1, $appId, $artService::ARTICLE_LEVEL_MIDDLE, 10);
        $appGuideHighList = $artService->getAppArticleGuideByCateIdAndAppId(1, $appId, $artService::ARTICLE_LEVEL_HIGH, 10);
        $artLevelArr = $artService::getArticleLeverArr();
        //获取相同类型下最热的5款游戏
        if(!empty($appInfo['app_type'])){
            $appInfo['app_type'] = explode(',', $appInfo['app_type'] );
            $parentTypeArr = $appService->getAppTypeIdParentIdAndName($appInfo['app_type']);
            if($parentTypeArr){
                $parentIdArr = array();
                foreach ($parentTypeArr as $key=>$type){
                    $parentIdArr[] = $type['id'];
                }
                if(!empty($parentIdArr)){
                    $where['at.parent_id'] = array('IN', $parentIdArr);
                    $orderBy = 'final_hot_sort ASC, app_down_num DESC';
                    $recommendApp = $appService->getPublishAppByPage($where, 0, 5, $orderBy);
                    $this->assign('recommendApp', $recommendApp);
                }
            }
        }



        $this->assign('newsList', $newsList);
        $this->assign('testList', $testList);
        $this->assign('appGuideLowerList', $appGuideLowerList);
        $this->assign('appGuideMiddleList', $appGuideMiddleList);
        $this->assign('appGuideHighList', $appGuideHighList);
        $this->assign('artLevelArr', $artLevelArr);
        $this->assign('guideList', $guideList);
        $this->assign('appInfo', $appInfo);
        $this->display();
    }

    /**
     * ajax获取游戏库首页列表
     * @author xy
     * @since 2017/09/06 18:55
     */
    public function ajaxGetAppList(){
        //游戏列表类型，1人气最高，2最近更新
        $listType = intval(I('list_type'));
        //游戏二级分类
        $appSecondType = intval(I('app_type'));
        //列表的样式类型 0 列表 1 方格
        $cssType = intval(I('css_type'));
        //当前页
        $currentPage = intval(I('p'));
        //sql的查询条件
        $where = array();
        if(empty($currentPage)){
            $currentPage = 1;
        }
        if(empty($listType)) {
            $listType = 1;
        }
        if($listType == 1){
            $orderBy = 'final_hot_sort ASC, app_down_num DESC';
        }else {
            $orderBy = 'final_new_sort ASC, alist.publish_time DESC';
        }
        $pageParams['list_type'] = $listType;
        if(!empty($appSecondType)){
            $pageParams['app_type'] = $appSecondType;
            $where['at.parent_id'] = $appSecondType;
        }
        $pageParams['css_type'] = $cssType;

        $appService = new AppService();
        // 查询满足要求的总记录数
        $appTotalNum = $appService->countPublishAppNumByCondition($where);
        // 实例化分页类 传入总记录数和每页显示的记录数
        $page = new NewPage($appTotalNum,5, $pageParams);
        // 分页显示输出
        $show = $page->show();
        // 进行分页游戏数据查询 注意limit方法的参数要使用Page类的属性
        $appList = $appService->getPublishAppByPage($where, $page->firstRow, $page->listRows, $orderBy);
        //媒体站本周上架的游戏数量
        $appWeekSjNum = $appService->countCurrentWeekSjAppNum($where);

        $this->assign('appTotalNum', $appTotalNum);
        $this->assign('appWeekSjNum', $appWeekSjNum);
        $this->assign('currentPage', $currentPage);
        $this->assign('listType', $listType);
        $this->assign('cssType', $cssType);
        $this->assign('appSecondType', $appSecondType);

        $this->assign('appList', $appList);
        $this->assign('show', $show);

        $html = $this->fetch('app_index_common');
        $this->ajaxReturn($html);

    }

}