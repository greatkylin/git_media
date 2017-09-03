<?php
/**
 * 排行榜控制器
 * @author xy
 * @since 2017/07/20 14:40
 */
namespace Admin\Controller;

use Admin\Service\AppService;
use Think\Controller;
use Admin\Controller\AdminBaseController;

class AppRankController extends AdminBaseController
{
    const RANk_TYPE_DOWNLOAD = 0;       //下载榜
    const RANK_TYPE_PAY = 1;            //畅销榜
    const RANK_TYPE_NEW = 2;            //新游榜

    const DATA_SOURCE_WEEK = 1;         //周榜
    const DATA_SOURCE_MONTH = 2;        //月榜
    const DATA_SOURCE_TOTAL = 3;        //总榜

    protected function getRankTypeArr(){
        return array(
            self::RANk_TYPE_DOWNLOAD,
            self::RANK_TYPE_PAY,
            self::RANK_TYPE_NEW,
        );
    }

    /**
     * 榜单数量类型
     * @author xy
     * @since 2017/08/21 18:19
     * @return array
     */
    protected function getDataSourceArr (){
        return array(
            self::DATA_SOURCE_WEEK,
            self::DATA_SOURCE_MONTH,
            self::DATA_SOURCE_TOTAL,
        );
    }

    public function __construct()
    {
        //控制器初始化
        parent::__construct();
    }

    /**
     * 热游榜列表，根据data_source判断是周榜，月榜，总榜
     * @author xy
     * @since 2017/07/20 17:04
     */
    public function download_rank_list() {
        //游戏id或者游戏名称
        $where = array();
        $appIdOrName = I('id_or_name');
        if(!empty($appIdOrName)){
            $where['alib.`app_name`'] = array('like', '%'.$appIdOrName.'%');
            $where['_logic'] = 'OR';
            $where['alist.`app_id`'] = $appIdOrName;
        }
        //媒体站游戏库已上架与未上架的游戏
        $where['alist.is_publish'] = array('IN', array(1, 2));
        //榜单类型 周榜，月榜，总榜
        $dataSource = intval(I('data_source'));
        if(!in_array($dataSource,$this->getDataSourceArr())){
            $this->error('榜单数据类型错误');
        }
        // 翻页
        $page     = intval(I('p'));
        $pageSize = intval(I('pagesize'));
        $pageSize = $pageSize > 0 ? $pageSize : DEFAULT_PAGE_SIZE;     //每页显示条数
        // 获取总条数
        $service = new AppService();
        if($dataSource == self::DATA_SOURCE_WEEK){
            //上周排行榜
            $count = $service->getLastWeekAppDownloadListTotalNum($where);
        }else if($dataSource == self::DATA_SOURCE_MONTH){
            //上月排行榜
            $count = $service->getLastMonthAppDownloadListTotalNum($where);
        }else{
            //总榜
            $count = $service->getAppDownloadListTotalNum($where);
        }
        $totalPages = ceil($count / $pageSize); //总页数
        $page       = $page > $totalPages ? $totalPages : $page;
        $page       = $page > 0 ? $page : 1;
        $currentRow   = $pageSize*($page-1);
        $this->assign('currentRow', $currentRow);
        $this->assign('page',     $page);
        $this->assign('pages',    $totalPages);
        $this->assign('pagesize', $pageSize);

        if($dataSource == self::DATA_SOURCE_WEEK){
            //上周排行榜
            $app_list = $service->getLastWeekAppDownloadListByPage($where, $currentRow, $pageSize);
        }else if($dataSource == self::DATA_SOURCE_MONTH){
            //上月排行榜
            $app_list = $service->getLastMonthAppDownloadListByPage($where, $currentRow, $pageSize);
        }else{
            //总榜
            $app_list = $service->getAppDownloadListByPage($where, $currentRow,$pageSize);
        }
        $this->assign('id_or_name', $appIdOrName);
        $this->assign('app_list', $app_list);
        $this->assign('data_source', $dataSource);
        $this->display();
    }

    /**
     * 畅游榜列表，根据data_source判断是周榜，月榜，总榜
     * @author xy
     * @since 2017/07/20 17:04
     */
    public function popular_rank_list(){
        //游戏id或者游戏名称
        $where = array();
        $appIdOrName = I('id_or_name');
        if(!empty($appIdOrName)){
            $where['alib.`app_name`'] = array('like', '%'.$appIdOrName.'%');
            $where['_logic'] = 'OR';
            $where['alist.`app_id`'] = $appIdOrName;
        }
        //媒体站游戏库已上架与未上架的游戏
        $where['alist.is_publish'] = array('IN', array(1, 2));

        //榜单类型 周榜，月榜，总榜
        $dataSource = intval(I('data_source'));
        if(!in_array($dataSource,$this->getDataSourceArr())){
            $this->error('榜单数据类型错误');
        }
        // 翻页
        $page     = intval(I('p'));
        $pageSize = intval(I('pagesize'));
        $pageSize = $pageSize > 0 ? $pageSize : DEFAULT_PAGE_SIZE;     //每页显示条数
        // 获取总条数
        $service = new AppService();
        if($dataSource == self::DATA_SOURCE_WEEK){
            //上周排行榜
            $count = $service->getLastWeekAppPayListTotalNum($where);
        }else if($dataSource == self::DATA_SOURCE_MONTH){
            //上月排行榜
            $count = $service->getLastMonthAppPayListTotalNum($where);
        }else{
            //总榜
            $count = $service->getAppPayListTotalNum($where);
        }
        $totalPages = ceil($count / $pageSize); //总页数
        $page       = $page > $totalPages ? $totalPages : $page;
        $page       = $page > 0 ? $page : 1;
        $currentRow   = $pageSize*($page-1);
        $this->assign('currentRow', $currentRow);
        $this->assign('page',     $page);
        $this->assign('pages',    $totalPages);
        $this->assign('pagesize', $pageSize);

        if($dataSource == self::DATA_SOURCE_WEEK){
            //上周排行榜
            $app_list = $service->getLastWeekAppPayListByPage($where, $currentRow, $pageSize);
        }else if($dataSource == self::DATA_SOURCE_MONTH){
            //上月排行榜
            $app_list = $service->getLastMonthAppPayListByPage($where, $currentRow, $pageSize);
        }else{
            //总榜
            $app_list = $service->getAppPayListByPage($where, $currentRow,$pageSize);
        }
        $this->assign('id_or_name', $appIdOrName);
        $this->assign('app_list', $app_list);
        $this->assign('data_source', $dataSource);
        $this->display();
    }

    /**
     * 新游榜列表，新游榜只有总榜
     * @author xy
     * @since 2017/07/24 11:44
     */
    public function new_rank_list(){
        //游戏id或者游戏名称
        $where = array();
        $appIdOrName = I('id_or_name');
        if(!empty($appIdOrName)){
            $where['alib.`app_name`'] = array('like', '%'.$appIdOrName.'%');
            $where['_logic'] = 'OR';
            $where['alist.`app_id`'] = $appIdOrName;
        }
        //媒体站游戏库已上架与未上架的游戏
        $where['alist.is_publish'] = array('IN', array(1, 2));
        //榜单类型总榜
        $dataSource = self::DATA_SOURCE_TOTAL;
        // 翻页
        $page     = intval(I('p'));
        $pageSize = intval(I('pagesize'));
        $pageSize = $pageSize > 0 ? $pageSize : DEFAULT_PAGE_SIZE;     //每页显示条数
        // 获取总榜总条数
        $service = new AppService();
        $count = $service->getAppNewListTotalNum($where);

        $totalPages = ceil($count / $pageSize); //总页数
        $page       = $page > $totalPages ? $totalPages : $page;
        $page       = $page > 0 ? $page : 1;
        $currentRow   = $pageSize*($page-1);
        $this->assign('currentRow', $currentRow);
        $this->assign('page',     $page);
        $this->assign('pages',    $totalPages);
        $this->assign('pagesize', $pageSize);

        //总榜
        $app_list = $service->getAppNewListByPage($where, $currentRow,$pageSize);

        $this->assign('id_or_name', $appIdOrName);
        $this->assign('app_list', $app_list);
        $this->assign('data_source', $dataSource);
        $this->display();
    }

    /**
     * 编辑游戏列表排序
     * @author xy
     * @since 2017/07/04 11:17
     */
    public function rank_sort_edit(){
        //游戏id
        $appId = intval(I('app_id'));
        //列表类型 0表示下载榜单，1表示畅销榜，2表示新游榜
        $rankType = intval(I('rank_type'));
        //排行表id
        $rankId = intval(I('id'));
        //榜单类型 ，1周榜，2月榜，3总榜
        $dataSource = intval(I('data_source'));
        if(IS_AJAX){
            if(empty($appId) || !is_numeric($appId)){
                $this->outputJSON(true,'100001','游戏id参数错误');
            }
            if(!in_array($rankType, $this->getRankTypeArr())){
                $this->outputJSON(true,'100001','榜单类型错误');
            }
            $targetSort = intval(I('pre_sort'));
            if(empty($targetSort) || !is_numeric($targetSort)){
                $this->outputJSON(true,'100001','排序参数错误');
            }
            if(!in_array($dataSource, $this->getDataSourceArr())){
                $this->outputJSON(true,'100001','榜单类型参数错误');
            }
            //判断目标排序是否已存在

            $where['ranking_type'] = $rankType;
            $where['data_source'] = $dataSource;
            $isExist = M('app_ranking')->where('pre_sort = '.$targetSort.' AND ranking_type = '.$rankType.' AND data_source = '.$dataSource)->count();

            //判断id为$rankId的数据是否存在，存在则更新相应数据的排序，不存在则添加数据
            if(!empty($rankId)){
                $rankData = M('app_ranking')->where('id = '.$rankId)->find();
                if(!empty($rankData)){
                    $fromSort = intval($rankData['pre_sort']);
                    $updateData['pre_sort'] = $targetSort;
                    M()->startTrans();
                    $result = M('app_ranking')->where('id = '.$rankId)->save($updateData);
                    if($result === false){
                        M()->rollback();
                    }else{
                        if($isExist){
                            //排序大于等于目标排序的排序,且id不等于$rankId的数据自增1,
                            if($fromSort <= 0){
                                $where['_string'] = '`pre_sort` >= '.$targetSort.' AND `id` <> '.$rankId;
                                $autoResult = M('app_ranking')->where($where)->setInc('pre_sort',1);
                            }else{
                                if($fromSort < $targetSort){
                                    //原排序小于目标排序,自减1
                                    $where['_string'] = '`pre_sort` >= '.$fromSort.' AND `pre_sort` <= '.$targetSort.' AND `id` <> '.$rankId;
                                    $autoResult = M('app_ranking')->where($where)->setDec('pre_sort',1);
                                }else if($fromSort > $targetSort){
                                    //原排序大于目标排序,自增1
                                    $where['_string'] = '`pre_sort` >= '.$targetSort.' AND `pre_sort` <= '.$fromSort.' AND `id` <> '.$rankId;
                                    $autoResult = M('app_ranking')->where($where)->setInc('pre_sort',1);
                                }else{
                                    $this->outputJSON(true,'100001','目标排序与原排序相同，无需修改');
                                    return false;
                                }
                            }
                            if($autoResult === false){
                                M()->rollback();
                                $this->outputJSON(true,'100001',D('app_ranking')->getError());
                            }
                        }
                        M()->commit();
                        $this->outputJSON(false,'000000','更新成功');
                    }
                }
            }
            $insertData['app_id'] = $appId;
            $insertData['ranking_type'] = $rankType;
            $insertData['pre_sort'] = $targetSort;
            $insertData['create_time'] = time();
            $insertData['data_source'] = $dataSource;
            $insertData['admin_id'] = $this->user_info['id'];
            M()->startTrans();
            $result = M('app_ranking')->add($insertData);
            if($result === false){
                $this->outputJSON(true,'100001',D('app_ranking')->getError());
            }

            //排序大于等于目标排序的排序自增1
            if($isExist){
                $where['_string'] = '`pre_sort` >= '.$targetSort.' AND `id` <> '.$result;
                $autoResult = M('app_ranking')->where($where)->setInc('pre_sort',1);

                if($autoResult === false){
                    M()->rollback();
                    $this->outputJSON(true,'100001',M('app_ranking')->getError());
                }
            }

            M()->commit();
            $this->outputJSON(false,'000000','添加成功');
        }else{
            if(empty($appId) || !is_numeric($appId)){
                $this->error('游戏id参数错误');
                exit;
            }
            if(!in_array($rankType, $this->getRankTypeArr())){
                $this->error('榜单类型错误');
                exit;
            }
            //$randId存在则获取对应的排行榜数据
            if(!empty($rankId)){
                $rankData = M('app_ranking')->where('id = '.$rankId)->find();
                $this->assign('rank_data',$rankData);
            }


            $this->assign('app_id',$appId);
            $this->assign('rank_type',$rankType);
            $this->assign('data_source',$dataSource);
            $this->display('rank_sort_edit');
        }
    }

    /**
     * ajax方式生成前台列表排序
     * @author xy
     * @since 2017/07/24 09:57
     */
    public function ajax_generate_list(){
        $rankType = intval(I('rank_type'));
        $dataSource = intval(I('data_source'));
        if(!in_array($rankType, $this->getRankTypeArr())){
            $this->outputJSON(true,'100001','榜单类型错误');
        }
        if(!in_array($dataSource, $this->getDataSourceArr())){
            $this->outputJSON(true,'100001','榜单类型错误');
        }
        //更新前台列表的排序
        $service = new AppService();
        $result = $service->updateAppRankFinalSortByType($rankType,$dataSource);
        if($result){
            $this->outputJSON(false,'000000','更新成功');
        }else{
            $this->outputJSON(true,'100001',$service->getFirstError());
        }
    }

    /**
     * 恢复榜单的默认排序
     * @author xy
     * @since 2017/08/24 10:27
     */
    public function recover_default_sort(){
        //排行榜类型 0 热游榜，1畅销榜，2新游榜
        $rankType = intval(I('rank_type'));
        //数据来源 1 周榜，2月榜，3总榜
        $dataSource = intval(I('data_source'));
        if(!in_array($rankType, $this->getRankTypeArr())){
            $this->outputJSON(true, '100001', '榜单类型错误');
        }
        if(!in_array($dataSource, $this->getDataSourceArr())){
            $this->outputJSON(true, '100001', '榜单数据类型错误');
        }
        if(($dataSource == self::DATA_SOURCE_WEEK || $dataSource == self::DATA_SOURCE_MONTH) && $rankType == self::RANK_TYPE_NEW) {
            $this->outputJSON(true, '100001', '新游榜没有周榜与月榜');
        }
        $appId = intval(I('app_id'));
        if(empty($appId)){
            $this->outputJSON(true, '100001', '对应的游戏不存在');
        }

        $where = array(
            'app_id' => $appId,
            'ranking_type' => $rankType,
            'data_source' => $dataSource,
        );

        $rankId = intval(I('rank_id'));
        if(!empty($rankId)){
            $where['id'] = $rankId;
        }
        $data = array(
            'pre_sort' => 0,
            'final_sort' => 0,
            'admin_id' => $this->user_info['id'],
        );
        $result = M('app_ranking')->where($where)->save($data);
        //echo M('app_ranking')->getLastSql();
        if($result === false){
            $this->outputJSON(true, '100001', '恢复默认排序失败');
        }
        if($result === 0){
            $this->outputJSON(true, '100001', '该数据已是默认排序无需修改');
        }
        $this->outputJSON(false, '000000', '恢复默认排序成功');
    }
}
