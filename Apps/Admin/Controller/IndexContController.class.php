<?php
/**
 * 首页管理控制器
 * @author xy
 * @since 2017/07/24 14:19
 */
namespace Admin\Controller;

use Think\Controller;
use Admin\Controller\AdminBaseController;

class IndexContController extends AdminBaseController
{
    //添加的分类关键字
    const CATEGORY_KEYWORD = array(
        'HOT_GUIDE'=>'HOT_GUIDE',                 //热门攻略分类关键字
        'NEW_APP_NOTICE'=>'NEW_APP_NOTICE',            //新游预告
    );

    public function __construct()
    {
        //控制器初始化
        parent::__construct();
    }

    /**
     * 图片分类列表
     * @author xy
     * @since 2017/07/24 14:22
     */
    public function category_list(){
        $name = trim(I('name'));
        $keyword = trim(I('keyword'));
        $isDelete = I('is_delete');
        $where = array();
        if(!empty($name)){
            $where['name'] = array('like', '%'.$name.'%');
        }
        if(!empty($keyword)){
            $where['keyword'] = array('like', '%'.strtoupper($keyword).'%');
        }
        if($isDelete == 1){
            $where['is_delete'] = 1;
        }
        if($isDelete == 2){
            $where['is_delete'] = 2;
        }
        // 分页
        $totalCount = count(M('index_column_category')->where($where)->select()); //获取总条数
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
        $categoryList = M('index_column_category')
            ->where($where)
            ->limit($currentPage . ',' . $pageSize)
            ->select();
        $this->assign('categoryList',$categoryList);

        $this->assign('name',$name);
        $this->assign('keyword',$keyword);
        $this->assign('isDelete',$isDelete);
        $this->display();
    }

    /**
     * 添加图片分类
     * @author xy
     * @since 2017/07/24 14:25
     */
    public function category_add(){
        if(IS_AJAX){
            $name = trim(I('name'));
            $keyword = trim(I('keyword'));
            $isDelete = intval(I('is_delete'));
            $imageSize = trim(I('image_size'));
            $numLimit = intval(I('num_limit'));
            if(empty($name)){
                $this->outputJSON(true,'100001','分类名称不能为空');
            }
            if(empty($keyword)){
                $this->outputJSON(true,'100001','关键字不能为空');
            }
            if(!$this->checkKeyword($keyword)){
                $this->outputJSON(true,'100001','关键字格式不正确或者已存在');
            }
            if(empty($imageSize)){
                $this->outputJSON(true,'100001','请填写图片宽高信息');
            }

            $data['name'] = $name;
            $data['keyword'] = strtoupper($keyword);
            $data['is_delete'] = $isDelete;
            $data['image_size'] = $imageSize;
            $data['num_limit'] = $numLimit;
            $result = M('index_column_category')->add($data);
            if(empty($result)){
                $this->outputJSON(true,'100001','添加失败');
            }
            $this->outputJSON(false,'000000','添加成功');
        }else{
            $this->display();
        }
    }

    /**
     * 编辑图片分类
     * @author xy
     * @since 2017/07/24 14:32
     */
    public function category_edit(){
        $id = intval(I('id'));
        if(IS_AJAX){
            if(empty($id)){
                $this->outputJSON(true,'100001','id不能为空');
            }
            $name = trim(I('name'));
            $imageSize = trim(I('image_size'));
            $isDelete = intval(I('is_delete'));
            $numLimit = intval(I('num_limit'));
            if(empty($name)){
                $this->outputJSON(true,'100001','分类名称不能为空');
            }
            if(empty($imageSize)){
                $this->outputJSON(true,'100001','请填写图片宽高信息');
            }
            $data['name'] = $name;
            $data['image_size'] = $imageSize;
            $data['is_delete'] = $isDelete;
            $data['num_limit'] = $numLimit;

            $result = M('index_column_category')->where(array('id'=>$id))->save($data);
            if($result === false){
                $this->outputJSON(true,'100001','编辑失败');
            }
            $this->outputJSON(false,'000000','编辑成功');
        }else{
            if(empty($id)){
                $this->error('id不能为空',U('Admin/IndexCont/category_list'));
            }
            $categoryInfo = M('index_column_category')->where(array('id'=>$id))->find();
            if(empty($categoryInfo)){
                $this->error('未找到id为'.$id.'的分类',U('Admin/IndexCont/category_list'));
            }
            $this->assign('categoryInfo',$categoryInfo);
            $this->display();
        }
    }

    /**
     * 图片分类状态修改
     * @author xy
     * @since 2017/07/24 14:40
     */
    public function category_status_change(){
        $id = intval(I('id'));
        if(empty($id)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $categoryInfo = M('index_column_category')->where(array('id'=>$id))->find();
        if(empty($categoryInfo)){
            $this->outputJSON(true,'100001','未找到id为'.$id.'的分类');
        }
        $currentStatus = $categoryInfo['is_delete'];
        if($currentStatus == 1){
            $cateData['is_delete'] = 2;
        }else{
            $cateData['is_delete'] = 1;
        }
        M()->startTrans();
        $result = M('index_column_category')->where(array('id'=>$id))->save($cateData);
        if(empty($result)){
            M()->rollback();
            $this->outputJSON(true,'100001','改变状态失败');
        }else{
            if($currentStatus == 1){
                $data['is_delete'] = 2;
                $result = M('index_column_content')->where(array('category_id'=>$id,'is_delete'=>1))->save($data);
                if($result === false){
                    M()->rollback();
                    $this->outputJSON(true,'100001','改变状态失败');
                }
            }
            M()->commit();
            $this->outputJSON(false,'000000','改变状态成功');
        }
    }

    /**
     * 内容更表管理
     * @author xy
     * @since 2017/07/24 14:57
     */
    public function content_list(){
        $title = trim(I('title'));
        $categoryId = intval(I('category_id'));
        $isDelete = intval(I('is_delete'));
        $isPublish = intval(I('is_publish'));
        $where = array();
        if(!empty($title)){
            $where['i.title'] = array('like', '%'.$title.'%');
        }
        if(!empty($categoryId)){
            $where['i.category_id'] = $categoryId;
        }
        //默认显示未删除的图片
        if(!empty($isDelete)){
            $where['i.is_delete'] = $isDelete;
        }else{
            $where['i.is_delete'] = 1;
        }
        if(!empty($isPublish)){
            $where['i.is_publish'] = $isPublish;
        }
        // 分页
        $allContent = M('index_column_content')->alias('i')
            ->where($where)
            ->select();
        $totalCount = count($allContent); //获取总条数
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
        //图片列表
        $imageList = M('index_column_content')->alias('i')
            ->field('i.*,IF(i.sort=0,9999999,i.sort) as self_sort,c.name as category_name')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'index_column_category c ON c.id = i.category_id ')
            ->where($where)
            ->limit($currentPage . ',' . $pageSize)
            ->order('i.is_delete ASC, self_sort ASC')
            ->select();

        $this->assign('imageList',$imageList);
        //图片分类列表
        $categoryList = M('index_column_category')
            ->field('id, keyword, is_delete, name')
            ->where('is_delete = 1')
            ->select();
        $this->assign('categoryList',$categoryList);

        $this->assign('title',$title);
        $this->assign('categoryId',$categoryId);
        $this->assign('isDelete',$isDelete);
        $this->assign('isPublish',$isPublish);
        //默认调用的模板
        $tplName = 'content_list';
        //如果当前分类是新游推荐则调用新游推荐的模板
        $keyword = $this->getKeywordByCategoryId($categoryId);
        if($keyword == 'NEW_APP_NOTICE'){
            $tplName = 'new_app_list';
        }

        $this->display($tplName);
    }

    /**
     * 添加图片
     * @author xy
     * @since 2017/07/24 14:58
     */
    public function content_add(){
        if(IS_AJAX){
            $title = trim(I('title'));
            if(empty($title)){
                $this->outputJSON(true,'100001','请填写图片名称');
            }
            $categoryId = intval(I('category_id'));
            if(empty($categoryId)){
                $this->outputJSON(true,'100001','请选择图片分类');
            }
            //判断当前图片的数量是否超过限制
            if(!$this->checkImageNumLimit($categoryId)){
                $this->outputJSON(true,'100001','请上传的图片数量超过限制');
            }
            //获取当前选择分类的关键词
            $keyword = $this->getKeywordByCategoryId($categoryId);
            if(!$keyword){
                $this->outputJSON(true,'100001','获取图片分类关键字失败');
            }
            $imagePath = trim(I('image_path'));
            $hrefLink = trim(I('href_link'));
            $sort = intval(I('sort'));
            $isPublish = intval(I('is_publish'));
            //$isDelete = intval(I('is_delete'));

            $data['category_id'] = $categoryId;
            $data['title'] = $title;
            $data['image_path'] = $imagePath;
            $data['href_link'] = $hrefLink;
            $data['sort'] = $sort;
            $data['is_publish'] = $isPublish;

            //默认未删除
            $data['is_delete'] = 1;
            $data['create_time'] = time();
            $data['update_time'] = time();


            if($keyword == self::CATEGORY_KEYWORD['HOT_GUIDE']){
                //热门攻略需要指定文章分类id的图片
                $artCategoryId = intval(I('art_category_id'));
                $extend['art_category_id'] = $artCategoryId;
                $data['extend'] = unserialize($extend);
            }else if($keyword == self::CATEGORY_KEYWORD['NEW_APP_NOTICE']){
                //添加新游预告
                $appName = trim(I('app_name'));
                $appId = intval(I('app_id'));
                if(empty($appName) || empty($appId)){
                    $this->outputJSON(true,'100001','请填写游戏名称');
                }
                $appInfo = array(
                    'app_publish_time' => strtotime(I('app_publish_time')),
                    'app_id' => $appId,
                    'app_name' => $appName,
                    'app_platform' => intval(I('platform')),
                );
            }
            M()->startTrans();
            $contentId = M('index_column_content')->add($data);
            if(empty($contentId)){
                M()->rollback();
                $this->outputJSON(true,'100001','添加失败');
            }
            if(!empty($appInfo)){
                //添加新游预告
                $appInfo['index_content_id'] = $contentId;
                $result = M('index_app_test_open')->add($appInfo);
                if(empty($result)){
                    M()->rollback();
                    $this->outputJSON(true,'100001','添加失败');
                }
            }
            M()->commit();
            $this->outputJSON(false,'000000','添加成功');
        }else{
            $categoryId = intval(I('category_id'));
            //获取未删除的分类列表
            $where['is_delete'] = 1;
            $where['id'] = $categoryId;
            $category = M('index_column_category')->where($where)->find();
            if(empty($category)){
                $this->error('请先添加图片分类',U('Admin/IndexCont/category_list'));
            }
            $tplName = 'content_add';
            if($category['keyword'] == self::CATEGORY_KEYWORD['NEW_APP_NOTICE']){
                $tplName = 'new_app_add';
            }
            $this->assign('category',$category);
            $this->display($tplName);
        }
    }

    /**
     * 编辑图片
     * @author xy
     * @since 2017/07/24
     */
    public function content_edit(){
        $id = intval(I('id'));
        if(IS_AJAX){
            if(empty($id)){
                $this->outputJSON(true,'100001','id不能为空');
            }
            $title = trim(I('title'));
            if(empty($title)){
                $this->outputJSON(true,'100001','请填写图片名称');
            }
            //分类不允许修改
            $categoryId = intval(I('category_id'));
            if(empty($categoryId)){
                $this->outputJSON(true,'100001','请选择图片分类');
            }
            $keyword = $this->getKeywordByCategoryId($categoryId);
            if(!$keyword){
                $this->outputJSON(true,'100001','获取图片分类关键字失败');
            }
            $imagePath = trim(I('image_path'));
            $hrefLink = trim(I('href_link'));
            $sort = intval(I('sort'));
            $isPublish = intval(I('is_publish'));

            $data['title'] = $title;
            if(!empty($imagePath)){
                $data['image_path'] = $imagePath;
            }
            if(!empty($hrefLink)){
                $data['href_link'] = $hrefLink;
            }
            $data['is_publish'] = $isPublish;
            $data['sort'] = $sort;
            //默认未删除
            $data['is_delete'] = 1;
            $data['update_time'] = time();

            //热门攻略需要指定文章分类id的图片
            if($keyword == self::CATEGORY_KEYWORD['HOT_GUIDE']){
                $artCategoryId = intval(I('art_category_id'));
                $extend['art_category_id'] = $artCategoryId;
                $data['extend'] = unserialize($extend);
            }else if($keyword == self::CATEGORY_KEYWORD['NEW_APP_NOTICE']){
                //添加新游预告
                $appName = trim(I('app_name'));
                $appId = intval(I('app_id'));
                if(empty($appName) || empty($appId)){
                    $this->outputJSON(true,'100001','请填写游戏名称');
                }
                $appInfo = array(
                    'app_publish_time' => strtotime(I('app_publish_time')),
                    'app_id' => $appId,
                    'app_name' => $appName,
                    'app_platform' => intval(I('platform')),
                );
            }
            M()->startTrans();
            $result = M('index_column_content')->where(array('id'=>$id))->save($data);
            if($result === false){
                M()->rollback();
                $this->outputJSON(true,'100001','编辑失败1');
            }
            if(!empty($appInfo)){
                //添加新游预告
                $result = M('index_app_test_open')->where('index_content_id = '.$id)->save($appInfo);
                if($result === false ){
                    M()->rollback();
                    $this->outputJSON(true,'100001','编辑失败2');
                }
            }
            M()->commit();
            $this->outputJSON(false,'000000','编辑成功');
        }else{
            if(empty($id)){
                $this->error('id不能为空',U('Admin/IndexCont/content_list'));
            }
            //获取内容
            $content = M('index_column_content')->where(array('id'=>$id))->find();
            if(empty($content)){
                $this->error('id为'.$id.'的数据不存在',U('Admin/IndexCont/content_list'));
            }
            $content['extend'] = unserialize($content['extend']);
            //获取未删除的分类
            $category = M('index_column_category')->where(array('id'=>$content['category_id']))->find();
            if(empty($category)){
                $this->error('请先添加分类',U('Admin/IndexCont/category_list'));
            }
            $tplName = 'content_edit';
            if($category['keyword'] == self::CATEGORY_KEYWORD['NEW_APP_NOTICE']){
                $tplName = 'new_app_edit';
                $appInfo = M('index_app_test_open')->where(array('index_content_id'=>$content['id']))->find();
                $this->assign('appInfo',$appInfo);
            }
            $this->assign('content',$content);
            $this->assign('category',$category);
            $this->display($tplName);
        }
    }

    /**
     * 添加新游预览
     * @author xy
     * @since 2017/08/31 14:58
     */
    public function new_app_add(){
        if(IS_AJAX){
            $appName = trim(I('app_name'));
            $appId = intval(I('app_id'));
            if(empty($appName) || empty($appId)){
                $this->outputJSON(true,'100001','请填写游戏名称');
            }
            $categoryId = intval(I('category_id'));
            if(empty($categoryId)){
                $this->outputJSON(true,'100001','请选择分类');
            }
            //判断当前图片的数量是否超过限制
            if(!$this->checkImageNumLimit($categoryId)){
                $this->outputJSON(true,'100001','请分类下内容数量超过限制');
            }
            $sort = intval(I('sort'));
            $isPublish = intval(I('is_publish'));

            $data['category_id'] = $categoryId;
            $data['title'] = $appName;
            $data['sort'] = $sort;
            $data['is_publish'] = $isPublish;
            //默认未删除
            $data['is_delete'] = 1;
            $data['create_time'] = time();
            $data['update_time'] = time();
            //扩展字段的内容, 进行序列化
            $data['extend'] = serialize(array(
                'app_publish_time' => strtotime(I('app_publish_time')),
                'app_id' => $appId,
                'app_name' => $appName,
                'app_platform' => intval(I('platform')),
            ));

            $result = M('index_column_content')->add($data);
            if(empty($result)){
                $this->outputJSON(true,'100001','添加失败');
            }
            $this->outputJSON(false,'000000','添加成功');
        }else{
            $categoryId = intval(I('category_id'));
            //获取未删除的分类列表
            $where['is_delete'] = 1;
            $where['id'] = $categoryId;
            $category = M('index_column_category')->where($where)->find();
            if(empty($category)){
                $this->error('当前分类不存在或者已删除',U('Admin/IndexCont/category_list'));
            }
            $this->assign('category',$category);
            $this->display();
        }
    }

    /**
     * 编辑新游预告
     * @author xy
     * @since 2017/08、31 14:45
     */
    public function new_app_edit(){
        $id = intval(I('id'));
        if(IS_AJAX){
            if(empty($id)){
                $this->outputJSON(true,'100001','id不能为空');
            }
            $appName = trim(I('app_name'));
            $appId = intval(I('app_id'));
            if(empty($appName) || empty($appId)){
                $this->outputJSON(true,'100001','请填写游戏名称');
            }
            //分类不允许修改
            $categoryId = intval(I('category_id'));
            if(empty($categoryId)){
                $this->outputJSON(true,'100001','请选择图片分类');
            }
            $keyword = $this->getKeywordByCategoryId($categoryId);
            if(!$keyword){
                $this->outputJSON(true,'100001','获取图片分类关键字失败');
            }
            $sort = intval(I('sort'));
            $isPublish = intval(I('is_publish'));

            $data['title'] = $appName;
            if(!empty($imagePath)){
                $data['image_path'] = $imagePath;
            }
            if(!empty($hrefLink)){
                $data['href_link'] = $hrefLink;
            }

            $data['is_publish'] = $isPublish;
            $data['sort'] = $sort;
            //默认未删除
            $data['is_delete'] = 1;
            $data['update_time'] = time();
            //扩展字段的内容, 进行序列化
            $data['extend'] = serialize(array(
                'app_publish_time' => strtotime(I('app_publish_time')),
                'app_id' => $appId,
                'app_name' => $appName,
                'app_platform' => intval(I('platform')),
            ));
            $result = M('index_column_content')->where(array('id'=>$id))->save($data);
            if($result === false){
                $this->outputJSON(true,'100001','编辑失败');
            }
            if($result === 0){
                $this->outputJSON(false,'000000','无需编辑');
            }
            $this->outputJSON(false,'000000','编辑成功');
        }else{
            if(empty($id)){
                $this->error('id不能为空',U('Admin/IndexCont/content_list'));
            }
            //获取内容
            $app = M('index_column_content')->where(array('id'=>$id))->find();
            if(empty($app)){
                $this->error('id为'.$id.'的数据不存在',U('Admin/IndexCont/content_list'));
            }
            //扩展字段反序列化
            $app['extend'] = unserialize($app['extend']);
            $app['extend']['app_publish_time'] = date('Y-m-d H:i:s', $app['extend']['app_publish_time']);
            //获取未删除的分类
            $category = M('index_column_category')->where(array('id'=>$app['category_id']))->find();
            if(empty($category)){
                $this->error('当前分类不存在或者已删除，请先添加分类',U('Admin/IndexCont/category_list'));
            }

            $this->assign('app',$app);
            $this->assign('category',$category);
            $this->display();
        }
    }

    /**
     * 更改图片的发布状态
     * @author xy
     * @since 2017/07/24 15:27
     */
    public function publish_status_change(){
        $id = intval(I('id'));
        if(empty($id)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $image = M('index_column_content')->where(array('id'=>$id))->find();
        if(empty($image)){
            $this->outputJSON(true,'100001','未找到id为'.$id.'的图片');
        }
        $currentStatus = $image['is_publish'];
        if($currentStatus == 1){
            $data['is_publish'] = 2;
        }else{
            $data['is_publish'] = 1;
        }
        M()->startTrans();
        $result = M('index_column_content')->where(array('id'=>$id))->save($data);
        if(empty($result)){
            M()->rollback();
            $this->outputJSON(true,'100001','改变状态失败');
        }else{
            M()->commit();
            $this->outputJSON(false,'000000','改变状态成功');
        }
    }

    /**
     * 删除图片，逻辑删除
     * @author xy
     * @since 2017/07/24 15:27
     */
    public function image_status_change(){
        $id = intval(I('id'));
        if(empty($id)){
            $this->outputJSON(true,'100001','id不能为空');
        }
        $image = M('index_column_content')->where(array('id'=>$id))->find();
        if(empty($image)){
            $this->outputJSON(true,'100001','未找到id为'.$id.'的图片,无法删除');
        }
        $currentStatus = $image['is_delete'];
        if($currentStatus == 1){
            $data['is_delete'] = 2;
        }else{
            $data['is_delete'] = 1;
        }
        M()->startTrans();
        $result = M('index_column_content')->where(array('id'=>$id))->save($data);
        if(empty($result)){
            M()->rollback();
            $this->outputJSON(true,'100001','删除失败');
        }else{
            M()->commit();
            $this->outputJSON(false,'000000','删除成功');
        }
    }

    /**
     * 发布当前分类下的所有图片
     * @author xy
     * @since 2017/08/22 17:41
     */
    public function publish_all(){
        $categoryId = intval(I('category_id'));
        if(empty($categoryId)){
            $this->outputJSON(true, '100001', '分类id错误');
        }
        $result = M('index_column_category')->find($categoryId);
        if($result === false){
            $this->outputJSON(true,'100001','获取图片栏目分类信息失败');
        }
        if(empty($result)){
            $this->outputJSON(true,'100001','获取图片栏目分类信息失败');
        }
        //当前分类下，所有未发布的图片
        $where = array(
            'category_id' => $categoryId,
            'is_publish' => 2
        );
        $result = M('index_column_content')->where($where)->save(array('is_publish' => 1));
        if($result === false){
            $this->outputJSON(true,'100001','修改发布状态失败');
        }
        if(empty($result)){
            $this->outputJSON(true,'100001','没有需要修改图片');
        }
        $this->outputJSON(false,'000000','发布成功');
    }

    /**
     * ajax方式验证关键词是否符合要求（字母数字下划线，且唯一）
     * @author xy
     * @since 2017/07/24 15:35
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
     * ajax根据分类id获取图片分类信息
     * @author xy
     * @since 2017/07/24 17:47
     */
    public function ajax_get_category_info(){
        $cateId = intval(I('id'));
        if(empty($cateId)){
            $this->outputJSON(true,'100001','id参数错误');
        }
        $result = M('index_column_category')->find($cateId);
        if($result === false){
            $this->outputJSON(true,'100001','获取图片栏目分类信息失败');
        }
        if(empty($result)){
            $this->outputJSON(true,'100001','获取图片栏目分类信息失败');
        }
        $this->outputJSON(false,'000000','获取成功',$result);
    }

    /**
     * ajax方式验证跳转链接是否符合要求
     * @author xy
     * @since 2017/07/24 18:16
     */
    public function ajax_check_url(){
        $hrefLink = trim(I('href_link'));
        if(empty($hrefLink)){
            $this->outputJSON(true,'100001','跳转链接不能为空');
        }
        if(!checkStringIsUrl($hrefLink)){
            $this->outputJSON(true,'100001','跳转链接的格式不正确');
        }
        $this->outputJSON(false,'000000','验证通过');
    }

    /**
     * ajax方式验证分类下图片数量是否超过限制
     * @author xy
     * @since 2018/08/23 14:11
     */
    public function ajax_check_image_limit(){
        $categoryId = intval(I('category_id'));
        if(empty($categoryId)){
            $this->outputJSON(true,'100001','请选择分类');
        }
        if(!$this->checkImageNumLimit($categoryId)){
            $this->outputJSON(true,'100001','该分类下图片超过限制');
        }
        $this->outputJSON(false,'000000','验证通过');
    }

    /**
     * 判断关键字是否是英文字符、下划线、字母组成，以及是否已存在
     * @author xy
     * @since 2017/07/24 15:52
     * @param $keyword
     * @return bool
     */
    private function checkKeyword($keyword){
        //判断是否是英文字符、下划线、字母组成
        if (!checkStringIsEnChar($keyword)){
            return false;
        }
        //判断关键词是否存在
        $keyword = strtoupper($keyword);
        $result = M('index_column_category')->where(array('keyword'=>$keyword))->find();
        if(!empty($result)){
            return false;
        }
        return true;
    }

    /**
     * 判断分类下的图片是否超过限制
     * @author xy
     * @since 2017/08/23 13:58
     * @param integer $categoryId 图片分类表ID
     * @return boolean
     */
    private function checkImageNumLimit($categoryId){
        //判断分类是否符合要求
        if(empty($categoryId) || !is_numeric($categoryId)){
            return false;
        }
        //判断分类是否存在
        $category = M('index_column_category')->where(array('id'=>$categoryId))->find();
        if(empty($category)){
            return false;
        }
        //获取当前分类下所有未删除图片的数量
        $imageNum = M('index_column_content')->where(array('category_id'=>$categoryId, 'is_delete'=>1))->count();
        //判断图片总量的数量是否超过限制数量
        if(!empty($category['num_limit'])){
            if($imageNum >= $category['num_limit']){
                return false;
            }

        }
        return true;
    }

    /**
     * 根据关键词获取图片分类ID
     * @author xy
     * @since 2017/08/25 14:39
     * @param string $keyword 关键字
     * @return bool
     */
    private function getCategoryIdByKeyword($keyword){
        $category = M('index_column_category')->where(array('keyword' => $keyword))->find();
        if($category){
            return $category['id'] ;
        }
        return false;
    }

    /**
     * 根据关键词获取图片分类ID
     * @author xy
     * @since 2017/08/25 14:39
     * @param int $id 表id
     * @return bool
     */
    private function getKeywordByCategoryId($id){
        $category = M('index_column_category')->where(array('id' => $id))->find();
        if($category){
            return $category['keyword'] ;
        }
        return false;
    }

}
