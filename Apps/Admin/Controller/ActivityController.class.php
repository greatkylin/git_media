<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 18:19
 */

namespace Admin\Controller;

use Think\Controller;
use Admin\Controller\AdminBaseController;

class ActivityController extends AdminBaseController
{

    public function activity_list(){

    }

    public function activity_add(){

    }

    public function activity_edit(){

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