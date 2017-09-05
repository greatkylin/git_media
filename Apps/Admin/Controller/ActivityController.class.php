<?php
/**
 * 热门活动
 * User: xy
 * Date: 2017/9/4
 * Time: 18:19
 */

namespace Admin\Controller;

use Think\Controller;
use Admin\Controller\AdminBaseController;

class ActivityController extends AdminBaseController
{

    public function activity_list(){
        $where = array();
        $activityTitle = trim(I('activity_title'));
        if(!empty($activityTitle)){
            $where['activity_title'] = array('like', "%".$activityTitle."%");
        }
        $isPublish = intval(I('is_publish'));
        if(!empty($isPublish)){
            $where['is_publish'] = $isPublish;
        }
        $isDelete = intval(I('is_delete'));
        if(!empty($isDelete)){
            $where['is_delete'] = $isDelete;
        }
        $totalCount = M('popular_activity')->where($where)->count(); //获取总条数
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
        $activityList = M('popular_activity')
            ->where($where)
            ->order('is_delete ASC, is_publish DESC, create_time DESC')
            ->limit($currentPage, $pageSize)
            ->select();
        $this->assign('activityList', $activityList);
        $this->assign('activityTitle', $activityTitle);
        $this->assign('isPublish', $isPublish);
        $this->assign('isDelete', $isDelete);
        $this->display();
    }

    /**
     * 添加活动
     * @author xy
     * @since 2017/09/05 12:00
     */
    public function activity_add(){
        if(IS_AJAX){
            //用来判断是否立即发布
            $publishStatus = intval(I('publish_status'));
            $imaUrl = trim(I('img_url_image'));
            if(empty($imaUrl)){
                $this->outputJSON(true, '100001', '请上传详情页图片');
            }
            $activityTitle = trim(I('activity_title'));
            if(empty($activityTitle)){
                $this->outputJSON(true, '100001', '请填写活动标题');
            }
            $joinMethod = trim(I('join_method'));
            if(empty($joinMethod)){
                $this->outputJSON(true, '100001', '请填写参与方式');
            }
            $activityDetail = trim(I('activity_detail'));
            if(empty($activityDetail)){
                $this->outputJSON(true, '100001', '请填写活动详情');
            }
            $activityNote = trim(I('activity_note'));
            if(empty($activityNote)){
                $this->outputJSON(true, '100001', '请填写活动注意事项');
            }
            $startTime = trim(I('start_time'));
            if(empty($startTime)){
                $this->outputJSON(true, '100001', '请填写活动开始时间');
            }
            $endTime = trim(I('end_time'));
            if(empty($endTime)){
                $this->outputJSON(true, '100001', '请填写活动结束时间');
            }
            $startTime = strtotime($startTime);
            $endTime = strtotime($endTime);
            if($startTime>$endTime){
                $this->outputJSON(true, '100001', '请填写活动开始时间要小于活动结束时间');
            }
            $data = array(
                'activity_title' => $activityTitle,
                'join_method' => $joinMethod,
                'activity_detail' => $activityDetail,
                'activity_note' => $activityNote,
                'start_time' => $startTime,
                'end_time' => $endTime+86400-1,
                'image_path' => $imaUrl,
                'create_time' => time(),
            );
            if(empty($publishStatus)){
                $data['is_publish'] = 2;
            }else{
                $data['is_publish'] = 1;
                $data['publish_time'] = time();
            }
            $result = M('popular_activity')->add($data);
            if(empty($result)){
                $this->outputJSON(true, '100001', '添加失败');
            }else {
                $this->outputJSON(false, '000000', '添加成功');
            }
        }else{
            $this->display();
        }

    }

    /**
     * 编辑活动
     * @author xy
     * @since 2017/09/05 16:37
     */
    public function activity_edit(){
        $activityId = intval(I('activity_id'));
        if(IS_AJAX){
            if(empty($activityId)){
                $this->outputJSON(true, '100001', 'id参数不能为空');
            }
            //用来判断是否立即发布
            $publishStatus = intval(I('publish_status'));
            $imaUrl = trim(I('img_url_image'));

            $activityTitle = trim(I('activity_title'));
            if(empty($activityTitle)){
                $this->outputJSON(true, '100001', '请填写活动标题');
            }
            $joinMethod = trim(I('join_method'));
            if(empty($joinMethod)){
                $this->outputJSON(true, '100001', '请填写参与方式');
            }
            $activityDetail = trim(I('activity_detail'));
            if(empty($activityDetail)){
                $this->outputJSON(true, '100001', '请填写活动详情');
            }
            $activityNote = trim(I('activity_note'));
            if(empty($activityNote)){
                $this->outputJSON(true, '100001', '请填写活动注意事项');
            }
            $startTime = trim(I('start_time'));
            if(empty($startTime)){
                $this->outputJSON(true, '100001', '请填写活动开始时间');
            }
            $endTime = trim(I('end_time'));
            if(empty($endTime)){
                $this->outputJSON(true, '100001', '请填写活动结束时间');
            }
            $startTime = strtotime($startTime);
            $endTime = strtotime($endTime);
            if($startTime>$endTime){
                $this->outputJSON(true, '100001', '请填写活动开始时间要小于活动结束时间');
            }
            $data = array(
                'activity_title' => $activityTitle,
                'join_method' => $joinMethod,
                'activity_detail' => $activityDetail,
                'activity_note' => $activityNote,
                'start_time' => $startTime,
                'end_time' => $endTime+86400-1,
                'update_time' => time(),
            );
            if(!empty($imaUrl)){
                $data['image_path'] = $imaUrl;
            }
            if(empty($publishStatus)){
                $data['is_publish'] = 2;
            }else{
                $data['is_publish'] = 1;
                $data['publish_time'] = time();
            }
            $result = M('popular_activity')->where('activity_id = '.$activityId)->save($data);
            if(empty($result)){
                $this->outputJSON(true, '100001', '编辑失败');
            }else {
                $this->outputJSON(false, '000000', '编辑成功成功');
            }
        } else {
            if(empty($activityId)){
                $this->error('id参数不能为空');
            }
            $activity = M('popular_activity')->where('activity_id = '.$activityId)->find();
            if(empty($activity)){
                $this->error('未找到id为'.$activityId.'的活动');
            }
            $this->assign('activity', $activity);
            $this->display();
        }

    }

    /**
     * 修改活动的发布状态
     * @author xy
     * @since 2017/09/04 18:32
     */
    public function public_status_change(){
        $activityId = intval(I('activity_id'));
        if(empty($activityId)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $activity = M('popular_activity')->field('activity_id, is_publish')->where(array('activity_id'=>$activityId))->find();
        if(empty($activity)){
            $this->outputJSON(true,'100001','未找到id为'.$activityId.'的活动');
        }
        $activity['update_time'] = time();
        if($activity['is_publish'] == 1){
            $activity['is_publish'] = 2;
        }else{
            $activity['is_publish'] = 1;
            $activity['publish_time'] = time();
        }
        $result = M('popular_activity')->where(array('activity_id'=>$activityId))->save($activity);
        if(empty($result)){
            $this->outputJSON(true,'100001','改变状态失败');
        }
        $this->outputJSON(false,'000000','改变状态成功');
    }

    /**
     * 修改活动的删除状态
     * @author xy
     * @since 2017/09/04 18:32
     */
    public function delete_status_change(){
        $activityId = intval(I('activity_id'));
        if(empty($activityId)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $activity = M('popular_activity')->field('activity_id, is_delete')->where(array('activity_id'=>$activityId))->find();
        if(empty($activity)){
            $this->outputJSON(true,'100001','未找到id为'.$activityId.'的活动');
        }
        $activity['update_time'] = time();
        if($activity['is_delete'] == 1){
            $activity['is_delete'] = 2;
        }else{
            $activity['is_delete'] = 1;
        }
        $result = M('popular_activity')->where(array('activity_id'=>$activityId))->save($activity);
        if(empty($result)){
            $this->outputJSON(true,'100001','改变状态失败');
        }
        $this->outputJSON(false,'000000','改变状态成功');
    }
}