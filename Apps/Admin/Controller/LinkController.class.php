<?php
/**
 * 友情链接管理
 * User: xy
 * Date: 2017/9/4
 * Time: 18:19
 */

namespace Admin\Controller;

use Think\Controller;
use Admin\Controller\AdminBaseController;

class LinkController extends AdminBaseController
{

    /**
     * 友情链接列表
     * @author xy
     * @since 2017/09/22 10:57
     */
    public function link_list(){
        $where = array();
        $linkName = trim(I('link_name'));
        if(!empty($linkName)){
            $where['link_name'] = array('like', "%".$linkName."%");
        }
        $isShow = isset($_REQUEST['is_show']) ? intval(I('is_show')) : '-1';
        if($isShow > -1){
            $where['is_show'] = $isShow;
        }

        $totalCount = M('links')->where($where)->count(); //获取总条数
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
        $linkList = M('links')
            ->field('*, IF(sort = 0, 999999999, sort) as new_sort')
            ->where($where)
            ->order('new_sort ASC')
            ->limit($currentPage, $pageSize)
            ->select();
        $this->assign('linkList', $linkList);
        $this->assign('linkName', $linkName);
        $this->assign('isShow', $isShow);
        $this->display();
    }

    /**
     * 添加友情链接
     * @author xy
     * @since 2017/09/05 12:00
     */
    public function link_add(){
        if(IS_AJAX){
            $linkName = trim(I('link_name'));
            if(empty($linkName)){
                $this->outputJSON(true, '100001', '请填写友情链接名称');
            }
            $linkUrl = trim(I('link_url'));
            if(empty($linkUrl)){
                $this->outputJSON(true, '100001', '请填写友情链接地址');
            }
            if(!checkStringIsUrl($linkUrl)){
                $this->outputJSON(true, '100001', '请友情链接地址格式不符合要求');
            }
            $linkTarget = trim(I('link_target'));
            if(empty($linkTarget)){
                $this->outputJSON(true, '100001', '请选择友情链接打开方式');
            }
            if(!in_array($linkTarget, array('_blank', '_self'))){
                $this->outputJSON(true, '100001', '友情链接打开方式错误');
            }
            $linkDescription = trim(I('link_description'));
            $sort = trim(I('sort'));
            $data = array(
                'link_name' => $linkName,
                'link_url' => $linkUrl,
                'link_target' => $linkTarget,
                'admin_id' => $this->user_info['id'],
            );
            if(!empty($linkDescription)){
                $data['link_description'] = $linkDescription;
            }
            if(!empty($sort)){
                $data['sort'] = $sort;
            }
            $result = M('links')->add($data);
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
     * 编辑友情链接
     * @author xy
     * @since 2017/09/05 16:37
     */
    public function link_edit(){
        $linkId = intval(I('link_id'));
        if(IS_AJAX){
            $linkName = trim(I('link_name'));
            if(empty($linkName)){
                $this->outputJSON(true, '100001', '请填写友情链接名称');
            }
            $linkUrl = trim(I('link_url'));
            if(empty($linkUrl)){
                $this->outputJSON(true, '100001', '请填写友情链接地址');
            }
            if(!checkStringIsUrl($linkUrl)){
                $this->outputJSON(true, '100001', '请友情链接地址格式不符合要求');
            }
            $linkTarget = trim(I('link_target'));
            if(empty($linkTarget)){
                $this->outputJSON(true, '100001', '请选择友情链接打开方式');
            }
            if(!in_array($linkTarget, array('_blank', '_self'))){
                $this->outputJSON(true, '100001', '友情链接打开方式错误');
            }
            $linkDescription = trim(I('link_description'));
            $sort = trim(I('sort'));
            $data = array(
                'link_name' => $linkName,
                'link_url' => $linkUrl,
                'link_target' => $linkTarget,
                'admin_id' => $this->user_info['id'],
            );
            if(!empty($linkDescription)){
                $data['link_description'] = $linkDescription;
            }
            if(!empty($sort)){
                $data['sort'] = $sort;
            }

            $result = M('links')->where('link_id = '.$linkId)->save($data);
            if(empty($result)){
                $this->outputJSON(true, '100001', '编辑失败');
            }else {
                $this->outputJSON(false, '000000', '编辑成功成功');
            }
        } else {
            if(empty($linkId)){
                $this->error('id参数不能为空');
            }
            $link = M('links')->where('link_id = '.$linkId)->find();
            if(empty($link)){
                $this->error('未找到id为'.$linkId.'的友情链接');
            }
            $reloadUrl = U('Admin/Link/link_list');
            $this->assign('reload_url', $reloadUrl);
            $this->assign('link', $link);
            $this->display();
        }

    }

    /**
     * 修改友情链接是否显示
     * @author xy
     * @since 2017/09/04 18:32
     */
    public function show_status_change(){
        $linkId = intval(I('link_id'));
        if(empty($linkId)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $link = M('links')->field('link_id, is_show')->where(array('link_id'=>$linkId))->find();
        if(empty($link)){
            $this->outputJSON(true,'100001','未找到id为'.$linkId.'的友情链接');
        }
        $link['admin_id'] = $this->user_info['id'];
        if($link['is_show'] == 1){
            $link['is_show'] = 0;
        }else{
            $link['is_show'] = 1;
        }
        $result = M('links')->where(array('link_id'=>$linkId))->save($link);
        if(empty($result)){
            $this->outputJSON(true,'100001','改变状态失败');
        }
        $this->outputJSON(false,'000000','改变状态成功');
    }

    /**
     * 删除友情链接
     * @author xy
     * @since 2017/09/04 18:32
     */
    public function link_delete(){
        $linkId = intval(I('link_id'));
        if(empty($linkId)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $link = M('links')->field('link_id')->where(array('link_id'=>$linkId))->find();
        if(empty($link)){
            $this->outputJSON(true,'100001','未找到id为'.$linkId.'的友情链接');
        }
        $result = M('links')->where(array('link_id'=>$linkId))->delete();
        if(empty($result)){
            $this->outputJSON(true,'100001','删除失败');
        }
        $this->outputJSON(false,'000000','删除成功');
    }
}