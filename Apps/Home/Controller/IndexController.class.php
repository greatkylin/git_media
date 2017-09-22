<?php
namespace Home\Controller;
use Home\Service\AppService;
use Home\Service\ArticleService;
use Home\Service\GiftService;
use Home\Service\IndexService;
use Think\Controller;
use Home\Controller\HomeBaseController;

class IndexController extends HomeBaseController {
    public function index(){

        $indexService = new IndexService();
        //首页顶部大图
        $topBanner = $indexService->getAdContentListByCategoryKeyword('TOP_BANNER', 3);
        //首页左侧轮播
        $leftBanner = $indexService->getAdContentListByCategoryKeyword('LEFT_BANNER', 3);
        //首页热门文章
        $topArticle = $indexService->getAdContentListByCategoryKeyword('TOP_ARTICLE', 2);
        //首页推荐文章列表
        $recommendArtList = $indexService->getAdContentListByCategoryKeyword('RECOMMEND_LIST', 9);
        //新游测评
        $newGameTest = $indexService->getAdContentListByCategoryKeyword('NEW_GAME_TEST', 3);
        if(!empty($newGameTest)){
            //新游测评评分
            foreach ($newGameTest as $key => $testApp){
                $extend = unserialize($testApp['extend']);
                $newGameTest[$key]['test_app_score'] = empty($extend['test_app_score']) ? 0 : $extend['test_app_score'];
            }
        }
        //每日一题图片
        $everyDayQuesImg = $indexService->getAdContentListByCategoryKeyword('EVERYDAT_QUESTION', 5);
        //精彩专题
        $greatTopic = $indexService->getAdContentListByCategoryKeyword('GREAT_TOPIC', 3);
        if(!empty($greatTopic)){
            foreach ($greatTopic as $key => $topic){
                //精彩专题开始时间与结束时间
                $extend = unserialize($topic['extend']);
                $greatTopic[$key]['activity_start_time'] = date('Y.m.d', $extend['activity_start_time']);
                $greatTopic[$key]['activity_end_time'] = date('Y.m.d', $extend['activity_end_time']);
            }
        }
        //热门活动
        $hotActivity = $indexService->getAdContentListByCategoryKeyword('HOT_ACTIVITY', 5);
        if(!empty($hotActivity)){
            foreach ($hotActivity as $key => $activity){
                //精彩专题开始时间与结束时间
                $extend = unserialize($activity['extend']);
                $hotActivity[$key]['activity_start_time'] = date('m.d', $extend['activity_start_time']);
                $hotActivity[$key]['activity_end_time'] = date('m.d', $extend['activity_end_time']);
            }
        }
        //新游预告
        $newAppPreview = $indexService->getNewAppNoticeByCategoryKeyword('NEW_APP_NOTICE', 12);
        if(!empty($newAppPreview)){
            foreach ($newAppPreview as $key => $preview){
                $newAppPreview[$key]['open_test_time'] = date('m 月 d H:i', $preview['open_test_time']);
            }
        }
        $artService = new ArticleService();
        //每日一题文章列表
        $questionList = $artService->getLastEveryDayQues(9);
        $hotAppGuide = $indexService->getAdContentListByCategoryKeyword('HOT_GUIDE', 4);
        if(!empty($hotAppGuide)){
            foreach ($hotAppGuide as $key => $guide){
                $extend = unserialize($guide['extend']);
                $artCategoryId = $extend['art_category_id'];
                $hotAppGuide[$key]['article'] = array();
                if(!empty($artCategoryId)){
                    $hotAppGuide[$key]['article'] = $artService->getAllAppArticleByCateId($artCategoryId, 5);
                }
            }
        }

        $appService = new AppService();
        //热游推荐
        $hotAppRecommend = $appService->getIndexHotRecommendAppNameAndIcon(8);
        //精选游戏
        $carefulChoiceApp = $appService->getIndexCarefulChoiceAppNameAndIcon();
        //新游一览
        $newAppView = $appService->getIndexNewAppNameAndIcon(21);
        //游戏礼包排行
        $indexGiftList = $appService->getIndexHotAppGift(10);
        //游戏下载榜 周榜 月榜 总榜
        $downloadWeek = $appService->getIndexHotAppRank(1, 9);
        $downloadMonth = $appService->getIndexHotAppRank(2, 9);
        $downloadTotal = $appService->getIndexHotAppRank(3, 9);
        //畅游榜 周榜，月榜 总榜
        $popularWeek = $appService->getIndexPopularAppRank(1, 9);
        $popularMonth = $appService->getIndexPopularAppRank(2, 9);
        $popularTotal = $appService->getIndexPopularAppRank(3, 9);
        //新游榜
        $newRankTotal = $appService->getNewAppRankTotal(9);

        $this->assign('topBanner', $topBanner);
        $this->assign('hotAppRecommend', $hotAppRecommend);
        $this->assign('carefulChoiceApp', $carefulChoiceApp);
        $this->assign('newAppView', $newAppView);
        $this->assign('leftBanner', $leftBanner);
        $this->assign('topArticle', $topArticle);
        $this->assign('recommendArtList', $recommendArtList);
        $this->assign('indexGiftList', $indexGiftList);
        $this->assign('newGameTest', $newGameTest);
        $this->assign('everyDayQuesImg', $everyDayQuesImg);
        $this->assign('questionList', $questionList);
        $this->assign('newAppPreview', $newAppPreview);
        $this->assign('greatTopic', $greatTopic);
        $this->assign('hotActivity', $hotActivity);
        $this->assign('downloadWeek', $downloadWeek);
        $this->assign('downloadMonth', $downloadMonth);
        $this->assign('downloadTotal', $downloadTotal);
        $this->assign('popularWeek', $popularWeek);
        $this->assign('popularMonth', $popularMonth);
        $this->assign('popularTotal', $popularTotal);
        $this->assign('newRankTotal', $newRankTotal);
        $this->assign('hotAppGuide', $hotAppGuide);

        $this->display();
    }

    /**
     * 新游预告列表
     * @author xy
     * @since 2017/09/04
     */
    public function app_notice(){
        //获取日期时间参数
        $currentTime = intval(I('open_time'));
        if(empty($currentTime)){
            $currentTime = time();
        }
        $currentTime = strtotime(date('Y-m-d', $currentTime));
        $timeArr = array(
            $currentTime - 3*86400,
            $currentTime - 2*86400,
            $currentTime - 1*86400,
            $currentTime,
            $currentTime + 1*86400,
            $currentTime + 2*86400,
            $currentTime + 3*86400,
        );
        $service = new IndexService();
        $appList = $service->getNewAppNoticeListByDate($currentTime);
        $this->assign('timeArr', $timeArr);
        $this->assign('appList', $appList);
        $this->display();
    }

    /**
     * 活动详情页
     * @author xy
     * @since 2017/09/04
     */
    public function activity_detail(){
        $activityId = intval(I('activity_id'));
        $indexService = new IndexService();
        $activityDetail = $indexService->getValidActivityDetailById($activityId);

        $appService = new AppService();
        $giftService = new GiftService();
        //1.获取热门礼包周榜月榜
        $giftWeekList = $giftService->getHotAppGiftWeekList(10);
        $giftMonthList = $giftService->getHotAppGiftMonthList(10);
        //2.获取本周专题
        $indexService = new IndexService();
        $thisWeekTopic = $indexService->getThisWeekTopicByKeyword();
        //3.获取热门游戏
        $hotAppList = $appService->getIndexHotRecommendAppNameAndIcon(5);

        $this->assign('giftWeekList', $giftWeekList);
        $this->assign('giftMonthList', $giftMonthList);
        $this->assign('thisWeekTopic', $thisWeekTopic);
        $this->assign('hotAppList', $hotAppList);
        $this->assign('activityDetail', $activityDetail);
        $this->assign('currentTime', time());
        $this->display();
    }

    /**
     * 活动详情预览页
     * @author xy
     * @since 2017/09/04
     */
    public function activity_preview(){
        $activityId = intval(I('activity_id'));
        $indexService = new IndexService();
        $activityDetail = $indexService->getActivityDetailById($activityId);

        $appService = new AppService();
        $giftService = new GiftService();
        //1.获取热门礼包周榜月榜
        $giftWeekList = $giftService->getHotAppGiftWeekList(10);
        $giftMonthList = $giftService->getHotAppGiftMonthList(10);
        //2.获取本周专题
        $indexService = new IndexService();
        $thisWeekTopic = $indexService->getThisWeekTopicByKeyword();
        //3.获取热门游戏
        $hotAppList = $appService->getIndexHotRecommendAppNameAndIcon(5);

        $this->assign('giftWeekList', $giftWeekList);
        $this->assign('giftMonthList', $giftMonthList);
        $this->assign('thisWeekTopic', $thisWeekTopic);
        $this->assign('hotAppList', $hotAppList);
        $this->assign('activityDetail', $activityDetail);
        $this->assign('currentTime', time());
        $this->display('activity_detail');
    }
}