<?php
/**
 * 游戏库控制器
 * @author: xy
 * @since: 2017/06/29 10:40
 */

namespace Admin\Controller;

use Admin\Service\AppService;
use Admin\Service\GiftService;
use Think\Controller;
use Admin\Controller\AdminBaseController;

class AppLibController extends AdminBaseController
{
    const SHELVE_GAME = 'SHELVE_GAME';      //游戏发布操作
    const UN_SHELVE_GAME = 'UN_SHELVE_GAME';  //游戏下架操作

    public function __construct()
    {
        //控制器初始化
        parent::__construct();
    }

    /**
     * 从指娱总库同步游戏列表到媒体站app_list
     * @author xy
     * @since 2017/06/29 13:33
     */
    public function ajax_sync_app_list_data(){
        //同步app_list表
        $service = new AppService();
        $result = $service->insertDataIntoMediaAppList();
        if($result){
            $this->outputJSON(false,'0000000','成功同步数据');
        }else{
            $errorMsg = $service->getFirstError();
            $this->outputJSON(true,'100001',empty($errorMsg)?'未知错误':$errorMsg);
        }
    }

    /**
     * 游戏库人气热门或近期更新列表管理
     * @author xy
     * @since 2017/07/03 17:30
     */
    public function app_list() {
        $listType = intval(I('type')); //1人气排行，2近期更新
        if(!in_array($listType,array(1,2))){
            $listType = 1;
        }
        $where = array();
        $idOrName = I('id_or_name');
        if(!empty($idOrName)){
            $where['_string'] = 'alist.app_id = \''.$idOrName.'\' OR alib.app_name like \'%'.$idOrName.'%\' OR lib.app_name like \'%'.$idOrName.'%\'';
        }
        $isPublish = intval(I('is_publish'));
        if(!empty($isPublish)){
            //根据上架状态进行查询
            $where['alist.is_publish'] = array('IN', array($isPublish));
        }else{
            //媒体站已上架和未上架的游戏
            $where['alist.is_publish'] = array('IN', array(1, 2));
        }

        // 分页
        $service = new AppService();
        $totalCount = $service->getAppListFromMediaTotalCount($where); //获取总条数
        $page     = intval(I('p'));
        $pageSize = intval(I('pagesize'));
        $pageSize = $pageSize > 0 ? $pageSize : DEFAULT_PAGE_SIZE; //每页显示条数
        $totalPages = ceil($totalCount / $pageSize);        //总页数
        $page       = $page > $totalPages ? $totalPages : $page;
        $page       = $page > 0 ? $page : 1;
        $currentPage   = $pageSize * ($page-1);
        $this->assign('firstRow', $currentPage);
        $this->assign('page',     $page);
        $this->assign('pages',    $totalPages);
        $this->assign('pagesize', $pageSize);
        //根据游戏列表的类型获取游戏列表
        $appList = $service-> getAppSortListFromMediaByPage($listType, $currentPage, $pageSize, $where);
        //var_dump($appList);die;
        $this->assign('idOrName',$idOrName);
        $this->assign('isPublish', $isPublish);
        $this->assign('list_type',$listType);
        $this->assign('app_list', $appList);
        $this->display('app_list');
    }

    /**
     * 编辑游戏列表排序
     * @author xy
     * @since 2017/07/04 11:17
     */
    public function app_sort_edit(){
        $listType = intval(I('type')); //列表类型 1 人气最高，2近期更新
        if(!in_array($listType,array(1,2))){
            $listType = 1;
        }
        $listId = intval(I('list_id'));
        $service = new AppService();
        if(IS_AJAX){
            if(empty($listId) || !is_numeric($listId)){
                $this->outputJSON(true,'100001','id参数错误');
            }
            $targetPreSort = intval(I('pre_sort'));
            if(empty($targetPreSort) || !is_numeric($targetPreSort)){
                $this->outputJSON(true,'100001','排序参数错误');
            }
            $appData = $service->loadAppListByPk($listId);
            if(!$appData){
                $this->outputJSON(true,'100001','id为'.$listId.'的数据不存在,请先同步数据');
            }
            if($listType == 1){
                $fromPreSort = $appData['pre_hot_sort'];
                $updateData = array('pre_hot_sort'=>$targetPreSort,'edit_time'=>time());
            }else{
                $fromPreSort = $appData['pre_new_sort'];
                $updateData = array('pre_new_sort'=>$targetPreSort,'edit_time'=>time());
            }

            //判断id为$list_id的数据是否存在，并更新相应数据的排序
            $service = new AppService();
            $result = $service->updateMediaAppListSortByPk($listId, $updateData);
            if($result === false){
                $this->outputJSON(true,'100001',$service->getFirstError());
            }
            //修改排序n后排在n后的排序变成n+1
            $result = $service->autoUpdateOtherMediaAppSort($listId, $listType, $fromPreSort, $targetPreSort);
            if($result === false){
                $this->outputJSON(true,'100001',$service->getFirstError());
            }
            $this->outputJSON(false,'000000','更新成功');
        }else{
            if(empty($listId) || !is_numeric($listId)){
                $this->error('id参数错误');
                exit;
            }
            //根据id获取媒体站app_list表的数据
            $appData = $service->loadAppListByPk($listId);
            if(!$appData){
                $this->error('id为'.$listId.'的数据不存在');
                exit;
            }
            $this->assign('list_type',$listType);
            $this->assign('app_data',$appData);
            $this->display('app_sort_edit');
        }
    }

    /**
     * 生成前台列表排序
     * @author xy
     * @since 2017/07/04 16:16
     */
    public function ajax_generate_list(){
        $listType = intval(I('type')); //列表类型 1 人气最高，2近期更新
        if(!in_array($listType, array(1, 2))){
            $listType = 1;
        }
        //更新列表的最终排序
        $service = new AppService();
        $result = $appData = $service->updateAppListFinalSortByType($listType);
        if($result === false){
            $this->outputJSON(true,'100001',$service->getFirstError());
        }
        $this->outputJSON(false,'000000','更新列表成功');
    }

    /**
     * 预览游戏列表
     * @author xy
     * @since 2017/08/23 15:06
     */
    public function preview(){
        $listType = intval(I('type')); //列表类型 1 人气最高，2近期更新
        if(!in_array($listType,array(1,2))){
            $listType = 1;
        }
        // TODO 需要前端页面来展示预览页面
    }

    /**
     * 游戏库详情页列表管理
     * @author xy
     * @since 2017/07/04 17:14
     */
    public function applib_list(){
        $where = array();
        //判读是否根据游戏的发布状态排序
        $statusOrder = intval(I('status_order'));
        if(empty($statusOrder)){
            $statusOrder = 0;
        }
        $orderBy = '';
        if($statusOrder == 1){
            $orderBy = 'alist.is_publish ASC';
        }else if($statusOrder == 2){
            $orderBy = 'alist.is_publish DESC';
        }
        //判断是否有通过游戏名称搜索游戏
        $appName = trim(I('app_name'));
        if(!empty($appName)){
            $where['alib.app_name'] = array('like', '%'.$appName.'%');
            $where['lib.app_name'] = array('like', '%'.$appName.'%');
        }
        //媒体站已上架和未上架的游戏
        $where['alist.is_publish'] = array('IN', array(1, 2));

        // 分页
        $service = new AppService();
        $totalCount = $service->getAppListFromMediaTotalCount($where); //获取总条数
        $page     = intval(I('p'));
        $pageSize = intval(I('pagesize'));
        $pageSize = $pageSize > 0 ? $pageSize : DEFAULT_PAGE_SIZE; //每页显示条数
        $totalPages = ceil($totalCount / $pageSize);        //总页数
        $page       = $page > $totalPages ? $totalPages : $page;
        $page       = $page > 0 ? $page : 1;
        $currentPage   = $pageSize * ($page-1);
        $this->assign('firstRow', $currentPage);
        $this->assign('page',     $page);
        $this->assign('pages',    $totalPages);
        $this->assign('pagesize', $pageSize);
        //根据游戏详情创建时间正序排序获取列表
        $appList = $service->getAppLibDetailPageList($where,$currentPage,$pageSize,$orderBy);
        //var_dump($appList);die;
        $this->assign('app_name', $appName);
        $this->assign('status_order', $statusOrder);
        $this->assign('app_list', $appList);
        $this->display('applib_list');
    }

    /**
     * 游戏详情页编辑
     * @author xy
     * @since 2017/07/05 11:35
     */
    public function applib_edit(){
        $appId = intval(I('app_id')); //游戏库表id
        //获取游戏详情
        $service = new AppService();
        $appInfo = $service->getAppLibDataFromMediaByPk($appId);
        $appGuideList = $service->getAppGuideByAppId($appId);
        if(IS_AJAX){
            if(empty($appId) || !is_numeric($appId)){
                $this->outputJSON(true,'100001','app_id参数错误');
            }
            if($appGuideList === false){
                $this->outputJSON(true,'100001','获取游戏图文攻略失败');
            }
            $data['app_id'] = $appId;
            $appName = trim(I('app_name'));
            if(empty($appName)){
                $this->outputJSON(true,'100001','游戏名称不能为空');
            }
            $data['app_name'] = $appName;
            $data['short_name'] = trim(I('short_name'));
            $data['supplier_id'] = intval(I('supplier_id'));
            //$data['star_rank'] = empty(trim(I('star_rank'))) ? '0' : trim(I('star_rank'));//游戏星级
            $data['start_score'] = empty(trim(I('start_score'))) ? '' : trim(I('start_score'));//游戏评分
            $platform = trim(I('platform'));
            if(!in_array($platform,array(1,2,3))){
                $this->outputJSON(true,'100001','游戏平台错误');
            }
            $data['platform'] = $platform;
            $data['app_type'] = trim(I('app_type'));
            $data['app_type1'] = trim(I('app_type1'));
            $data['app_type2'] = trim(I('app_type2'));
            $data['app_size'] = trim(I('app_size'));
            $data['version_code'] = trim(I('version_code'));
            $data['version_name'] = trim(I('version_name'));
            $data['about'] = trim(I('about'));
            $data['introduct'] = trim(I('introduct'));
            $data['is_landscape'] = trim(I('is_landscape'));

            $data['update_time'] = !empty(trim(I('update_time'))) ? strtotime(trim(I('update_time'))): time();
            $data['create_time'] = !empty(trim(I('create_time'))) ? trim(I('create_time')) : time();

            //游戏评论
            $data['game_quality'] = !empty(trim(I('game_quality')))?trim(I('game_quality')):'游戏品质';
            $data['game_picture'] = !empty(trim(I('game_picture')))?trim(I('game_picture')):'游戏画质';
            $data['game_gandu'] = !empty(trim(I('game_gandu')))?trim(I('game_gandu')):'游戏肝度';
            $data['game_diff'] = !empty(trim(I('game_diff')))?trim(I('game_diff')):'游戏难度';
            $data['game_quality_value'] = !empty(trim(I('game_quality_value')))?trim(I('game_quality_value')):0;
            $data['game_picture_value'] = !empty(trim(I('game_picture_value')))?trim(I('game_picture_value')):0;
            $data['game_gandu_value'] = !empty(trim(I('game_gandu_value')))?trim(I('game_gandu_value')):0;
            $data['game_diff_value'] = !empty(trim(I('game_diff_value')))?trim(I('game_diff_value')):0;
            //游戏icon
            $icon =  trim(I('img_url_icon'));
            if(!empty($icon)){
                $data['icon'] = $icon;
            }
            //游戏弹窗及链接
            $data['video_link'] = trim(I('video_link'));
            $coverImg = trim(I('img_url_video'));
            if(!empty($coverImg)){
                $data['cover_img'] = $coverImg;
            }
            //封面截图
            $picUrl = I('pic_url');
            $delPicUrl = I('pic_url_del');
            if(!empty($appInfo['pic_url'])){
                $libPicUrl = explode(',', $appInfo['pic_url']);
                $delPicUrlArr = array();
                if(!empty($delPicUrl)) {
                    // 删除已经添加都数据库的图片（上传成功后替换的不给删除）
                    if(!empty($libPicUrl)) {
                        foreach($delPicUrl as $dVal) {
                            if(in_array($dVal, $libPicUrl)) {
                                $delPicUrlArr[] = $dVal;
                            }
                        }
                    }

                }
                // 将原有的图片添加进去
                if(!empty($libPicUrl)) {
                    foreach ($libPicUrl as $lVal) {
                        if(!in_array($lVal, $delPicUrlArr)) {
                            $picUrl[] = $lVal;
                        }
                    }
                }
            }
            $data['pic_url'] = implode(',',$picUrl);
            //图文攻略图片以及链接
            $guideData = array();
            $guideLinkArr = I('guide_link');
            $guideImageArr = I('img_url_guide');

            foreach ($guideLinkArr as $key=>$link){
                $guideData[$key]['app_id'] = $appId;
                $guideData[$key]['type'] = 0;
                if(!empty($link)){
                    $dotPos = strrpos($link, ".");
                    if($dotPos !== false){
                        $ext = strtolower(substr($link,$dotPos+1));
                        if(in_array($ext,C('VIDEO_TYPE'))){
                            $guideData[$key]['type'] = 1;
                        }
                    }
                    $guideData[$key]['guide_link'] = $link;
                }else{
                    if($appGuideList!==NULL){
                        $guideData[$key]['guide_link'] = $appGuideList[$key+1]['guide_link'];
                    }else{
                        $guideData[$key]['guide_link'] = '';
                    }
                }
                if(!empty($guideImageArr[$key])){
                    $guideData[$key]['guide_image'] = $guideImageArr[$key];
                }else{
                    if($appGuideList!==NULL){
                        $guideData[$key]['guide_image'] = $appGuideList[$key+1]['guide_image'];
                    }else{
                        $guideData[$key]['guide_image'] = '';
                    }
                }
                $guideData[$key]['sort'] = $key+1;
                $guideData[$key]['create_time'] = time();
            }
            //是否更新游戏发布状态
            $publish = empty(trim(I('app_list_status')))?'0':trim(I('app_list_status'));
            $result = $service->saveAppLibDetainInfo($appId, $data, $guideData, $publish);
            if($result === false){
                //echo $service->getFirstError();
                $this->outputJSON(true,'100001', $service->getFirstError());
            }else{
                //删除原来的图片
                if($icon != $appInfo['icon'] && !empty($icon)) {
                    unlink(realpath($appInfo['icon']));
                }
                if($coverImg != $appInfo['cover_img'] && !empty($cover_img)) {
                    unlink(realpath($appInfo['cover_img']));
                }
                if(!empty($delPicUrlArr)) {
                    foreach ($delPicUrlArr as $dVal) {
                        unlink(realpath($dVal));
                    }
                }
                //保存礼包简介与礼包上限数量
                $giftDetailArr = I('gift_detail');
                $giftLimitNum = I('limited_count');

                if(!empty($giftLimitNum)){
                    M()->startTrans();
                    foreach($giftLimitNum as $giftId => $limitNum){
                        if(!empty($limitNum)){
                            $data = array(
                                'limited_count' => $limitNum,
                                'gift_detail' => trim($giftDetailArr[$giftId]),
                                'edit_time' => time()
                            );
                            if(!empty(M('sync_gift_lib')->where('gift_id = '.$giftId )->find())){
                                $result = M('sync_gift_lib')->where('gift_id = '.$giftId )->save($data);
                            }else{
                                $data['gift_id'] = $giftId;
                                $result = M('sync_gift_lib')->where('gift_id = '.$giftId )->add($data);
                            }
                            if($result === false){
                                M()->rollback();
                                $this->outputJSON(false,'000000','保存游戏信息成功，设置礼包id为'.$giftId.'的礼包上限数量失败');
                            }else{
                                //媒体站记录礼包动态
                                $optData = array(
                                    'gift_id' => $giftId,
                                    'opt_count' => 0,
                                    'opt_type' => 0, //申请礼包
                                    'admin_id' => $this->user_info['id'],
                                    'remark' => '设置礼包上限数量为' . $limitNum,
                                    'create_time' => time(),
                                );
                                $result = M('gift_opt_record')->add($optData);
                                if($result === false){
                                    M()->rollback();
                                    $this->outputJSON(false,'000000','保存游戏信息成功，保存礼包id为'.$giftId.'的操作记录失败');
                                }
                                M()->commit();
                            }
                        }
                    }
                }
                $this->outputJSON(false,'000000','保存信息成功');
            }
        }else{
            if(empty($appId) || !is_numeric($appId)){
                $this->error('app_id参数错误');
            }
            //获取游戏类型
            $appTypeList =  $service->getAppTypeFromZhiYu();
            if(empty($appTypeList)) {
                $this->error('获取游戏类型失败');
                exit;
            }
            //定义游戏星级
            //$starRank = array(1,2,3,4,5);
            //游戏截图的数量 默认0，
            $picNum = 0;
            if(isset($appInfo['pic_url'])){
                $picUrl = explode(',', $appInfo['pic_url']);
                $picNum = count($picUrl);
                $this->assign('pic_url', $picUrl);
            }
            if($appGuideList === false){
                $this->outputJSON(true,'100001','获取游戏图文攻略失败');
                $this->error('获取游戏图文攻略失败');
                exit;
            }
            if(empty($appInfo['update_time'])){
                $appInfo['update_time'] = time();
            }
            //游戏类型
            if(!empty($appInfo['app_type'])){
                $this->assign('appTypeIdArray',$appInfo['app_type']);

            }

            $giftService = new GiftService();
            $giftList = $giftService->getAppGiftByAppId($appId);
            //$giftList = $giftService->getSetUponNumGiftListByAppId($appId);
            $this->assign('gift_list',$giftList);

            $this->assign('app_id',$appId);
            $this->assign('app_type_list',$appTypeList);
            //$this->assign('star_rank',$starRank);
            $this->assign('pic_num',$picNum);
            $this->assign('app_guide_list',$appGuideList);
            $this->assign('app_info',$appInfo);
            $this->assign('reload_url', U('Admin/AppLib/applib_edit',array('app_id'=>$appId)));
            $this->display();
        }

    }

    /**
     * ajax方法根据app_id从指娱拉取app的数据
     * @author xy
     * @since 2017/07/05 18:22
     */
    public function ajax_get_app_data_from_zhiyu(){
        $appId = intval(I('app_id'));
        if(empty($appId) || !is_numeric($appId)){
            $this->outputJSON(true,'100001','app_id参数错误');
        }
        //判读媒体站的app_list表是否存在app_id 为$appId的数据
        $service = new AppService();
        $isExist = $service->isMediaAppListDataExistByAppId($appId);

        if(empty($isExist)){
            $this->outputJSON(true,'100001','媒体站不存在应用id为'.$appId.'的数据，请先同步列表数据');
        }
        //从指娱总库app_lib获取具体的数据
        $appLibData = $service->getAppLibInfoFromZhiYuByPk($appId);
        if(!$appLibData){
            $this->outputJSON(true,'100001',$service->getFirstError());
        }
        $appLibData['about'] = htmlspecialchars_decode($appLibData['about']);
        //获取礼包的信息
        $giftService = new GiftService();
        $giftList = $giftService->getAppGiftByAppId($appId);
        $returnData = array(
            'app_info' => $appLibData,
            'gift_info' => $giftList,
        );
        $this->outputJSON(false,'000000','成功获取数据',$returnData);
    }

    /**
     * ajax游戏发布操作 此方法弃用，since 2017/08/24 17:49 xy
     * @author xy
     * @since 2017/07/05
     */
    public function ajax_shelve_game(){
        $listId = intval(I('list_id')); //app_list表id
        if(empty($listId)){
            $this->outputJSON(true,'100001','列表id不能为空');
        }
        $service = new AppService();
        $result = $service->mediaAppShelveOption($listId,self::SHELVE_GAME);
        if($result === false){
            $this->outputJSON(true,'100001',$service->getFirstError());
        }
        $this->outputJSON(false,'000000','游戏发布成功');
    }

    /**
     * ajax通过游戏名称获取游戏app列表
     * @author xy
     * @since 2017/07/10 18:00
     */
    public function ajax_get_app(){
        $service = new AppService();
        $appId = trim(I('app_id'));
        $appList = $service->getAppInfoByAppId($appId);
        if($appList === false){
            $this->outputJSON(true,'100001',$service->getError());
        }
        if($appList === NULL){
            $this->outputJSON(true,'100001','未找到对应数据');
        }
        $this->outputJSON(false,'000000','成功',$appList);
    }

    /**
     * ajax通过游戏名称获取游戏app列表
     * @author xy
     * @since 2017/07/10 18:00
     */
    public function ajax_get_all_app(){
        $service = new AppService();
        $appId = trim(I('app_id'));
        $appList = $service->getAllAppInfoByAppId($appId);
        if($appList === false){
            $this->outputJSON(true,'100001',$service->getError());
        }
        if($appList === NULL){
            $this->outputJSON(true,'100001','未找到对应数据');
        }
        $this->outputJSON(false,'000000','成功',$appList);
    }

    /**
     * ajax游戏下架操作 此方法弃用，since 2017/08/24 17:49 xy
     * @author xy
     * @since 2017/07/05 10:25
     */
    public function ajax_unshelve_game(){
        $listId = intval(I('list_id')); //app_list表id
        if(empty($listId)){
            $this->outputJSON(true,'100001','列表id不能为空');
        }
        $service = new AppService();
        $result = $service->mediaAppShelveOption($listId,self::UN_SHELVE_GAME);
        if($result === false){
            $this->outputJSON(true,'100001',$service->getFirstError());
        }
        $this->outputJSON(false,'000000','游戏下架成功');
    }

    /**
     * ajax获取应用厂商数据
     * @author xy
     * @since2017/08/09 17:39
     */
    public function ajax_get_supplier(){
        $supplierId = intval(I('supplier_id'));
        $service = new AppService();
        $supplierList = $service->getSupplierInfoFromZhiYu($supplierId);
        if(empty($supplierList)){
            $this->outputJSON(true,'100001',$service->getFirstError());
        }
        $this->outputJSON(false,'000000','获取成功', $supplierList);
    }

    /**
     * ajax获取游戏分类
     * @author xy
     * @since2017/08/09 17:39
     */
    public function ajax_get_app_type_tree(){
        $service = new AppService();
        $typeList = $service->getAppTypeFromZhiYu();
        if(empty($typeList)){
            $this->outputJSON(true,'100001',$service->getFirstError());
        }
        $this->outputJSON(false,'000000','获取成功', $typeList);
    }

    /**
     * ajax恢复默认排序
     * @author xy
     * @since 2017/08/10 15:38
     */
    public function ajax_recover_sort(){
        //列表类型 1人气最高 2近期更新
        $listType = intval(I('list_type'));
        if(!in_array($listType, array(1, 2))){
            $this->outputJSON(true,'100001','列表类型参数错误');
        }
        $listId = intval(I('list_id'));

        if($listType == 1){
            $data['pre_hot_sort'] = 0;
            $data['final_hot_sort'] = 0;
        }else{
            $data['pre_new_sort'] = 0;
            $data['final_new_sort'] = 0;
        }
        $result = M('app_list')->where('id = '.$listId)->save($data);
        if($result){
            $this->outputJSON(false,'000000','更新成功');
        }else{
            $this->outputJSON(true,'100001','更新失败');
        }
    }

}