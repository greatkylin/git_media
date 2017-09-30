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
        //游戏二级分类
        $appSecondType = intval(I('app_type'));
        //1.获取游戏的游戏顶级分类
        $appService = new AppService();
        $appTypeList = $appService->getSecondLevelAppType();
        $this->assign('appTypeList', $appTypeList);
        if(IS_AJAX){
            //ajax分页
            $this->ajaxGetAppList();
        }
        $this->assign('appSecondType', $appSecondType);
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
         //TODO 添加游戏下载的记录
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
        $isExist = $appService->isMediaExistAndPublishApp($appId);
        if(!$isExist){
            $this->error($appService->getFirstError());
        }
        $appInfo = $appService->getAppDetailInfoByAppId($appId);
        if(!$appInfo){
            $this->error($appService->getFirstError());
        }
        //获取二维码
        if(!empty($appInfo)){
            $downUrl = C('BASE_URL').U('Home/App/down', array('app_id' => $appInfo['app_id']));
            $logoPath = grab_image(format_url($appInfo['icon']), 'logo'.$appInfo['app_id']);
            $appInfo['qr_code'] = generate_logo_qr_code($downUrl, $appInfo['app_id'], $logoPath);
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
        $appGuideLowerList = $artService->getAppArticleStrategyByCateIdAndAppId(1, $appId, $artService::ARTICLE_LEVEL_LOWER, 0, 10);
        $appGuideMiddleList = $artService->getAppArticleStrategyByCateIdAndAppId(1, $appId, $artService::ARTICLE_LEVEL_MIDDLE, 0, 10);
        $appGuideHighList = $artService->getAppArticleStrategyByCateIdAndAppId(1, $appId, $artService::ARTICLE_LEVEL_HIGH, 0,10);
        $artLevelArr = $artService::getArticleLeverArr();
        //获取相同类型下最热的5款游戏
        $recommendApp = $appService->getAppsInSameAppTypeByAppId($appInfo['app_id'], 5);
        if($recommendApp === false){
            $this->error($appService->getFirstError());
        }
        $this->assign('recommendApp', $recommendApp);

        $this->assign('appTypeList', $appTypeList);
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
     * 游戏攻略合集页
     * @author xy
     * @since 2017/09/08
     */
    public function strategy(){
        $appId = intval(I('app_id'));
        if(empty($appId)){
            $this->error('缺少请求参数');
        }
        $appService = new AppService();
        //验证游戏是否已存在于媒体站且发布了
        $isExistAndPublish = $appService->isMediaExistAndPublishApp($appId);
        if(!$isExistAndPublish){
            $this->error($appService->getFirstError());
        }
        $appName = $appService->getAppNameByAppId($appId);
        $appTypeList = $appService->getSecondLevelAppType();

        //1.获取游戏详情页的设置的精品攻略
        $guideList = $appService->getAppDetailGuideByAppId($appId);
        if($guideList === false){
            $this->error($appService->getFirstError());
        }
        //2.获取时间段是本周的游戏专题
        $indexService = new IndexService();
        $thisWeekTopic = $indexService->getThisWeekTopicByKeyword();
        if($thisWeekTopic === false){
            $this->error($appService->getFirstError());
        }
        //3.获取同类型的最热的5款游戏
        $recommendApp = $appService->getAppsInSameAppTypeByAppId($appId, 5);
        if($recommendApp === false){
            $this->error($appService->getFirstError());
        }
        //4.获取指定游戏攻略的列表
        if(IS_AJAX){
            $this->ajaxGetStrategyList();
        }
        $this->assign('appId', $appId);
        $this->assign('appName', $appName);
        $this->assign('appTypeList', $appTypeList);
        $this->assign('guideList', $guideList);
        $this->assign('thisWeekTopic', $thisWeekTopic);
        $this->assign('recommendApp', $recommendApp);

        $this->display();
    }

    /**
     * 游戏专题列表页
     * @author xy
     * @since 2017/09/11 10:17
     */
    public function app_topic_list(){
        $appService = new AppService();
        //计算游戏每周专题的数量
        $totalNum = $appService->countAppTopicNum();
        if($totalNum === false){
            $this->error($appService->getFirstError());
        }
        // 实例化分页类 传入总记录数和每页显示的记录数
        $page = new NewPage($totalNum,15, array(), false);
        // 分页显示输出
        $show = $page->show();
        $topicList = $appService->getAppTopicList($page->firstRow, $page->listRows);
        if($topicList === false){
            $this->error($appService->getFirstError());
        }
        //获取专题列表头部的图片
        $image = $appService->getAppTopicListImage();
        if($image === false){
            $this->error($appService->getFirstError());
        }
        $this->assign('image', $image);
        $this->assign('show', $show);
        $this->assign('topicList', $topicList);
        $this->display();
    }

    /**
     * 游戏每周专题的详情页
     * @author xy
     * @since 2017/09/11 17:29
     */
    public function app_topic_detail(){
        $topicId = intval(I('topic_id'));
        $topicType = intval(I('topic_type'));
        if(empty($topicType) || empty($topicId)){
            $this->error('缺少请求参数');
        }
        if(!in_array($topicType, array(1, 2))){
            $this->error('请求参数错误');
        }
        $appService = new AppService();
        //更具topic_id 获取专题详情
        $topicContent = $appService->getAppTopicContentByTopicId($topicId);
        if($topicContent === false){
            $this->error($appService->getFirstError());
        }
        //根据topic_type来显示不同的视图模板
        $this->assign('topicContent', $topicContent);

        if($topicType == 1){
            $tplName = 'app_topic_tpl';
        }else{
            $tplName = 'app_topic_editor';
        }
        $this->display($tplName);
    }


    /**
     * ajax方式获取指定游戏的游戏攻略列表
     * @author xy
     * @since 2017/09/08 14:30
     */
    public function ajaxGetStrategyList(){
        $appId = intval(I('app_id'));
        if(empty($appId)){
            $this->ajaxReturn('必填参数缺失');
        }
        //当前页
        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage = 1;
        }
        //文章类型 1初阶，2进阶，3高阶
        $service = new ArticleService();
        $levelType = intval(I('level_type'));
        if(empty($levelType)){
            $levelType = $service::ARTICLE_LEVEL_LOWER;
        }
        $pageParams = array(
            'app_id' => $appId,
            'level_type' => $levelType,
        );
        $strategyTotalNum = $service->countAppArticleStrategyByCateIdAndAppId(1, $appId, $levelType);
        // 实例化分页类 传入总记录数和每页显示的记录数
        $page = new NewPage($strategyTotalNum,19, $pageParams);
        // 分页显示输出
        $show = $page->show();
        $strategyList = $service->getAppArticleStrategyByCateIdAndAppId(1, $appId, $levelType, $page->firstRow, $page->listRows);
        //获取游戏攻略阶级的类型
        $levelTypeArr = $service::getArticleLeverArr();

        $this->assign('appId', $appId);
        $this->assign('currentPage', $currentPage);
        $this->assign('levelType', $levelType);
        $this->assign('levelTypeArr', $levelTypeArr);
        $this->assign('strategyList', $strategyList);
        $this->assign('show', $show);

        $html = $this->fetch('strategy_list');
        $this->ajaxReturn($html);
    }

    /**
     * ajax获取游戏库首页列表
     * @author xy
     * @since 2017/09/06 18:55
     */
    public function ajaxGetAppList(){
        //游戏列表类型，1人气最高，2最近更新，3新建专区
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
        }else if($listType == 2){
            $orderBy = 'final_new_sort ASC, alist.publish_time DESC';
        }else{
            $orderBy = 'list.create_time DESC';
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