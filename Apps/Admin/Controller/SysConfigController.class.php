<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Controller\AdminBaseController;

class SysConfigController extends AdminBaseController {

    /**
     * 配置
     * @author xy
     * @since 2017/07/13
     */
    public function config_list(){
        $name = trim(I('name'));
        $keyword = trim(I('keyword'));
        $isDelete = I('is_delete');
        $where = array();
        if(!empty($name)){
            $where[] = ' `name` like "%'.$name.'%" ' ;
        }
        if(!empty($keyword)){
            $where[] = ' `keyword` like "%'.strtoupper($keyword).'%" ' ;
        }
        if($isDelete == 1){
            $where[] = ' `is_delete` = 1 ';
        }
        if($isDelete == 2){
            $where[] = ' `is_delete` = 2 ';
        }
        //$where[] = ' (IF(sort=0,9999999,sort) OR IFNULL(sort,9999999)) as self_sort';
        $where = implode('AND',$where);
        // 分页
        $totalCount = count(M('sys_config')->where($where)->select()); //获取总条数

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

        $configList = M('sys_config')
            ->field('*, IF(sort = 0, 9999999, IFNULL(sort,9999999)) as self_sort ')
            ->where($where)
            ->limit($currentPage . ',' . $pageSize)
            ->order('self_sort ASC')
            ->select();
        //echo M('position_category')->getLastSql();
        $this->assign('configList',$configList);

        $this->assign('name',$name);
        $this->assign('keyword',$keyword);
        $this->assign('isDelete',$isDelete);
        $this->display();
    }

    /**
     * 添加配置
     * @author xy
     * @since 2017/07/24 19:57
     */
    public function config_add(){
        if(IS_AJAX){
            $name = trim(I('name'));
            $keyword = trim(I('keyword'));
            $isDelete = intval(I('is_delete'));
            $sort = intval(I('sort'));
            $configVal = I('config_value');
            if(empty($name)){
                $this->outputJSON(true,'100001','分类名称不能为空');
            }
            if(empty($keyword)){
                $this->outputJSON(true,'100001','关键字不能为空');
            }
            if(!$this->checkKeyword($keyword)){
                $this->outputJSON(true,'100001','关键字格式不正确或者已存在');
            }
            if(!isset($configVal)){
                $this->outputJSON(true,'100001','配置值不能为空');
            }
            $data['name'] = $name;
            $data['keyword'] = strtoupper($keyword);
            $data['config_value'] = $configVal;
            $data['is_delete'] = $isDelete;
            $data['sort'] = $sort;
            $result = M('sys_config')->add($data);
            if(empty($result)){
                $this->outputJSON(true,'100001','添加失败');
            }
            $this->outputJSON(false,'000000','添加成功');
        }else{
            $this->display();
        }

    }

    /**
     * 编辑配置
     * @author xy
     * @since 2017/07/14 10:48
     */
    public function config_edit(){
        $id = intval(I('id'));
        if(IS_AJAX){
            if(empty($id)){
                $this->outputJSON(true,'100001','id不能为空');
            }
            $name = trim(I('name'));
            $isDelete = intval(I('is_delete'));
            $sort = intval(I('sort'));
            $configVal = I('config_value');
            if(empty($name)){
                $this->outputJSON(true,'100001','分类名称不能为空');
            }
            if(!isset($configVal)){
                $this->outputJSON(true,'100001','配置值不能为空');
            }
            $data['name'] = $name;
            $data['config_value'] = $configVal;
            $data['is_delete'] = $isDelete;
            if(!empty($sort)){
                $data['sort'] = $sort;
            }

            $result = M('sys_config')->where(array('id'=>$id))->save($data);
            if($result === false){
                $this->outputJSON(true,'100001','编辑失败');
            }
            $this->outputJSON(false,'000000','编辑成功');
        }else{
            if(empty($id)){
                $this->error('id不能为空',U('Admin/SysConfig/config_list'));
            }
            $configData = M('sys_config')->where(array('id'=>$id))->find();
            if(empty($configData)){
                $this->error('未找到id为'.$id.'的配置',U('Admin/SysConfig/config_list'));
            }
            $this->assign('configData',$configData);
            $this->display();
        }

    }

    /**
     * 删除或启用岗位类别
     * @author xy
     * @since 2017/07/13
     */
    public function config_status_change(){
        $id = intval(I('id'));
        if(empty($id)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $configData = M('sys_config')->where(array('id'=>$id))->find();
        if(empty($configData)){
            $this->outputJSON(true,'100001','未找到id为'.$id.'的分类');
        }
        $currentStatus = $configData['is_delete'];
        if($currentStatus == 1){
            $configData['is_delete'] = 2;
        }else{
            $configData['is_delete'] = 1;
        }
        M()->startTrans();
        $result = M('sys_config')->where(array('id'=>$id))->save($configData);
        if(empty($result)){
            M()->rollback();
            $this->outputJSON(true,'100001','改变状态失败');
        }else{
            M()->commit();
            $this->outputJSON(false,'000000','改变状态成功');
        }

    }

    /**
     * ajax验证关键字是否符合要求
     * @author xy
     * @since 2017/07/13
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
     * @since 2017/07/3
     * @param $keyword
     * @return bool
     */
    private function checkKeyword($keyword){
        //判断是否是英文字符、下划线、字母组成
        if (!preg_match('/^[_0-9a-z]/i', $keyword)) {
            return false;
        }
        //判读关键词是否存在
        $keyword = strtoupper($keyword);
        $result = M('sys_config')->where(array('keyword'=>$keyword))->find();
        if(!empty($result)){
            return false;
        }
        return true;
    }

    /**
     * 网站信息配置(网站名称，备案信息，seo相关信息等)
     * @author xy
     * @since 2017/08/22 11:43
     */
    public function site_config(){
        if(IS_AJAX){
            $keywordArr = array(
                'SITE_NAME',            //网站名称
                'IPC_INFO',             //ipc信息
                'SITE_SEO_TITLE',       //seo标题
                'SITE_SEO_KEYWORD',     //seo关键字
                'SITE_SEO_DESCRIPTION', //seo描述
            );
            $configInfo = I('config');

            if(!empty($configInfo)){
                M('sys_config')->startTrans();
                foreach ($configInfo as $key => $value){
                    $data = array('config_value' => $value);
                    if(in_array($key, $keywordArr)){
                        $result = M('sys_config')->where(array('keyword' => $key))->save($data);
                        if($result !== false){
                            continue;
                        }else {
                            M('sys_config')->rollback();
                            $this->outputJSON(true, '100001' ,'修改失败');
                        }
                    }else{
                        M('sys_config')->rollback();
                        $this->outputJSON(true, '100001' ,'数据不正确，修改失败');
                    }
                }
                M('sys_config')->commit();
                //更新redis中的缓存
                $this->updateSiteConfigInRedis();
                $this->outputJSON(false, '000000' ,'修改成功');
            }
            $this->outputJSON(true, '100001' ,'修改失败');
        }else {
            $newArr = S('media_site_config');
            if(empty($newArr)){
                $newArr = $this->updateSiteConfigInRedis();
            }
            $this->assign('config', $newArr);
            $this->display();
        }

    }

    /**
     * 更新redis中的网站信息的缓存
     * @author xy
     * @since 2017/08/22 14:08
     */
    protected function updateSiteConfigInRedis(){
        $keywordArr = array(
            'SITE_NAME',
            'IPC_INFO',
            'SITE_SEO_TITLE',
            'SITE_SEO_KEYWORD',
            'SITE_SEO_DESCRIPTION',
        );
        $where['keyword'] = array('IN', $keywordArr);
        $where['is_delete'] = 1;
        $configs = M('sys_config')->field('keyword, config_value')->where($where)->select();
        $newArr = array();
        if(!empty($configs)){
            foreach ($configs as $key => $config){
                $newArr[$config['keyword']] = $config['config_value'];
            }
        }
        if(!empty($newArr)){
            S('media_site_config', $newArr, array('expire' => 86400));
            return $newArr;
        }
        return false;
    }
}