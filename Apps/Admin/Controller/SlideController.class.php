<?php
/**
 * 热门活动
 * User: xy
 * Date: 2017/9/4
 * Time: 18:19
 */

namespace Admin\Controller;

use Admin\Service\SlideService;
use Think\Controller;
use Admin\Controller\AdminBaseController;

class SlideController extends AdminBaseController
{

    public function slide_list(){
        // 获取搜索条件
        $slideName = trim(I('slide_name'));
        $publishStatus = intval(I('publish_status'));
        //幻灯片分类
        $slideCid = intval(I('slide_cid'));
        $where = array();
        if(!empty($slideName)) {
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
            $where['s.is_publish'] = 0;
        }

        if(!empty($slideCid)){
            $where['s.slide_cid'] = $slideCid;
        }
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
        $this->display();
    }

    /**
     * 添加图片信息
     * @author xy
     * @since 2017/09/15 15:04
     */
    public function slide_add(){
        $slideCid = intval(I('slide_cid'));
        $service = new SlideService();
        if(IS_AJAX){
            if(empty($slideCid)){
                $this->outputJSON(true, '100001', '必填参数缺失');
            }
            //图片分类
            if(!$service->isSlideCidExist($slideCid)){
                $this->outputJSON(true, '100001', '分类不存在');
            }
            $data['slide_cid'] = $slideCid;
            //图片名称
            $slideName = trim(I('slide_name'));
            if(empty($slideName)){
                $this->outputJSON(true, '100001', '请填写图片名称');
            }
            $data['slide_name'] = $slideName;
            //上传的图片的路径
            $imagePath = trim(I('image_path'));
            if (empty($imagePath)) {
                $this->outputJSON(true, '100001', '请上传图片');
            }
            $data['slide_pic'] = $imagePath;

            //跳转地址
            $slideUrl = trim(I('slide_url'));
            $data['slide_url'] = $slideUrl;

            //图片说明
            $slideDes = trim(I('slide_des'));
            $data['slide_des'] = $slideDes;

            $data['is_publish'] = intval(I('is_publish'));
            $data['sort'] = intval(I('sort'));
            $data['admin_id'] = $this->user_info['id'];
            $data['start_time'] = strtotime(I('start_time'));
            $data['end_time'] = strtotime(I('end_time'));
            $data['create_time'] = time();
            if (M('slide')->add($data)) {
                $this->outputJSON(false, '000000', '添加成功');
            } else {
                $this->outputJSON(true, '100001', '添加失败');
            }
        }else{
            if(!$service->isSlideCidExist($slideCid)){
                $this->error('分类不存在');
            }
            $reloadUrl = U('Admin/Slide/slide_list', array('slide_cid' => $slideCid));
            $this->assign('reloadUrl', $reloadUrl);
            $this->assign('slideCid', $slideCid);
            $this->assign('start_time', time());
            $this->assign('end_time', strtotime("+30 day"));
            $this->display();
        }

    }

    /**
     * 编辑幻灯片
     * @author xy
     * @since 2017/09/05 16:37
     */
    public function slide_edit(){
        $slideId = intval(I('slide_id'));
        $slideCid = intval(I('slide_cid'));
        $service = new SlideService();
        if(IS_AJAX){
            if(empty($slideId)){
                $this->outputJSON(true, '100001', '必填参数缺失');
            }
            if(empty($slideCid)){
                $this->outputJSON(true, '100001', '必填参数缺失');
            }
            $slide = M('slide')->where(array('sldie_id'=>$slideId))->getField('slide_cid');
            if($slide['slide_cid'] != $slideCid){
                $this->outputJSON(true, '100001', '分类id错误');
            }
            $data['slide_cid'] = $slideCid;

            //图片名称
            $slideName = trim(I('slide_name'));
            if(empty($slideName)){
                $this->outputJSON(true, '100001', '请填写图片名称');
            }
            $data['slide_name'] = $slideName;

            //上传的图片的路径
            $imagePath = trim(I('image_path'));
            if (!empty($imagePath)) {
                $data['slide_pic'] = $imagePath;
            }
            //跳转地址
            $slideUrl = trim(I('slide_url'));
            if (!empty($slideUrl)) {
                $data['slide_url'] = $slideUrl;
            }
            //图片说明
            $slideDes = trim(I('slide_des'));
            if (!empty($slideDes)) {
                $data['slide_des'] = $slideDes;
            }

            $data['is_publish'] = intval(I('is_publish'));
            $data['sort'] = intval(I('sort'));
            $data['admin_id'] = $this->user_info['id'];
            $data['start_time'] = strtotime(I('start_time'));
            $data['end_time'] = strtotime(I('end_time'));
            $data['update_time'] = time();

            $result = M('slide')->where(array('slide_id'=>$slideId))->save($data);
            if($result){
                $this->outputJSON(false, '000000', '编辑成功');
            } else {
                $this->outputJSON(true, '100001', '编辑失败');
            }

        } else {
            if(empty($slideId)){
                $this->error('id参数不能为空');
            }
            $slide = M('slide')->where('slide_id = '.$slideId)->find();
            if(empty($slide)){
                $this->error('未找到id为'.$slideId.'的活动');
            }
            if(!$service->isSlideCidExist($slideCid)){
                $this->error('分类不存在');
            }
            $reloadUrl = U('Admin/Slide/slide_list', array('slide_cid' => $slideCid));
            $this->assign('reloadUrl', $reloadUrl);
            $this->assign('slide', $slide);
            $this->assign('slideCid', $slideCid);
            $this->display();
        }

    }

    /**
     * 修改幻灯片发布状态
     * @author xy
     * @since 2017/09/15 17:02
     */
    public function public_status_change(){
        $slideId = intval(I('slide_id'));
        if(empty($slideId)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $slide = M('slide')->field('slide_id, is_publish')->where(array('slide_id'=>$slideId))->find();
        if(empty($slide)){
            $this->outputJSON(true,'100001','未找到id为'.$slideId.'的活动');
        }
        $slide['update_time'] = time();
        $slide['admin_id'] = $this->user_info['id'];
        if($slide['is_publish'] == 1){
            $slide['is_publish'] = 0;
        }else{
            $slide['is_publish'] = 1;
        }
        $result = M('slide')->where(array('slide_id'=>$slideId))->save($slide);
        if(empty($result)){
            $this->outputJSON(true,'100001','改变状态失败');
        }
        $this->outputJSON(false,'000000','改变状态成功');
    }

    /**
     * 删除
     * @author xy
     * @since 2017/09/04 18:32
     */
    public function slide_delete(){
        $slideId = intval(I('slide_id'));
        if(empty($slideId)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $result = M('slide')->where(array('slide_id'=>$slideId))->delete();
        if(empty($result)){
            $this->outputJSON(true,'100001','删除失败');
        }
        $this->outputJSON(false,'000000','删除成功');
    }

}