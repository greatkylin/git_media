<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25
 * Time: 16:33
 */

namespace Admin\Controller;


use Admin\Service\SearchService;

class SearchController extends AdminBaseController
{
    /**
     * 前台搜索热词列表
     * @author xy
     * @since 2017/09/25 16:43
     */
    public function hot_keyword_list(){
        // 获取搜索条件
        $keyword = trim(I('keyword'));
        $publishStatus = intval(I('publish_status'));
        $type = intval(I('type'));
        $where = array();
        if($type == 1){
            $where['s.type'] = $type;
            if(!empty($keyword)) {
                $where['s.keyword']    = array('like', "%$keyword%");
            }
        }
        if($type == 2){
            $where['s.type'] = $type;
            if(!empty($appId)) {
                $where['s.app_id']    = $appId;
            }
        }

        $nowTime = time();
        if ($publishStatus == 1) { // 待上线
            $where['s.start_time'] = array('gt', $nowTime);
        } elseif ($publishStatus == 2) {// 已上线
            $where['s.start_time'] = array('lt', $nowTime);
            $where['s.end_time'] = array('gt', $nowTime);
        } elseif ($publishStatus == 3) {// 已下线
            $where['s.end_time'] = array('lt', $nowTime);
        }

        $service = new SearchService();
        // 分页
        $totalCount = $service->countHotKeywordNum($where); //获取总条数
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

        $keywordList = $service->getHotKeywordListByPage($where, $currentPage, $pageSize);
        if($keywordList === false){
            $this->error('查询失败');
        }
        $this->assign('type',$type);
        $this->assign('publishStatus',$publishStatus);
        $this->assign('keyword',$keyword);
        $this->assign('appId',$appId);
        $this->assign('keywordList', $keywordList);
        $this->display();
    }

    /**
     * 前台搜索热词推荐添加
     * @author xy
     * @since 2017/09/25 16:45
     */
    public function hot_keyword_add(){
        if(IS_AJAX){
            $type = intval(I('type'));
            if(empty($type)){
                $this->outputJSON(true, '100001','请选择推荐类型');
            }
            $keyword = trim(I('keyword'));
            $appId = intval(I('app_id'));
            $data = array();
            if($type == 1){
                if(empty($keyword)){
                    $this->outputJSON(true, '100001','请选择推荐类型');
                }
                $data['keyword'] = $keyword;
            } else if($type == 2){
                if(empty($appId)){
                    $this->outputJSON(true, '100001','请选择推荐类型');
                }
                $data['app_id'] = $appId;
            }else {
                $this->outputJSON(true, '100001','请选择推荐类型');
            }
            $data['type'] = $type;
            $data['sort'] = intval(I('sort'));
            $data['admin_id'] = $this->user_info['id'];
            $data['start_time'] = strtotime(I('start_time'));
            $data['end_time'] = strtotime(I('end_time'));
            $data['create_time'] = time();
            if (M('search_recommend')->add($data)) {
                $this->outputJSON(false, '000000', '添加成功');
            } else {
                $this->outputJSON(true, '100001', '添加失败');
            }
        }else{
            $reloadUrl = U('Admin/Search/hot_keyword_list');
            $this->assign('reloadUrl', $reloadUrl);
            $this->assign('start_time', time());
            $this->assign('end_time', strtotime("+30 day"));
            $this->display();
        }
    }

    /**
     * 前台搜索热词修改
     * @author xy
     * @since 2017/09/25 16:46
     */
    public function hot_keyword_edit(){
        $id = intval(I('id'));
        if(IS_AJAX){
            if(empty($id)){
                $this->outputJSON(true, '100001', '必填参数缺失');
            }
            $type = intval(I('type'));
            if(empty($type)){
                $this->outputJSON(true, '100001','请选择推荐类型');
            }
            $keyword = trim(I('keyword'));
            $appId = intval(I('app_id'));
            $data = array();
            if($type == 1){
                if(empty($keyword)){
                    $this->outputJSON(true, '100001','请选择推荐类型');
                }else{
                    $data['keyword'] = $keyword;
                }
            } else if($type == 2){
                if(empty($appId)){
                    $this->outputJSON(true, '100001','请选择推荐类型');
                }else{
                    $data['app_id'] = $appId;
                }
            }else {
                $this->outputJSON(true, '100001','请选择推荐类型');
            }

            $data['sort'] = intval(I('sort'));
            $data['admin_id'] = $this->user_info['id'];
            $data['start_time'] = strtotime(I('start_time'));
            $data['end_time'] = strtotime(I('end_time'));
            $data['update_time'] = time();

            $result = M('search_recommend')->where(array('id'=>$id))->save($data);
            if($result){
                $this->outputJSON(false, '000000', '编辑成功');
            } else {
                $this->outputJSON(true, '100001', '编辑失败');
            }

        } else {
            if(empty($id)){
                $this->error('id参数不能为空');
            }
            $keyword = M('search_recommend')->where('id = '.$id)->find();
            if(empty($id)){
                $this->error('未找到id为'.$id.'的记录');
            }
            $reloadUrl = U('Admin/Search/hot_keyword_list');
            $this->assign('reloadUrl', $reloadUrl);
            $this->assign('keyword', $keyword);

            $this->display();
        }
    }

    /**
     * 删除前台搜索热词推荐
     * @author xy
     * @since 2017/09/25 16:47
     */
    public function hot_keyword_delete(){
        $id = intval(I('id'));
        if(empty($id)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $result = M('search_recommend')->where(array('id'=>$id))->delete();
        if(empty($result)){
            $this->outputJSON(true,'100001','删除失败');
        }
        $this->outputJSON(false,'000000','删除成功');
    }
}