<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Controller\AdminBaseController;

class ContentController extends AdminBaseController {


    /**
     * 内容列表
     * @author xy
     * @since 2017/07/25 14:00
     */
    public function content_list(){
        $id = intval(I('id'));
        $keyword = trim(I('keyword'));
        $isPublish = intval(I('is_publish'));
        $isDelete = intval(I('is_delete'));
        $where = array();
        if(!empty($keyword)){
            $where['keyword'] = array('like', '%'.$keyword.'%');
        }
        if(!empty($isPublish)){
            $where['is_publish'] = $isPublish;
        }
        if(!empty($isDelete)){
            $where['is_delete'] = $isDelete;
        }
        if(!empty($id)){
            $where['id'] = $id;
        }
        // 分页
        $allContent = M('independent_content')
            ->field('id')
            ->where($where)
            ->select();
        //获取总条数
        $totalCount = count($allContent);
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
        //内容列表
        $contentList = M('independent_content')
            ->field('*,IF(sort=0,9999999,IFNULL(sort,9999999)) as self_sort')
            ->where($where)
            ->limit($currentPage . ',' . $pageSize)
            ->order('self_sort ASC')
            ->select();

        $this->assign('contentList',$contentList);

        $this->assign('keyword',$keyword);
        $this->assign('isPublish',$isPublish);
        $this->assign('isDelete',$isDelete);
        $this->assign('id', $id);
        $this->display();
    }

    /**
     * 独立内容添加
     * @author xy
     * @since 2017/07/25 11:56
     */
    public function content_add(){
        if(IS_AJAX){
            $keyword = trim(I('keyword'));
            if(empty($keyword)){
                $this->outputJSON(true,'100001','关键字不能为空');
            }
            if(!$this->checkKeyword($keyword)){
                $this->outputJSON(true,'100001','关键字格式不正确或者已存在');
            }
            $title = trim(I('title'));
            if(empty($title)){
                $this->outputJSON(true,'100001','标题不能为空');
            }
            //$content = trim(I('content','',''));
            //if(empty($content)){
            //    $this->outputJSON(true,'100001','内容不能为空');
            //}
            $sort = intval(I('sort'));
            //$isPublish = intval(I('is_publish'));
            //$isDelete = intval(I('is_delete'));

            $data['keyword'] = strtoupper($keyword);
            $data['title'] = $title;
            $data['sort'] = $sort;
            //默认是未发布，编辑后点击发布才发布
            $data['is_publish'] = 2;
            $data['is_delete'] = 1;
            $data['update_time'] = time();
            $data['create_time'] = time();
            //$data['content'] = $content;
            $result = M('independent_content')->add($data);
            if(empty($result)){
                $this->outputJSON(true,'100001','添加失败');
            }
            $this->outputJSON(false,'000000','添加成功');
        }else{
            $this->display();
        }
    }

    /**
     * 独立内容编辑
     * @author xy
     * @since 2017/07/25 11:56
     */
    public function content_edit(){
        $id = intval(I('id'));
        if(IS_AJAX){
            if(empty($id)){
                $this->outputJSON(true,'100001','id不能为空');
            }
            $content = M('independent_content')->where('id = '.$id)->find();
            if(empty($content)){
                $this->outputJSON(true,'100001','id为'.$id.'的数据不存在不能为空');
            }
            $title = trim(I('title'));
            if(empty($title)){
                $this->outputJSON(true,'100001','标题不能为空');
            }
            $editContent = trim(I('content'));
            if(empty($editContent)){
                $this->outputJSON(true,'100001','内容不能为空');
            }
            $sort = intval(I('sort'));
            //$isPublish = intval(I('is_publish'));
            //$isDelete = intval(I('is_delete'));
            $data['title'] = $title;
            $data['seo_keyword'] = trim(I('seo_keyword'));
            $data['seo_description'] = trim(I('seo_description'));
            $data['content'] = $editContent;
            $data['sort'] = $sort;
            //$data['is_publish'] = $isPublish;
            //$data['is_delete'] = $isDelete;
            $data['update_time'] = time();
            $result = M('independent_content')->where('id = '.$id)->save($data);
            if(empty($result)){
                $this->outputJSON(true,'100001','编辑失败');
            }
            $this->outputJSON(false,'000000','编辑成功');
        }else{
            if(empty($id)){
                $this->error('id不能为空',U('Admin/Content/content_list'));
            }
            $content = M('independent_content')->where('id = '.$id)->find();
            if(empty($content)){
                $this->error('id为'.$id.'的数据不存在不能为空',U('Admin/Content/content_list'));
            }
            $this->assign('content',$content);
            $this->display();
        }
    }


    /**
     * ajax验证关键字是否符合要求
     * @author xy
     * @since 2017/07/25 13:37
     */
    public function ajax_check_keyword(){
        $keyword = trim(I('keyword'));
        if(empty($keyword)){
            $this->outputJSON(true,'100001','关键字不能为空');
        }
        if(!$this->checkKeyword($keyword)){
            $this->outputJSON(true,'100001','关键字格式不正确或者已存在');
        }
        $this->outputJSON(false,'000000','验证通过');
    }

    /**
     * 判断关键字是否是英文字符、下划线、字母组成，以及是否已存在
     * @author xy
     * @since 2017/07/25 13:37
     * @param $keyword
     * @return bool
     */
    private function checkKeyword($keyword){
        //判断是否是英文字符、下划线、字母组成
        if(!checkStringIsEnChar($keyword)){
            return false;
        }
        //判断关键词是否存在
        $keyword = strtoupper($keyword);
        $result = M('independent_content')->where(array('keyword'=>$keyword))->find();
        if(!empty($result)){
            return false;
        }
        return true;
    }

    /**
     * 删除或启用内容
     * @author xy
     * @since 2017/07/25 14:29
     */
    public function content_status_change(){
        $id = intval(I('id'));
        if(empty($id)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $content = M('independent_content')->field('id,is_delete')->where(array('id'=>$id))->find();
        if(empty($content)){
            $this->outputJSON(true,'100001','未找到id为'.$id.'的内容');
        }
        $content['update_time'] = time();
        if($content['is_delete'] == 1){
            $content['is_delete'] = 2;
        }else{
            $content['is_delete'] = 1;
        }
        $result = M('independent_content')->where(array('id'=>$id))->save($content);
        if(empty($result)){
            $this->outputJSON(true,'100001','改变状态失败');
        }
        $this->outputJSON(false,'000000','改变状态成功');
    }

    /**
     * 修改内容的发布状态
     * @author xy
     * @since 2017/07/25 14:29
     */
    public function content_publish_change(){
        $id = intval(I('id'));
        if(empty($id)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $content = M('independent_content')->field('id,is_publish')->where(array('id'=>$id))->find();
        if(empty($content)){
            $this->outputJSON(true,'100001','未找到id为'.$id.'的内容');
        }
        $content['update_time'] = time();
        if($content['is_publish'] == 1){
            $content['is_publish'] = 2;
        }else{
            $content['is_publish'] = 1;
        }
        $result = M('independent_content')->where(array('id'=>$id))->save($content);
        if(empty($result)){
            $this->outputJSON(true,'100001','改变状态失败');
        }
        $this->outputJSON(false,'000000','改变状态成功');
    }


}