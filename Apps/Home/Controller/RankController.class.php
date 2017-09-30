<?php
/**
 * 游戏排行榜
 * User: xy
 * Date: 2017/9/25
 * Time: 10:19
 */

namespace Home\Controller;


use Home\Service\AppService;

class RankController extends HomeBaseController
{
    /**
     * 游戏库排行榜列表
     * @author xy
     * @since 2017/09/12 13:58
     */
    public function app_rank(){
        $appService = new AppService();
        //1.获取游戏的热游榜 周榜，月榜，总榜
        $hotAppListWeek = $appService->getHotAppRankWeek(16);
        $hotAppListMonth = $appService->getHotAppRankMonth(16);
        $hotAppListTotal = $appService->getHotAppRankTotal(16);
        if($hotAppListWeek === false || $hotAppListMonth === false || $hotAppListTotal === false){
            $this->error($appService->getFirstError());
        }
        //2.获取游戏畅游榜 周榜，月榜，总榜
        $popularAppListWeek = $appService->getPopularAppRankWeek(16);
        $popularAppListMonth = $appService->getPopularAppRankMonth(16);
        $popularAppListTotal = $appService->getPopularAppRankTotal(16);
        if($popularAppListWeek === false || $popularAppListMonth === false || $popularAppListTotal === false){
            $this->error($appService->getFirstError());
        }
        //3.获取游戏新游榜总榜
        $newAppListTotal = $appService->getNewAppRankTotal(16);
        if($newAppListTotal === false){
            $this->error($appService->getFirstError());
        }

        $this->assign('hotAppListWeek', $hotAppListWeek);
        $this->assign('hotAppListMonth', $hotAppListMonth);
        $this->assign('hotAppListTotal', $hotAppListTotal);
        $this->assign('popularAppListWeek', $popularAppListWeek);
        $this->assign('popularAppListMonth', $popularAppListMonth);
        $this->assign('popularAppListTotal', $popularAppListTotal);
        $this->assign('newAppListTotal', $newAppListTotal);

        $this->display();
    }

    /**
     * 排行榜预览
     * @author xy
     * @since 2017/09/26 14:29
     */
    public function preview(){
        $appService = new AppService();
        //1.获取游戏的热游榜 周榜，月榜，总榜
        $hotAppListWeek = $appService->getHotAppRankWeek(16, true);
        $hotAppListMonth = $appService->getHotAppRankMonth(16, true);
        $hotAppListTotal = $appService->getHotAppRankTotal(16, true);
        if($hotAppListWeek === false || $hotAppListMonth === false || $hotAppListTotal === false){
            $this->error($appService->getFirstError());
        }
        //2.获取游戏畅游榜 周榜，月榜，总榜
        $popularAppListWeek = $appService->getPopularAppRankWeek(16, true);
        $popularAppListMonth = $appService->getPopularAppRankMonth(16, true);
        $popularAppListTotal = $appService->getPopularAppRankTotal(16, true);
        if($popularAppListWeek === false || $popularAppListMonth === false || $popularAppListTotal === false){
            $this->error($appService->getFirstError());
        }
        //3.获取游戏新游榜总榜
        $newAppListTotal = $appService->getNewAppRankTotal(16, true);
        if($newAppListTotal === false){
            $this->error($appService->getFirstError());
        }

        $this->assign('hotAppListWeek', $hotAppListWeek);
        $this->assign('hotAppListMonth', $hotAppListMonth);
        $this->assign('hotAppListTotal', $hotAppListTotal);
        $this->assign('popularAppListWeek', $popularAppListWeek);
        $this->assign('popularAppListMonth', $popularAppListMonth);
        $this->assign('popularAppListTotal', $popularAppListTotal);
        $this->assign('newAppListTotal', $newAppListTotal);

        $this->display('app_rank');
    }
}