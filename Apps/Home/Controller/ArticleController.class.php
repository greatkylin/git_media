<?php
/**
 * 媒体站前台资讯控制器
 * User: xy
 * Date: 2017/9/18
 * Time: 9:16
 */

namespace Home\Controller;

use Home\Service\AppService;
use Home\Service\ArticleService;
use Home\Service\GiftService;
use Home\Service\IndexService;
use Think\App;
use Think\Controller;
use Home\Controller\HomeBaseController;
use Think\NewPage;

class ArticleController extends HomeBaseController
{
    //文章主分类
    const ARTICLE_STRATEGY = 1;    //游戏攻略
    const ARTICLE_NEWS= 2;         //游戏资讯
    const ARTICLE_EVALUATION = 3;  //游戏测评
    const ARTICLE_QUESTION = 50;   //每日一题

    //游戏攻略类型
    const ARTICLE_LEVEL_LOWER = 1;     //文章 初阶
    const ARTICLE_LEVEL_MIDDLE = 2;    //文章 进级
    const ARTICLE_LEVEL_HIGH = 3;      //文章 高阶

    /**
     * 资讯中心首页
     * @author xy
     * @since 2017/09/18 14:29
     */
    public function index(){
        $service = new ArticleService();
        //1.获取3张图片,获取首页管理中的攻略驿站设置的3张图片
        $indexService = new IndexService();
        $bannerList = $indexService->getAdContentListByCategoryKeyword('STRATEGY_STATION');
        if($bannerList === false){
            $this->error($indexService->getFirstError());
        }
        //2.最新的游戏资讯
        $newsList = $service -> getAllAppArticleByCateId(self::ARTICLE_NEWS, 13);
        if($newsList === false){
            $this->error('查询游戏资讯失败');
        }
        //3.最新的游戏攻略
        $strategyTypeArr = $service::getArticleLeverArr();
        $strategyLowerList = $service->getAllStrategyListByTypeId(self::ARTICLE_LEVEL_LOWER, 11);
        $strategyMiddleList = $service->getAllStrategyListByTypeId(self::ARTICLE_LEVEL_MIDDLE, 11);
        $strategyHighList = $service->getAllStrategyListByTypeId(self::ARTICLE_LEVEL_HIGH, 11);
        if($strategyLowerList === false || $strategyMiddleList === false || $strategyHighList === false){
            $this->error($service->getFirstError());
        }
        //4.最新的新游测评
        $evaluationList = $service->getAllAppArticleByCateId(self::ARTICLE_EVALUATION, 13);
        if($evaluationList === false){
            $this->error('查询新游测评失败');
        }
        //5.腾讯游戏攻略
        $supplier = M(C('DB_ZHIYU.DB_NAME') . '.' . 'supplier', C('DB_ZHIYU.DB_PREFIX'))
            ->field('supplier_id')
            ->where(array('supplier_name' => '腾讯'))
            ->find();
        if(empty($supplier)){
            $this->error('获取游戏厂商信息失败');
        }
        $supplierId = $supplier['supplier_id'];
        $txAppList = $service->getStrategyAppListBySupplierId($supplierId, 12);
        if(empty($txAppList)){
            $this->error('获取腾讯热门游戏失败');
        }
        //6.ajax网易游戏攻略
        $this->assign('bannerList', $bannerList);
        $this->assign('newsList', $newsList);
        $this->assign('strategyLowerList', $strategyLowerList);
        $this->assign('strategyMiddleList', $strategyMiddleList);
        $this->assign('strategyHighList', $strategyHighList);
        $this->assign('strategyTypeArr', $strategyTypeArr);
        $this->assign('evaluationList', $evaluationList);
        $this->assign('txAppList', $txAppList);
        $this->display();

    }

    /**
     * ajax分页获取网易热门游戏攻略的游戏列表
     * @author xy
     * @since 2017/09/18 11:40
     */
    public function ajax_get_strategy_net_ease_app_list(){
        //当前页
        $currentPage = intval(I('p'));
        if(empty($currentPage)){
            $currentPage = 1;
        }
        //获取网易的supplier_id
        $supplier = M(C('DB_ZHIYU.DB_NAME') . '.' . 'supplier', C('DB_ZHIYU.DB_PREFIX'))
            ->field('supplier_id')
            ->where(array('supplier_name' => '网易'))
            ->find();
        if(empty($supplier)){
            $this->error('获取游戏厂商信息失败');
        }
        $supplierId = $supplier['supplier_id'];

        $service = new ArticleService();
        // 查询满足要求的总记录数
        $appTotalNum = $service->countStrategyAppListBySupplierIdNum($supplierId);
        if($appTotalNum === false){
            $this->error($service->getFirstError());
        }
        // 实例化分页类 传入总记录数和每页显示的记录数
        $page = new NewPage($appTotalNum,12);
        // 分页显示输出
        $show = $page->show();
        // 进行分页游戏数据查询 注意limit方法的参数要使用Page类的属性
        $appList = $service->getStrategyAppListBySupplierIdPage($supplierId, $page->firstRow, $page->listRows);
        if($appList === false){
            $this->error($service->getFirstError());
        }

        $this->assign('appTotalNum', $appTotalNum);
        $this->assign('currentPage', $currentPage);
        $this->assign('appList', $appList);
        $this->assign('show', $show);
        $html = $this->fetch('index_net_ease_game');
        $this->ajaxReturn($html);
    }

    /**
     * 每日一题合集页
     * @author xy
     * @since 2017/09/20 15:20
     */
    public function ques_collect(){
        if(IS_AJAX){
            //ajax获取最新发布的每日一题列表
            //当前页
            $currentPage = intval(I('p'));
            if(empty($currentPage)){
                $currentPage = 1;
            }
            $service = new ArticleService();
            // 查询满足要求的总记录数
            $artTotalNum = $service->countArticleByCateIdAndAppIdNum(self::ARTICLE_QUESTION, 0);
            if($artTotalNum === false){
                $this->error($service->getFirstError());
            }
            // 实例化分页类 传入总记录数和每页显示的记录数
            $page = new NewPage($artTotalNum,8);
            // 分页显示输出
            $show = $page->show();
            // 进行分页游戏数据查询 注意limit方法的参数要使用Page类的属性
            $articleList = $service->getArticleByCateIdAndAppIdByPage(self::ARTICLE_QUESTION, 0, $page->firstRow, $page->listRows);
            if($articleList === false){
                $this->error($service->getFirstError());
            }
            $this->assign('artTotalNum', $artTotalNum);
            $this->assign('currentPage', $currentPage);
            $this->assign('articleList', $articleList);
            $this->assign('show', $show);

            $html = $this->fetch('ajax_ques_collect');
            $this->ajaxReturn($html);

        }
        //每日一题游戏轮播数据从首页管理中 分类关键字 DAILY_QUESTION_APP
        $indexService = new IndexService();
        $quesAppImageList = $indexService->getDailyQuestionAppByKeyword();
        if($quesAppImageList === false){
            $this->error($indexService->getFirstError());
        }
        //获取本周游戏专题
        $thisWeekTopic = $indexService->getThisWeekTopicByKeyword();
        if($thisWeekTopic === false){
            $this->error($indexService->getFirstError());
        }
        //获取热门礼包周榜日榜
        $giftService = new GiftService();
        $giftWeekList = $giftService->getHotAppGiftWeekList(10);
        $giftDailyList = $giftService->getHotAppGiftDailyList(10);
        if($giftWeekList === false || $giftDailyList === false){
            $this->error($giftService->getFirstError());
        }
        //热门游戏
        $appService = new AppService();
        $hotAppList = $appService->getIndexHotRecommendAppNameAndIcon(5);
        if($hotAppList === false){
            $this->error($appService->getFirstError());
        }


        $this->assign('giftWeekList', $giftWeekList);
        $this->assign('giftDailyList', $giftDailyList);
        $this->assign('quesAppImageList', $quesAppImageList);
        $this->assign('thisWeekTopic', $thisWeekTopic);
        $this->assign('hotAppList', $hotAppList);

        $this->display();
    }

    /**
     * 游戏每日一题页
     * @author xy
     * @sicne 2017/09/20 17:10
     */
    public function app_ques(){
        $appId = intval(I('app_id'));
        if(empty($appId)){
            $this->error('必填参数缺失');
        }
        if(IS_AJAX){
            //ajax方式获取该游戏的每日一题
            //当前页
            $currentPage = intval(I('p'));

            if(empty($currentPage)){
                $currentPage = 1;
            }
            $service = new ArticleService();
            // 查询满足要求的总记录数
            $artTotalNum = $service->countArticleByCateIdAndAppIdNum(self::ARTICLE_QUESTION, $appId);
            if($artTotalNum === false){
                $this->error($service->getFirstError());
            }
            // 实例化分页类 传入总记录数和每页显示的记录数
            $paramArr = array();
            if(!empty($appId)){
                $paramArr['app_id'] = $appId;
            }
            $page = new NewPage($artTotalNum,10, $paramArr);
            // 分页显示输出
            $show = $page->show();
            // 进行分页游戏数据查询 注意limit方法的参数要使用Page类的属性
            $articleList = $service->getArticleByCateIdAndAppIdByPage(self::ARTICLE_QUESTION, $appId, $page->firstRow, $page->listRows);
            if($articleList === false){
                $this->error($service->getFirstError());
            }
            $this->assign('appId', $appId);
            $this->assign('artTotalNum', $artTotalNum);
            $this->assign('currentPage', $currentPage);
            $this->assign('articleList', $articleList);
            $this->assign('show', $show);

            $html = $this->fetch('ajax_app_ques');
            $this->ajaxReturn($html);
        }
        //获取游戏详情信息
        $appService = new AppService();
        $appInfo = $appService->getAppDetailInfoByAppId($appId);
        if($appInfo === false){
            $this->error($appService->getFirstError());
        }

        //每日一题游戏
        $artService = new ArticleService();
        $appList = $artService->getArticleAppListByCateId(self::ARTICLE_QUESTION, 12);
        if($appList === false){
            $this->error($artService->getFirstError());
        }

        //中间的广告
        $giftService = new GiftService();
        $giftAd = $giftService->getAppQuesGiftImageAd($appId);

        $this->assign('appId', $appId);
        $this->assign('appList', $appList);
        $this->assign('appInfo', $appInfo);
        $this->assign('giftAd', $giftAd);

        $this->display();
    }


    /**
     * 游戏攻略列表
     * @author xy
     * @since 2017/09/18 16:13
     */
    public function strategy_list(){
        //礼包排行榜，热门游戏，本周专题数据
        $this->list_left_common();
        $appId = intval(I('app_id'));
        $appService = new AppService();
        //如果游戏id不为空获取游戏名称
        if(!empty($appId)){
            $appName = $appService->getAppNameByAppId($appId);
            if(empty($appName)){
                $this->error($appService->getFirstError());
            }
            $this->assign('appName', $appName);
        }

        if(IS_AJAX){
            //当前页
            $currentPage = intval(I('p'));
            if(empty($currentPage)){
                $currentPage = 1;
            }
            $service = new ArticleService();
            $articleTypeArr = $service->getArticleLeverArr();
            $typeId = intval(I('type_id'));
            if(!empty($typeId) && !in_array($typeId, $service->getArticleLevelIdArr())){
                $this->error('类型错误');
            }
            // 查询满足要求的总记录数
            $artTotalNum = $service->countAppArticleStrategyByCateIdAndAppId(self::ARTICLE_STRATEGY, $appId, $typeId);
            if($artTotalNum === false){
                $this->error($service->getFirstError());
            }
            $paramArr = array(
                'type_id' => $typeId,
                'app_id' => $appId
            );
            // 实例化分页类 传入总记录数和每页显示的记录数
            $page = new NewPage($artTotalNum,6, $paramArr);
            // 分页显示输出
            $show = $page->show();
            // 进行分页游戏数据查询 注意limit方法的参数要使用Page类的属性
            $articleList = $service->getAppArticleStrategyByCateIdAndAppId(self::ARTICLE_STRATEGY, $appId, $typeId, $page->firstRow, $page->listRows);
            if($articleList === false){
                $this->error($service->getFirstError());
            }
            $this->assign('appId', $appId);
            $this->assign('typeId', $typeId);
            $this->assign('artTotalNum', $artTotalNum);
            $this->assign('currentPage', $currentPage);
            $this->assign('articleList', $articleList);
            $this->assign('articleTypeArr', $articleTypeArr);
            $this->assign('show', $show);
            $html = $this->fetch('ajax_strategy_list');
            $this->ajaxReturn($html);
        }
        $this->assign('appId', $appId);
        $this->display();
    }

    /**
     * 游戏评测列表
     * @author xy
     * @since 2017/09/18 15:52
     */
    public function evaluate_list(){
        //礼包排行榜，热门游戏，本周专题数据
        $this->list_left_common();
        if(IS_AJAX){
            //当前页
            $currentPage = intval(I('p'));
            if(empty($currentPage)){
                $currentPage = 1;
            }
            $service = new ArticleService();
            // 查询满足要求的总记录数
            $artTotalNum = $service->countArticleByCateIdAndAppIdNum(self::ARTICLE_EVALUATION, 0);
            if($artTotalNum === false){
                $this->error($service->getFirstError());
            }
            // 实例化分页类 传入总记录数和每页显示的记录数
            $page = new NewPage($artTotalNum,6);
            // 分页显示输出
            $show = $page->show();
            // 进行分页游戏数据查询 注意limit方法的参数要使用Page类的属性
            $articleList = $service->getArticleByCateIdAndAppIdByPage(self::ARTICLE_EVALUATION, 0, $page->firstRow, $page->listRows);
            if($articleList === false){
                $this->error($service->getFirstError());
            }
            $this->assign('artTotalNum', $artTotalNum);
            $this->assign('currentPage', $currentPage);
            $this->assign('articleList', $articleList);
            $this->assign('show', $show);
            $html = $this->fetch('ajax_evaluate_list');
            $this->ajaxReturn($html);
        }
        $this->display();
    }

    /**
     * 游戏测评列表
     * @author xy
     * @since 2017/09/18 14:30
     */
    public function news_list(){
        //礼包排行榜，热门游戏，本周专题数据
        $this->list_left_common();
        if(IS_AJAX){
            //当前页
            $currentPage = intval(I('p'));
            if(empty($currentPage)){
                $currentPage = 1;
            }
            $service = new ArticleService();
            // 查询满足要求的总记录数
            $artTotalNum = $service->countArticleByCateIdAndAppIdNum(self::ARTICLE_NEWS, 0);
            if($artTotalNum === false){
                $this->error($service->getFirstError());
            }
            // 实例化分页类 传入总记录数和每页显示的记录数
            $page = new NewPage($artTotalNum,6);
            // 分页显示输出
            $show = $page->show();
            // 进行分页游戏数据查询 注意limit方法的参数要使用Page类的属性
            $articleList = $service->getArticleByCateIdAndAppIdByPage(self::ARTICLE_NEWS, 0, $page->firstRow, $page->listRows);
            if($articleList === false){
                $this->error($service->getFirstError());
            }
            $this->assign('artTotalNum', $artTotalNum);
            $this->assign('currentPage', $currentPage);
            $this->assign('articleList', $articleList);
            $this->assign('show', $show);
            $html = $this->fetch('ajax_news_list');
            $this->ajaxReturn($html);
        }
        $this->display();
    }

    /**
     * 每日一题列表
     * @author xy
     * @since 2017/09/19
     */
    public function ques_list(){
        $this->list_left_common();
        if(IS_AJAX){
            //当前页
            $currentPage = intval(I('p'));
            if(empty($currentPage)){
                $currentPage = 1;
            }
            $service = new ArticleService();
            // 查询满足要求的总记录数
            $artTotalNum = $service->countArticleByCateIdAndAppIdNum(self::ARTICLE_QUESTION, 0);
            if($artTotalNum === false){
                $this->error($service->getFirstError());
            }
            // 实例化分页类 传入总记录数和每页显示的记录数
            $page = new NewPage($artTotalNum,6);
            // 分页显示输出
            $show = $page->show();
            // 进行分页游戏数据查询 注意limit方法的参数要使用Page类的属性
            $articleList = $service->getArticleByCateIdAndAppIdByPage(self::ARTICLE_QUESTION, 0, $page->firstRow, $page->listRows);
            if($articleList === false){
                $this->error($service->getFirstError());
            }
            $this->assign('artTotalNum', $artTotalNum);
            $this->assign('currentPage', $currentPage);
            $this->assign('articleList', $articleList);
            $this->assign('show', $show);
            $html = $this->fetch('ajax_ques_list');
            $this->ajaxReturn($html);
        }
        $this->display();
    }

    /**
     * 资讯，攻略，评测，每日一题列表页右侧公共的礼包
     * ，排行榜，本周专题数据
     * @author xy
     * @since 2017/09/18 14:36
     */
    private function list_left_common(){
        $appService = new AppService();
        $giftService = new GiftService();
        //热门游戏
        $hotAppList = $appService->getIndexHotRecommendAppNameAndIcon(5);
        if($hotAppList === false){
            $this->error($appService->getFirstError());
        }
        //本周专题
        $indexService = new IndexService();
        $thisWeekTopic = $indexService->getThisWeekTopicByKeyword();
        if($thisWeekTopic === false){
            $this->error($indexService->getFirstError());
        }
        //获取热门礼包周榜日榜
        $giftWeekList = $giftService->getHotAppGiftWeekList(10);
        $giftDailyList = $giftService->getHotAppGiftDailyList(10);

        if($giftWeekList === false || $giftDailyList === false){
            $this->error($giftService->getFirstError());
        }
        $this->assign('giftWeekList', $giftWeekList);
        $this->assign('giftDailyList', $giftDailyList);
        $this->assign('thisWeekTopic', $thisWeekTopic);
        $this->assign('hotAppList', $hotAppList);
    }

    /**
     * 游戏攻略详情页
     * @author xy
     * @since 2017/09/19 13:49
     */
    public function strategy_detail(){
        $this->detail_common_content();
        $this->display();
    }

    /**
     * 游戏资讯详情页
     * @author xy
     * @since 2017/09/19 13:49
     */
    public function news_detail(){
        $this->detail_common_content();
        $this->display();
    }

    /**
     * 游戏评测详情页
     * @author xy
     * @since 2017/09/19 13:49
     */
    public function evaluate_detail(){
        $this->detail_common_content();
        $this->display();
    }

    /**
     * 游戏每日一题详情页
     * @author xy
     * @since 2017/09/20 10:50
     */
    public function ques_detail(){
        $this->detail_common_content();
        $this->display();
    }

    /**
     * 获取游戏详情页中的公共的内容
     * @author xy
     * @since 2017/09/19/ 16:18
     */
    private function detail_common_content(){
        $articleId = intval(I('id'));
        if(empty($articleId)){
            $this->error('必填参数缺失');
        }
        $articleService = new ArticleService();
        //1.获取文章详情
        $article = $articleService->getValidArticleDetailByPk($articleId);
        if($article === false){
            $this->error($articleService->getFirstError());
        }
        //文章内容中图片路径的替换
        if(!empty($article['content'])){
            $article['content'] = replace_content_img(htmlspecialchars_decode($article['content']));
        }
        //2.获取精选文章数据 取阅读量最多的几条数据
        $choiceArtList = $articleService->getMostReadArticleListByCateIdAndAppId($article['catid'], $article['app_id'],4);
        if($choiceArtList === false){
            $this->error($articleService->getFirstError());
        }
        //3.相关文章，取同类型下的其他攻略
        $otherArtList = $articleService->getAppArticleByCateIdAndAppId($article['catid'], $article['app_id'],10);
        if($otherArtList === false){
            $this->error($articleService->getFirstError());
        }
        //4.取游戏礼包的日榜周榜数据
        $giftService = new GiftService();
        $giftWeekList = $giftService->getHotAppGiftWeekList(10);
        $giftDailyList = $giftService->getHotAppGiftDailyList(10);
        if($giftWeekList === false || $giftDailyList === false){
            $this->error($giftService->getFirstError());
        }
        //5.取同类型的五款游戏
        $appService = new AppService();
        $recommendAppList = $appService -> getAppsInSameAppTypeByAppId($article['app_id']);
        if($recommendAppList === false){
            $this->error($appService->getFirstError());
        }
        //根据游戏id获取获取游戏信息
        if(!empty($article['app_id'])){
            $appInfo = $appService->getAppDetailInfoByAppId($article['app_id']);
            if($appInfo === false){
                $this->error($appService->getFirstError());
            }
            //获取二维码
            if(!empty($appInfo)){
                $downUrl = C('BASE_URL').U('Home/App/down', array('app_id' => $appInfo['app_id']));
                $logoPath = __DIR__.'/../../../../'.$appInfo['icon'];
                $appInfo['qr_code'] = generate_logo_qr_code($downUrl, $appInfo['app_id'], $logoPath);
            }
            $this->assign('appInfo', $appInfo);
        }

        //获取今天之前的发布的文章
        $beforeArtList = $articleService->getArticleReleaseBeforeTodayByCateIdAndAppId($article['catid'], $article['app_id'],10);
        if($beforeArtList === false){
            $this->error($articleService->getFirstError());
        }

        $this->assign('article', $article);
        $this->assign('choiceArtList', $choiceArtList);
        $this->assign('otherArtList', $otherArtList);
        $this->assign('giftWeekList', $giftWeekList);
        $this->assign('giftDailyList', $giftDailyList);
        $this->assign('recommendAppList', $recommendAppList);
        $this->assign('beforeArtList', $beforeArtList);
    }
}