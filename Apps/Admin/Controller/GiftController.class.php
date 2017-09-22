<?php
/**
 * 礼包中心管理后台
 * User: xy
 * Date: 2017/8/8
 * Time: 14:11
 */

namespace Admin\Controller;

use Admin\Service\GiftService;
use Admin\Service\SlideService;
use Think\Controller;
use Admin\Controller\AdminBaseController;

class GiftController extends AdminBaseController
{
    /**
     * 礼包中心列表
     * @author xy
     * @since 2017/08/08 14:13
     */
    public function gift_list(){
        $listType = intval(I('list_type')); //热门礼包，2新游礼包
        if(!in_array($listType,array(1,2))){
            $listType = 1;
        }
        $where = array();
        $idOrName = I('id_or_name');
        if(!empty($idOrName)){
            $where['_string'] = '( gl.gift_id = \''.$idOrName.'\' OR CONCAT(al.app_name, \'-\',gl.gift_name , \'-\', gl.original_name ) like \'%'.$idOrName.'%\' )';
        }

        // 分页
        $service = new GiftService();

        $totalCount = $service->getGiftInfoListTotalNum($where); //获取总条数
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
        $giftList = $service->getGiftInfoListByPage($listType, $currentPage, $pageSize, $where);

        $this->assign('idOrName',$idOrName);
        $this->assign('listType',$listType);
        $this->assign('giftList', $giftList);
        $this->display('gift_list');

    }

    /**
     * 设置列表的排序
     * @author xy
     * @since 2017/08/10 15:52
     */
    public function gift_sort_edit(){
        $listType = intval(I('list_type')); //列表类型 1 热门礼包，2新游礼包
        if(!in_array($listType,array(1,2))){
            $listType = 1;
        }
        $giftId = intval(I('gift_id'));
        $service = new GiftService();
        if(IS_AJAX){
            if(empty($giftId) || !is_numeric($giftId)){
                $this->outputJSON(true,'100001','id参数错误');
            }
            $targetPreSort = intval(I('pre_sort'));
            if(empty($targetPreSort) || !is_numeric($targetPreSort)){
                $this->outputJSON(true,'100001','排序参数错误');
            }
            $giftData = $service->loadGiftSortDataByGiftId($giftId);

            if($listType == 1){
                if(!$giftData){
                    $fromPreSort = 0;
                    $updateData = array('gift_id'=>$giftId, 'limited_count'=>0, 'pre_hot_sort'=>$targetPreSort,'edit_time'=>time());
                }else{
                    $fromPreSort = $giftData['pre_hot_sort'];
                    $updateData = array('pre_hot_sort'=>$targetPreSort,'edit_time'=>time());
                }
            }else{
                if(!$giftData){
                    $fromPreSort = 0;
                    $updateData = array('gift_id'=>$giftId, 'limited_count'=>0, 'pre_new_sort'=>$targetPreSort,'edit_time'=>time());
                }else{
                    $fromPreSort = $giftData['pre_new_sort'];
                    $updateData = array('pre_new_sort'=>$targetPreSort,'edit_time'=>time());
                }
            }


            //判断gift_id为 $giftId 的数据是否存在，并更新相应数据的排序
            $result = $service->updateGiftPreSortByGiftId($giftId,$updateData);
            if($result === false){
                $this->outputJSON(true,'100001',$service->getFirstError());
            }
            //修改排序n后排在n后的排序变成n+1或n-1
            $result = $service->autoUpdateOtherGiftPreSort($giftId, $listType, $fromPreSort, $targetPreSort);
            if($result === false){
                $this->outputJSON(true,'100001',$service->getFirstError());
            }
            $this->outputJSON(false,'000000','更新成功');
        }else{
            if(empty($giftId) || !is_numeric($giftId)){
                $this->error('gift_id参数错误');
                exit;
            }
            //根据gift_id获取媒体站礼包表的数据
            $giftData = $service->loadGiftSortDataByGiftId($giftId);

            $this->assign('giftId',$giftId);
            $this->assign('listType',$listType);
            $this->assign('giftData',$giftData);
            $this->display('gift_sort_edit');
        }
    }

    /**
     * ajax恢复默认排序
     * @author xy
     * @since 2017/08/10 15:38
     */
    public function ajax_recover_sort(){
        //列表类型 1热门礼包 2新游礼包
        $listType = intval(I('list_type'));
        if(!in_array($listType, array(1, 2))){
            $this->outputJSON(true,'100001','列表类型参数错误');
        }
        $giftId = intval(I('gift_id'));
        //恢复默认排序，设置为0
        if($listType == 1){
            $data['pre_hot_sort'] = 0;
            $data['final_hot_sort'] = 0;
        }else{
            $data['pre_new_sort'] = 0;
            $data['final_new_sort'] = 0;
        }
        $result = M('sync_gift_lib')->where('gift_id = '.$giftId)->save($data);
        if($result){
            $this->outputJSON(false,'000000','更新成功');
        }else{
            $this->outputJSON(true,'100001','更新失败');
        }
    }

    /**
     * 设置礼包上限数量列表页
     * @author xy
     * @since 2017/08/10 18:28
     */
    public function set_upon_num(){
        $listType = intval(I('list_type')); //列表类型 1 热门礼包，2新游礼包
        if(!in_array($listType,array(1, 2))){
            $listType = 1;
        }
        $where = array();
        $idOrName = I('id_or_name');
        if(!empty($idOrName)){
            $where['_string'] = '( gl.gift_id = \''.$idOrName.'\' OR CONCAT(al.app_name, \'-\',gl.gift_name , \'-\', gl.original_name ) like \'%'.$idOrName.'%\' )';
        }

        $service = new GiftService();

        //1.获取所有礼包，并列出所有礼包
        $giftList = $service->getAllGiftInfoList($listType, $where);
        if ($giftList === false) {
            $this->error($service->getFirstError());
        }
        $this->assign('idOrName',$idOrName);
        $this->assign('listType', $listType);
        $this->assign('giftList', $giftList);
        $this->display();

    }

    /**
     * 生成列表，把预排序设置为默认排序
     * @author xy
     * @since 2017/08/11 09:41
     */
    public function ajax_generate_list(){
        $listType = intval(I('list_type')); //列表类型 1 热门礼包，2新游礼包
        if(!in_array($listType,array(1,2))){
            $listType = 1;
        }
        //更新列表的最终排序
        $service = new GiftService();
        $result = $appData = $service->updateGiftFinalSort($listType);
        if($result === false){
            $this->outputJSON(true,'100001',$service->getFirstError());
        }
        $this->outputJSON(false,'000000','更新列表成功');
    }

    /**
     * 验证填写的上限数量是否超出礼包总量
     * @author xy
     * @since 2017/08/11 14:32
     */
    public function ajax_check_upon_num(){
        $giftId = intval(I('giftId'));
        $uponNum = intval(I('uponNum'));
        if(empty($giftId)){
            $this->outputJSON(true,'100001','礼包id参数错误');
        }
        /*if(empty($uponNum)){
            $this->outputJSON(true,'100001','上限数量参数错误');
        }
        if($uponNum<=0){
            $this->outputJSON(true,'100001','上限数量必须大于0');
        }*/
        //通过礼包id获取所有批次礼包码总数，判断礼包总数是否大于上限数量
        $service = new GiftService();
        $stockNum = $service->getGiftCodeNumByGiftId($giftId);
        if($stockNum === false){
            $this->outputJSON(true,'100001', $service->getFirstError());
        }
        if($stockNum<$uponNum){
            $this->outputJSON(true,'100001', '上限数量必须小于礼包库数量');
        }
        $this->outputJSON(false,'000000', '允许设置');
    }

    /**
     * 设置单个礼包的上限数量
     * @author xy
     * @since 2017/08/11 16:30
     */
    public function set_single_upon_num(){
        $giftId = intval(I('giftId'));
        $uponNum = intval(I('uponNum'));
        if(empty($giftId)){
            $this->outputJSON(true,'100001','礼包id参数错误');
        }
        $service = new GiftService();
        //判断是否已经存在数据，存在则修改，不存在则添加
        $isExist = $service->isGiftSortDataExist($giftId);
        $data = array(
            'edit_time' => time(),
            'limited_count' => $uponNum,
        );
        if(!$isExist){
            $data['gift_id'] = $giftId;
            $result = M('sync_gift_lib')->add($data);
        }else{
            $result = M('sync_gift_lib')->where('gift_id = '.$giftId)->save($data);
        }
        if(empty($result)){
            $this->outputJSON(true,'100001','设置失败');
        }
        $this->outputJSON(false,'000000','设置成功');
    }

    /**
     * 设置多个礼包的上限数量
     * @author xy
     * @since 2017/08/14 09:20
     */
    public function set_multi_upon_num(){
        $limitedCount = I('limited_count');
        if(!is_array($limitedCount)){
            $this->outputJSON(true,'100001','参数类型错误');
        }
        $giftIds = array();
        foreach ($limitedCount as $giftId=>$uponNum){
            if($uponNum != ''){
                $giftIds[] = $giftId;
            }else{
                unset($limitedCount[$giftId]);
            }
        }

        $existGiftIdArr = M('sync_gift_lib')
            ->field('gift_id')
            ->where('gift_id IN ('.implode(',',$giftIds).')')
            ->select();
        $giftIdArr = array();
        foreach ($existGiftIdArr as $key=>$value){
            array_push($giftIdArr,$value['gift_id']);
        }

        $addData = array();
        foreach ($limitedCount as $giftId => $uponNum){

            if(!in_array($giftId,$giftIdArr)){
                //如果不存在记录，保存数据，后面执行插入操作
                $data = array(
                    'gift_id' => $giftId,
                    'limited_count' => $uponNum,
                    'edit_time' => time(),
                );
                $addData[] = $data;
                unset($limitedCount[$giftId]);
            }else{
                //如果存在记录，执行修改操作
                $data = array(
                    'limited_count' => $uponNum,
                    'edit_time' => time(),
                );
                $result = M('sync_gift_lib')->where('gift_id = '.$giftId)->save($data);
                if($result){
                    continue;
                }else{
                    $this->outputJSON(true,'100001','修改失败');
                }
            }
        }
        //执行插入的操作
        if(!empty($addData)){
            $result = M('sync_gift_lib')->addAll($addData);
            if($result){
                $this->outputJSON(false,'000000','修改成功');
            }else{
                $this->outputJSON(true,'100001','修改失败');
            }
        }else{
            $this->outputJSON(false,'000000','修改成功');
        }

    }

    /**
     * 游戏礼包列表页，列出游戏的礼包库总量与可领取剩余量
     * @author xy
     * @since 2017/08/14 11:05
     */
    public function app_gift_list(){
        $where = array();
        $order = intval(I('order'));
        if(!in_array($order,array(1,2))){
            $order = 1;
        }
        //游戏id获取游戏名称
        $appIdOrAppName = I('id_or_name');
        if(!empty($appIdOrAppName)){
            $where['_string'] = '( alist.app_id = "'.$appIdOrAppName.'" OR alib.app_name like "%'.$appIdOrAppName.'%" )';
        }

        // 分页
        $service = new GiftService();

        $totalCount = $service->getAppAllGiftListNum($where); //获取总条数
        if($totalCount === false){
            $this->error($service->getFirstError());
        }
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

        //通过游戏列表关联礼包库以及礼包码表，获取总量以及可领取量
        $appGiftList = $service->getAppAllGiftList($order, $currentPage, $pageSize, $where);
        if($appGiftList === false){
            $this->error($service->getFirstError());
        }

        $this->assign('appGiftList',$appGiftList);
        $this->assign('order',$order);
        $this->assign('appIdOrAppName',$appIdOrAppName);

        $this->display();

    }

    /**
     * 游戏礼包详情页
     * @author xy
     * @since 2017/08/15 09:38
     */
    public function app_gift_detail(){
        $appId = intval(I('app_id'));

        if(IS_AJAX){
            if(empty($appId)){
                $this->outputJSON(true,'100001','游戏id参数缺失');
            }
            $bannerPath = trim(I('img_url'));
            if(empty($bannerPath)){
                $this->outputJSON(true,'100001','banner图片地址参数缺失');
            }
            $giftBanner = M('gift_banner')->where('app_id = '.$appId)->find();
            $data = array(
                'banner_path' => $bannerPath,
                'update_time' => time(),
            );
            if(empty($giftBanner)){
                $data['app_id'] = $appId;
                $data['create_time'] = time();
                $result = M('gift_banner')->where('app_id = '.$appId)->add($data);
            }else{
                $result = M('gift_banner')->where('app_id = '.$appId)->save($data);
                if($result){
                    //删除旧图片
                    if($bannerPath != $giftBanner['banner_path'] && !empty($bannerPath)) {
                        unlink(realpath($giftBanner['banner_path']));
                    }
                }
            }
            if(!$result){
                $this->outputJSON(true,'100001','保存图片失败');
            }
            $this->outputJSON(false,'000000','保存图片成功');
        }else{
            if(empty($appId)){
                $this->error('游戏id参数错误');
            }
            //礼包名称名称
            $giftName = trim(I('gift_name'));
            $where = array();
            if(!empty($giftName)){
                $where['_string'] = '( CONCAT(alib.app_name, \'-\', gl.gift_name, \'-\', gl.original_name) like "%'.$giftName.'%" )';
            }
            /* 1.先判断媒体站游戏详情表是否有对应游戏id的数据，有则获取；
             * 2.无则判断游戏库是否有对应游戏id的数据，有则获取，无则报错
             */
            $appInfo = M('app_lib')->alias('alist')
                ->field('alist.*, alib.app_file_url')
                ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')
                ->where('alist.app_id = '.$appId)->find();
            if(empty($appInfo)){
                $appInfo = M(C('DB_ZHIYU.DB_NAME').'.'.'app_lib', C('DB_ZHIYU.DB_PREFIX'))->where('app_id = '.$appId)->find();
                if(empty($appInfo)){
                    $this->error('未找到对应游戏id的数据');
                }
            }
            $this->assign('appInfo',$appInfo);
            //获取礼包详情页的banner图片地址
            $bannerPath = M('gift_banner')->where('app_id = '.$appId)->getField('banner_path');

            $this->assign('bannerPath',$bannerPath);
            //查找该游戏id对应的有设置上限数量的所有礼包
            $service = new GiftService();
            $giftList = $service->getSetUponNumGiftListByAppId($appId, $where);

            if($giftList === false){
                $this->error($service->getFirstError());
            }
            $this->assign('appId',$appId);
            $this->assign('giftName',$giftName);
            $this->assign('giftList',$giftList);
            $this->display();
        }

    }

    /**
     * 申请礼包操作
     * @author xy
     * @since 2017/08/15 16:43
     */
    public function apply_gift(){
        $giftId = intval(I('gift_id'));
        $service = new GiftService();
        if(IS_AJAX){
            if(empty($giftId)){
                $this->outputJSON(true,'100001', '礼包id参数错误');
            }
            //申请礼包的数量
            $applyNum = intval(I('apply_num'));
            if(empty($applyNum)){
                $this->outputJSON(true,'100001', '申请的数量不能为0');
            }
            //执行申请礼包操作
            $result = $service->applyGiftFromZhiYu($giftId, $applyNum);
            if($result){
                $this->outputJSON(false,'000000', '申请成功');
            }else{
                $this->outputJSON(true,'100001', $service->getFirstError());
            }
        }else{
            if(empty($giftId)){
                $this->error('礼包id参数错误');
            }

            $giftApplyInfo = $service->getGiftApplyInfoByGiftId($giftId);
            if($giftApplyInfo === false){
                $this->error($service->getFirstError());
            }
            $this->assign('giftId',$giftId);
            $this->assign('giftApplyInfo',$giftApplyInfo);

            $this->display();
        }
    }

    /**
     * 删除礼包
     * @author xy
     * @since 2017/08/16 14:31
     */
    public function gift_delete(){
        $giftId = intval(I('gift_id'));
        if(empty($giftId)){
            $this->outputJSON(true,'100001', '礼包id参数错误');
        }
        $service = new GiftService();
        $result = $service -> deleteGift($giftId);
        if(!$result){
            $this->outputJSON(true,'100001', '删除礼包失败');
        }
        $this->outputJSON(false,'000000', '删除礼包成功');
    }

    /**
     * ajax验证申请的礼包数量是否符合要求(不得超过库数量以及上限数量)
     * @author xy
     * @since 2017/08/15 18:31
     */
    public function ajax_check_apply_num(){
        $giftId = intval(I('gift_id'));
        $applyNum = intval(I('apply_num'));
        if(empty($giftId)){
            $this->outputJSON(true,'100001', '礼包id参数错误');
        }
        if(empty($applyNum)){
            $this->outputJSON(true,'100001', '申请数量不符合要求');
        }

        $service = new GiftService();
        $result = $service->checkGiftApplyNum($giftId, $applyNum);

        if($result){
            $this->outputJSON(false,'000000', '允许申请');
        }else{
            $this->outputJSON(true,'100001', $service->getFirstError());
        }
    }

    /**
     * 礼包中心首页的轮播列表
     * @author xy
     * @since 2017/09/14 17:53
     */
    public function gift_slide_list(){
        // 获取搜索条件
        $slideTitle = trim(I('slide_title'));
        $publishStatus = intval(I('publish_status'));
        $showPosition = intval(I('show_position'));
        $where = array();
        if(!empty($slideTitle)) {
            $where['gs.slide_title']    = array('like', "%$slideTitle%");
        }
        $nowTime = time();
        if ($publishStatus == 1) { // 待上线
            $where['gs.is_publish'] = 1;
            $where['gs.start_time'] = array('gt', $nowTime);
        } elseif ($publishStatus == 2) {// 已上线
            $where['gs.is_publish'] = 1;
            $where['gs.start_time'] = array('lt', $nowTime);
            $where['gs.end_time'] = array('gt', $nowTime);
        } elseif ($publishStatus == 3) {// 已下线
            $where['gs.is_publish'] = 2;
        }

        if(!empty($showPosition)){
            $where['gs.show_position'] = $showPosition;
        }


        // 分页
        $service = new GiftService();

        $totalCount = $service->countGiftSlideNum($where); //获取总条数
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
        $slideList = $service->getGiftSlideListByPage($where, $currentPage, $pageSize);
        if($slideList === false){
            $this->error($service->getFirstError());
        }
        $this->assign('showPosition',$showPosition);
        $this->assign('slideTitle',$slideTitle);
        $this->assign('publishStatus',$publishStatus);
        $this->assign('slideList', $slideList);
        $this->display();
    }

    /**
     * 礼包中心首页轮播添加
     * @author xy
     * @since 2017/09/15 13:38
     */
    public function gift_slide_add(){
        $showPosition = intval(I('show_position'));
        if(IS_AJAX) {
            $slideTitle = trim(I('slide_title'));
            if(empty($slideTitle)){
                $this->outputJSON(true, '100001', '请填写标题');
            }
            $relationType = intval(I('relation_type'));
            if(!in_array($relationType, array(1, 2))){
                $this->outputJSON(true, '100001', '轮播关联类型错误');
            }
            $appId = intval(I('app_id'));
            $giftId = intval(I('gift_id'));
            if($relationType == 1){
                if(empty($appId)){
                    $this->outputJSON(true, '100001', '请选择关联游戏');
                }
            }else{
                if(empty($appId) || empty($giftId)){
                    $this->outputJSON(true, '100001', '请选择关联游戏或者关联礼包');
                }
            }
            $openType = intval(I('open_type'));
            if(empty($openType)){
                $openType = 1;
            }
            $slideImage = trim(I('image_path'));
            if(empty($slideImage)){
                $this->outputJSON(true, '100001', '请上传图片');
            }
            $data['slide_title'] = $slideTitle;
            $data['relation_type'] = $relationType;
            $data['open_type'] = $openType;
            $data['app_id'] = $appId;
            $data['gift_id'] = $giftId;
            $data['slide_img'] = $slideImage;
            $data['create_time'] = time();
            $data['sort'] = intval(I('sort'));
            $data['is_publish'] = 1;    // 上架
            $data['admin_id'] = $this->user_info['id'];
            $data['start_time'] = strtotime(I('start_time'));
            $data['end_time'] = strtotime(I('end_time'));
            $data['show_position'] = $showPosition;
            if (M('gift_slide')->add($data)) {
                $this->outputJSON(false, '000000', '添加成功');
            } else {
                $this->outputJSON(true, '100001', '添加失败');
            }
        } else {
            $service = new GiftService();
            $appList = $service->getHasGiftAppList();
            if($appList === false){
                $this->error($service->getFirstError());
            }
            $reloadUrl = U('Admin/Gift/gift_slide_list', array('show_position'=>$showPosition));

            $this->assign('showPosition', $showPosition);
            $this->assign('reloadUrl', $reloadUrl);
            $this->assign('appList', $appList);
            $this->assign('start_time', time());
            $this->assign('end_time', strtotime("+30 day"));
            $this->display();
        }
    }

    /**
     * 礼包中心首页轮播编辑
     * @author xy
     * @since 2017/09/15 13:48
     */
    public function gift_slide_edit(){
        $slideId = intval(I('slide_id'));
        $service = new GiftService();
        if(IS_AJAX){
            if(empty($slideId)){
                $this->outputJSON(true, '100001', '必填参数缺失');
            }
            $slideTitle = trim(I('slide_title'));
            if(empty($slideTitle)){
                $this->outputJSON(true, '100001', '请填写标题');
            }
            $relationType = intval(I('relation_type'));
            if(!in_array($relationType, array(1, 2))){
                $this->outputJSON(true, '100001', '轮播关联类型错误');
            }
            $appId = intval(I('app_id'));
            $giftId = intval(I('gift_id'));
            if($relationType == 1){
                if(empty($appId)){
                    $this->outputJSON(true, '100001', '请选择关联游戏');
                }
            }else{
                if(empty($appId) || empty($giftId)){
                    $this->outputJSON(true, '100001', '请选择关联游戏或者关联礼包');
                }
            }
            $openType = intval(I('open_type'));
            if(empty($openType)){
                $openType = 1;
            }
            $slideImage = trim(I('image_path'));

            $data['slide_title'] = $slideTitle;
            $data['relation_type'] = $relationType;
            $data['open_type'] = $openType;
            $data['app_id'] = $appId;
            $data['gift_id'] = $giftId;
            if(!empty($slideImage)){
                $data['slide_img'] = $slideImage;
            }
            $data['update_time'] = time();
            $data['sort'] = intval(I('sort'));
            $data['is_publish'] = 1;    // 上架
            $data['admin_id'] = $this->user_info['id'];
            $data['start_time'] = strtotime(I('start_time'));
            $data['end_time'] = strtotime(I('end_time'));
            if (M('gift_slide')->where(array('slide_id' => $slideId))->save($data)) {
                $this->outputJSON(false, '000000', '编辑成功');
            } else {
                $this->outputJSON(true, '100001', '编辑失败');
            }
        } else {
            if(empty($slideId)){
                $this->outputJSON(true,'100001', '必填参数缺失');
            }
            $slide = $service -> getGiftSlideById($slideId);
            if($slide === false){
                $this->outputJSON(true,'100001', $service->getFirstError());
            }
            $appList = $service->getHasGiftAppList();
            if($appList === false){
                $this->error($service->getFirstError());
            }
            $reloadUrl = U('Admin/Gift/gift_slide_list', array('show_position'=>$slide['show_position']));
            $this->assign('reloadUrl', $reloadUrl);
            $this->assign('appList', $appList);
            $this->assign('slide', $slide);
            $this->display();
        }
    }

    /**
     * ajax方式生成可选择的礼包option
     * @author xy
     * @since 2017/09/15 09:51
     */
    public function ajax_get_gift_option(){
        $appId = intval(I('app_id'));
        $giftId = intval(I('gift_id'));
        if(empty($appId)){
            $this->outputJSON(true, '100001', '请选择具体的游戏');
        }
        $service = new GiftService();
        $giftList = $service -> getAppGiftByAppId($appId);
        if($giftList === false){
            $this->outputJSON(true, '100001', $service->getFirstError());
        }
        $html = '';
        if(empty($giftList)){
            $html .= '<option value="">暂无礼包</option>';
        }else{
            foreach ($giftList as $gift){
                if($gift['sync_gift_id'] === $giftId){
                    $html .= '<option value="'.$gift['sync_gift_id'].'" selected="selected">'.$gift['short_gift_name'].'</option>';
                }else{
                    $html .= '<option value="'.$gift['sync_gift_id'].'">'.$gift['short_gift_name'].'</option>';
                }

            }
        }
        $this->outputJSON(false, '000000', '获取成功', $html);
    }

    /**
     * 礼包中心轮播图片上下架操作
     * @author xy
     * @since 2017/09/15 15:26
     */
    public function gift_slide_publish_change(){
        $slideId = intval(I('slide_id'));
        if(empty($slideId)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $slide = M('gift_slide')->field('slide_id, is_publish')->where(array('slide_id'=>$slideId))->find();
        if(empty($slide)){
            $this->outputJSON(true,'100001','未找到id为'.$slideId.'的活动');
        }
        $slide['update_time'] = time();
        $slide['admin_id'] = $this->user_info['id'];
        if($slide['is_publish'] == 1){
            $slide['is_publish'] = 2;
        }else{
            $slide['is_publish'] = 1;
        }
        $result = M('gift_slide')->where(array('slide_id'=>$slideId))->save($slide);
        if(empty($result)){
            $this->outputJSON(true,'100001','改变状态失败');
        }
        $this->outputJSON(false,'000000','改变状态成功');
    }

    /**
     * 删除礼包轮播图片
     * @author xy
     * @since 2017/09/15 15:43
     */
    public function gift_slide_delete(){
        $slideId = intval(I('slide_id'));
        if(empty($slideId)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $result = M('gift_slide')->where(array('slide_id'=>$slideId))->delete();
        if(empty($result)){
            $this->outputJSON(true,'100001','删除失败');
        }
        $this->outputJSON(false,'000000','删除成功');
    }

    /**
     * 礼包中心首页的右侧图文广告
     * @author xy
     * @since 2017/09/15 18:13
     */
    public function gift_index_ad_list(){
        $slideCate = M('slide_cat')->where(array('is_delete' => 1, 'keyword' => 'GIFT_INDEX_LEFT_AD'))->getField('cid');
        // 获取搜索条件
        if($slideCate === false){
            $this->error('获取分类信息失败');
        }
        $slideName = trim(I('slide_name'));
        $publishStatus = intval(I('publish_status'));
        //幻灯片分类
        $slideCid = intval(I('slide_cid'));
        $where = array();
        if(!empty($slideTitle)) {
            $where['s.slide_name']    = array('like', "%$slideName%");
        }
        $nowTime = time();
        if ($publishStatus == 1) { // 待上线
            $where['s.is_publish'] = 1;
            $where['s.start_time'] = array('gt', $nowTime);
        } elseif ($publishStatus == 2) {// 已上线
            $where['s.is_publish'] = 1;
            $where['s.start_time'] = array('lt', $nowTime);
            $where['s.end_time'] = array('gt', $nowTime);
        } elseif ($publishStatus == 3) {// 已下线
            $where['s.is_publish'] = 2;
        }

        if(empty($slideCid)){
            $slideCid = $slideCate['cid'];
        }
        $where['s.slide_cid'] = $slideCid;
        $service = new SlideService();
        // 分页
        $totalCount = $service->countSlideNum($where); //获取总条数
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
        $slideList = $service->getSlideListByPage($where, $currentPage, $pageSize);
        if($slideList === false){
            $this->error('查询失败');
        }
        $this->assign('slideName',$slideName);
        $this->assign('publishStatus',$publishStatus);
        $this->assign('slideCid',$slideCid);
        $this->assign('slideList', $slideList);
        $this->display('Slide/slide_list');
    }
}