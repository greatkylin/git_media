<?php
/**
 * Created by PhpStorm.
 * User: songsl
 * Date: 2017/5/15
 * Time: 14:40
 */
namespace Admin\Controller;

use Think\Controller;

class AppController extends AdminBaseController
{
    public function __construct()
    {
        //控制器初始化
        parent::__construct();
    }

    public function app_list() {
        // 翻页
        $page     = intval(I('p'));
        $pagesize = intval(I('pagesize'));
        $pagesize = $pagesize > 0 ? $pagesize : DEFAULT_PAGE_SIZE;     //每页显示条数
        // 获取总条数
        $count = M('app')->count();
        $totalPages = ceil($count / $pagesize); //总页数
        $page       = $page > $totalPages ? $totalPages : $page;
        $page       = $page > 0 ? $page : 1;
        $firstRow   = $pagesize*($page-1);
        $this->assign('firstRow', $firstRow);
        $this->assign('page',     $page);
        $this->assign('pages',    $totalPages);
        $this->assign('pagesize', $pagesize);
        $orderby = 'create_time desc';
        $app_list = M('app')->field('id,app_id,status,title')
            ->order($orderby)
            ->limit($firstRow . ',' . $pagesize)
            ->select();
        $ids = '';
        foreach ($app_list as $key => $val) {
            $ids .= $val['app_id'].',';
        }
        $ids = substr($ids, 0, -1);
        if(!empty($ids)) {
            $post_data = array();
            $post_data['type'] = 1;
            $post_data['ids'] = $ids;
            $data = array();
            $data = $this->get_app_info($post_data);
            if(!$data['error']) {
                $data_list = $data['data'];
                foreach ($data_list as $item) {
                    foreach ($app_list as $key => $val) {
                        if($item['app_id'] == $val['app_id']) {
                            $app_list[$key]['app_name'] = $item['app_name'];
                            $app_list[$key]['nickname'] = $item['nickname'];
                        }
                    }
                }
            }
        }
        $this->assign('app_list', $app_list);

        $this->display();
    }

    /**
     * 添加游戏落地页
     */
    public function app_add() {
        if(IS_AJAX) {
            $app_id = intval(I('sel_app'));
            $title = I('title');
            if(!$app_id) {
                $res['error'] = true;
                $res['code'] = 1;
                $res['info'] = '请选择游戏';
                $this->ajaxReturn($res);
            }
            $add_data = array();
            $add_data['admin_id'] = $this->user_info['id'];
            $add_data['create_time'] = time();
            $add_data['status'] = 0;
            $add_data['app_id'] = $app_id;
            $add_data['title'] = $title;
            if(M('app')->add($add_data)) {
                $res['error'] = false;
                $res['code'] = 0;
                $res['info'] = '操作成功';
                $this->ajaxReturn($res);
            }
            else {
                $res['error'] = true;
                $res['code'] = 1;
                $res['info'] = '操作失败';
                $this->ajaxReturn($res);
            }
        }
        else {
            $this->assign('html_url', C('MEDIA_LD_URL'));
            $this->display();
        }
    }

    /**
     * 编辑游戏落地页
     */
    public function app_edit() {
        $id = intval(I('media_id'));
        $media_info = M('app')->where("id=$id")->find();
        if(empty($media_info)) {
            $this->error('参数错误');
        }
        $app_id = $media_info['app_id'];
        if(IS_AJAX) {
            // 基本信息
//            $title = I('title');
            $keywords = I('keywords');
            $description = I('description');
            $android_down_url = I('android_url');
            $ios_down_url = I('ios_down_url');
            $video_id = I('video_id');
            $game_tag = trim(I('game_tag'), ';');
            $play_info = I('editorValue');
            $cover_file_url = I('cover_file_url');
            $fine_pic_url_arr = I('fine_pic_url');
            $fine_thumb_pic_url_arr = I('fine_thumb_pic_url');
            $fine_pic_url = empty($fine_pic_url_arr) ? '' : implode(',', $fine_pic_url_arr);
            $fine_thumb_pic_url = empty($fine_thumb_pic_url_arr) ? '' : implode(',', $fine_thumb_pic_url_arr);
            $special_title = I('special_title');
            $special_info = I('special_info');

            $media_id = $id;
            $game_edit_data = array();
            $game_edit_data['id'] = $media_id;
            if(substr($android_down_url, 0, 1) == '/') {
                $android_down_url = substr($android_down_url, 1);
            }
//            $game_edit_data['title'] = $title;
            $game_edit_data['keywords'] = $keywords;
            $game_edit_data['description'] = $description;
            $game_edit_data['android_down_url'] = $android_down_url;
            $game_edit_data['ios_down_url'] = $ios_down_url;
            $game_edit_data['video_id'] = $video_id;
            $game_edit_data['game_tag'] = $game_tag;
            $game_edit_data['play_info'] = $play_info;
            if($media_info['cover_imgs_url'] != $cover_file_url && !empty($cover_file_url)) {
                $game_edit_data['cover_imgs_url'] = $cover_file_url;
            }
            if($media_info['fine_imgs_url'] != $fine_pic_url && !empty($fine_pic_url)) {
                $game_edit_data['fine_imgs_url'] = $fine_pic_url;
                $game_edit_data['fine_imgs_thumb_url'] = $fine_thumb_pic_url;
            }
            $game_edit_data['admin_id'] = $this->user_info['id'];
            $game_edit_data['special_title'] = $special_title;
            $game_edit_data['special_info'] = $special_info;
            M('app')->startTrans();
            $is_update_ma = true;
            if(M('app')->save($game_edit_data) === false) {
                write_log('152', 'INFO', 'app.log');
                $is_update_ma = false;
            }

            // 攻略
            $is_gl_success = true;
            $edit_gl_id = I('edit_gl_id');
            $edit_gl_title = I('edit_gl_title');
            $edit_gl_url = I('edit_gl_url');
            // 添加攻略
            $gl_title_arr = I('gl_title');
            $gl_url_arr = I('gl_url');
            $gl_add_data = array();
            if(!empty($gl_title_arr)) {
                foreach ($gl_title_arr as $key => $value) {
                    $gl_add_data[$key]['app_id'] = $app_id;
                    $gl_add_data[$key]['gl_title'] = $value;
                    $gl_add_data[$key]['gl_url'] = $gl_url_arr[$key];
                    $gl_add_data[$key]['admin_id'] = $this->user_info['id'];
                    $gl_add_data[$key]['create_time'] = time();
                }
                if(M('gonglue')->addAll($gl_add_data) === false) {
                    write_log('173', 'INFO', 'app.log');
                    $is_gl_success = false;
                }
            }
            // 编辑攻略
            $is_update_egl = true;
            if(!empty($edit_gl_id)) {
                foreach($edit_gl_id as $egl_key => $egl_val) {
                    $gl_update_data = array();
                    $gl_update_data['gl_title'] = $edit_gl_title[$egl_key];
                    $gl_update_data['gl_url'] = $edit_gl_url[$egl_key];
                    if(M('gonglue')->where("id=$egl_val")->save($gl_update_data) === false) {
                        write_log(M('game_special')->getLastSql().'----185', 'INFO', 'app.log');
                        $is_update_egl = false;
                        break;
                    }
                }
            }


            // 公司
            $is_company_success = true;
            $supplier_id = I('supplier_id');
            if(intval($supplier_id) > 0) {
                $is_company_success = false;
                $other_game = trim(I('other_game'), ';');
                $supplier_info = I('supplier_info');
                $supplier_icon = I('company_icon_url');
                $company_add_data = array();
                $company_add_data['supplier_id'] = $supplier_id;
                $company_add_data['other_game'] = $other_game;
                $company_add_data['supplier_info'] = $supplier_info;
                if(!empty($supplier_icon)) {
                    $company_add_data['supplier_icon'] = $supplier_icon;
                }
                $company_add_data['admin_id'] = $this->user_info['id'];
                $company_data = array();
                $company_data = $this->update_company($company_add_data);
                if(!$company_data['error']) {
                    $is_company_success = 1;
                }
            }


            // 专辑关联游戏
            $is_add_gs_success = true;
            $del_arr = I('del_arr');
            $special_app_id_arr = I('special_app_id');
            $game_info_arr = I('game_info');
            $edit_gs_id_arr = I('edit_gs_id');
            $edit_game_info_arr = I('edit_game_info');
            $special_add_data = array();
            // 删除
            $is_del_gs = true;
            if(!empty($del_arr)) {
                $del_str = implode(',', $del_arr);
                if(M('game_special')->where("id in ({$del_str})")->delete() === false) {
                    write_log($del_str.'232', 'INFO', 'app.log');
                    $is_del_gs = false;
                }
                write_log(M('game_special')->getLastSql().'232', 'INFO', 'app.log');
            }
            // 编辑
            $is_update_egs = true;
            if(!empty($edit_gs_id_arr)) {
                foreach($edit_gs_id_arr as $egs_key => $egs_val) {
                    if(M('game_special')->where("id=$egs_val")->save(array('game_info' => $edit_game_info_arr[$egs_key])) === false) {
                        write_log('242', 'INFO', 'app.log');
                        $is_update_egs = false;
                        break;
                    }
                }
            }
            // 添加
            $is_gs_success = true;
            if(!empty($special_app_id_arr)) {
                foreach ($special_app_id_arr as $s_key => $s_val) {
                    $special_add_data[$s_key]['app_id'] = $s_val;
                    $special_add_data[$s_key]['game_info'] = $game_info_arr[$s_key];
                    $special_add_data[$s_key]['ma_id'] = $media_id;
                    $special_add_data[$s_key]['admin_id'] = $this->user_info['id'];
                    $special_add_data[$s_key]['create_time'] = time();
                }
                if(M('game_special')->addAll($special_add_data) === false) {
                    write_log('258', 'INFO', 'app.log');
                    $is_gs_success = false;
                }
            }

            if($is_update_ma && $is_gl_success && $is_company_success && $is_add_gs_success && $is_gs_success && $is_update_egs && $is_del_gs && $is_update_egl) {
                M('app')->commit();
                if($media_info['android_down_url'] != $android_down_url && !empty($android_down_url)) {
                    unlink(realpath($media_info['android_down_url']));
                }
                if($media_info['cover_imgs_url'] != $cover_file_url && !empty($cover_file_url)) {
                    unlink(realpath($media_info['cover_imgs_url']));
                }
                if($media_info['fine_imgs_url'] != $fine_pic_url && !empty($fine_pic_url)) {
                    $del_fine_arr = explode(',', $media_info['fine_imgs_url']);
                    foreach($del_fine_arr as $del_f_val) {
                        unlink(realpath($del_f_val));
                    }
                    $del_fine_thumb_arr = explode(',', $media_info['fine_imgs_thumb_url']);
                    foreach($del_fine_thumb_arr as $del_f_t_val) {
                        unlink(realpath($del_f_t_val));
                    }
                }
                if($company_data['code'] == 2 && !$company_data['error']) {
                    unlink(realpath(I('del_company_icon_url')));
                }
                $res['error'] = false;
                $res['code'] = 0;
                $res['info'] = '操作成功';
                $this->ajaxReturn($res);
            }
            else {
                M('app')->rollback();
                $res['msg'] = $is_update_ma .'---'. $is_gl_success .'---'. $is_company_success .'---'. $is_add_gs_success .'---'.
                    $is_gs_success .'---'. $is_update_egs .'---'.$is_del_gs .'---'. $is_update_egl;
                $res['error'] = true;
                $res['code'] = 2;
                $res['info'] = '操作失败';
                $this->ajaxReturn($res);
            }
        }
        else {
            // 获取游戏信息
            $post_data = array();
            $post_data['type'] = 0;
            $post_data['ids'] = $app_id;
            $data = array();
            $data = $this->get_app_info($post_data);
            $app_info = $data['data'];
            // 游戏图片处理
            $pic_url = array();
            if(!empty($app_info['pic_url'])) {
                $pic_url = explode(',', $app_info['pic_url']);
            }
            $this->assign('pic_url', $pic_url);
            // 公司图标
            $app_info['old_supplier_icon'] = $app_info['supplier_icon'];
            $app_info['supplier_icon'] = C('BASE_URL') . '/' . $app_info['supplier_icon'];
            $this->assign('app_info', $app_info);

            // 高清图片
            if(!empty($media_info['cover_imgs_url'])) {
                $media_info['cover_imgs_url'] = C('BASE_URL') . '/' . $media_info['cover_imgs_url'];
            }
            // 获取落地页信息
            $this->assign('media_info', $media_info);
            $this->assign('media_id', $id);
            // 精美图片
            $fine_arr = array();
            if(!empty($media_info['fine_imgs_thumb_url'])) {
                $fine_arr = explode(',', $media_info['fine_imgs_thumb_url']);
                foreach($fine_arr as &$f_value) {
                    $f_value = C('BASE_URL') .'/'. $f_value;
                }
            }
            $this->assign('fine_arr', $fine_arr);
            // 专辑游戏
            $game_special_list = M('game_special')->where("ma_id=$id")->select();
            $apps = M('game_special')->field('GROUP_CONCAT(app_id) as app_ids')->where("ma_id=$id")->group('ma_id')->find();
            $ids = $apps['app_ids'];
            if(!empty($ids)) {
                $post_data = array();
                $post_data['type'] = 1;
                $post_data['ids'] = $ids;
                $data = array();
                $data = $this->get_app_info($post_data);
                if(!$data['error']) {
                    $data_list = $data['data'];
                    foreach ($data_list as $item) {
                        foreach ($game_special_list as $key => $val) {
                            if($item['app_id'] == $val['app_id']) {
                                $game_special_list[$key]['app_name'] = $item['app_name'];
                                $game_special_list[$key]['type_name'] = $item['type_name'];
                                $game_special_list[$key]['icon'] = $item['icon'];
                                $game_special_list[$key]['start_score'] = $item['start_score'];
                            }
                        }
                    }
                }
            }
            $this->assign('game_special_list', $game_special_list);
            $gs_html = $this->fetch('special_edit');
            $this->assign('gs_html', $gs_html);
            $gs_count = count($game_special_list);
            $this->assign('gs_count', $gs_count);
            // 攻略
            $gl_list = M('gonglue')->where("app_id=$app_id")->select();
            $this->assign('gl_list', $gl_list);
            $gl_count = count($gl_list);
            $this->assign('gl_count', $gl_count);

            $this->display();
        }
    }

    /**
     * 落地页上下架
     */
    public function app_status() {
        $status = I('app_status');
        $id = I('game_id');
        $where = array();
        $where['id'] = $id;
        $app_info = M('app')->where($where)->find();
        if(empty($app_info)) {
            $res['error'] = true;
            $res['code'] = 1;
            $res['info'] = '参数错误';
            $this->ajaxReturn($res);
        }
        $update_date = array();
        $update_date['status'] = $status;
        if(M('app')->where($where)->save($update_date)) {
            $res['error'] = false;
            $res['code'] = 0;
            $res['info'] = '操作成功';
            $this->ajaxReturn($res);
        }
        else {
            $res['error'] = true;
            $res['code'] = 2;
            $res['info'] = '操作失败';
            $this->ajaxReturn($res);
        }
    }
    
    /**
     * 获取app_id
     */
    public function get_app_id_html() {
        $post_data = array();
        $post_data['name'] = I('appname');
        $data = array();
        $data = $this->get_app_info_from_name($post_data);
        $html = '';
        $data_list = $data['data'];
        $count = count($data_list);
        if($count > 1) {
            $html = '<select class="select" size="1" name="sel_app" id="sel_app">';
            foreach ($data_list as $key => $value) {
                $html .= "<option value='".$value['app_id']."'>".$value['app_id']."</option>";
            }
            $html .= "</select>";
        }
        else {
            $app_id = $data_list[0]['app_id'];
            $res['app_id'] = $app_id;
            $html = "<input type='hidden' value='".$app_id."' id='sel_app' name='sel_app'/>".$app_id;
        }
        $res['count'] = $count;
        $res['error'] = false;
        $res['html'] = $html;
        $res['code'] = 0;
        $res['info'] = '操作成功';
        $this->ajaxReturn($res);
    }

    /**
     * 添加专辑游戏
     */
    public function get_app_special() {
//        $ma_id = I('media_id');
//        if(M('game_special')->where("ma_id={$ma_id}")->count() > 5) {
//            $res['error'] = false;
//            $res['html'] = '';
//            $res['code'] = 0;
//            $res['info'] = '专辑游戏最多只能添加6款';
//            $this->ajaxReturn($res);
//        }
        $app_id = I('appid');
        $dd_length_name = I('dd_length_name');
        $post_data = array();
        $post_data['type'] = 0;
        $post_data['ids'] = $app_id;
        $data = array();
        $data = $this->get_app_info($post_data);
        $html = '';
        if(!$data['error']) {
            $data_info = $data['data'];
            $this->assign('app_info', $data_info);
            $this->assign('dd_length_name', $dd_length_name);
            $html = $this->fetch('special');
        }
        $res['error'] = false;
        $res['html'] = $html;
        $res['code'] = 0;
        $res['info'] = '操作成功';
        $this->ajaxReturn($res);
    }

    /**
     * 查询视频
     */
    public function get_video() {
        $video_name = I('v_name');
        $post_data = array();
        $post_data['type'] = 2;
        $post_data['v_name'] = $video_name;
        $data = array();
        $data = $this->video_from_name($post_data);
        $html = '';
        if(!$data['error']) {
            $video_list = $data['data'];
        }
        $res['error'] = false;
        $res['list'] = $video_list;
        $res['code'] = 0;
        $res['info'] = '操作成功';
        $this->ajaxReturn($res);
    }

    /**
     * 文件上传
     */
    public function app_upload() {
        $fun = I('fun');
        $fun_arr = array('upload_app', 'cover_upload', 'fine_upload', 'supplier_icon');
        if(in_array($fun, $fun_arr)) {
            $this->$fun();
        }
        else {
            echo '未找到方法';
        }
    }

    /**
     * 上传app
     */
    protected function upload_app() {

        $this->uploadchunk();
    }

    /**
     * 上传高清封面图
     */
    protected function cover_upload() {
        $file_conf = C('FILE_CONFIG.APP_COVER_PIC_CONF');
        $upload = new \Think\Upload();
        $upload->maxSize = $file_conf['max_size'];      // 设置附件上传大小
        $upload->exts = $file_conf['exts'];             // 设置附件上传类型
        $path = realpath(ROOT_PATH . $file_conf['file_path']) . '/';
        $upload->rootPath = $path;                      // 设置附件上传根目录
        $upload->savePath = $file_conf['save_path'];    // 保存路径目录
        $info = $upload->upload();
        $res = array('error' => true, 'info' => '');
        if (!$info) {
            $res['error'] = true;
            $res['info'] = $upload->getError();
            $this->ajaxReturn($res);
        } else {
//            $thumb_url = thumb_image($info, $file_conf);    // 生成缩略图
//            $res['thumb_url'] = $thumb_url;
            $res['error'] = false;
            $res['url'] = $file_conf['file_path'] . '/' . $info['file']['savepath'] . $info['file']['savename'];
            $res['info'] = $info;
            $this->ajaxReturn($res);
        }
    }

    /**
     * 上传精美图片
     */
    protected function fine_upload() {
        $file_conf = C('FILE_CONFIG.APP_FINE_PIC_CONF');
        $upload = new \Think\Upload();
        $upload->maxSize = $file_conf['max_size'];      // 设置附件上传大小
        $upload->exts = $file_conf['exts'];             // 设置附件上传类型
        $path = realpath(ROOT_PATH . $file_conf['file_path']) . '/';
        $upload->rootPath = $path;                      // 设置附件上传根目录
        $upload->savePath = $file_conf['save_path'];    // 保存路径目录
        $info = $upload->upload();
        $res = array('error' => true, 'info' => '');
        if (!$info) {
            $res['error'] = true;
            $res['info'] = $upload->getError();
            $this->ajaxReturn($res);
        } else {
            $thumb_url = thumb_image($info, $file_conf);    // 生成缩略图
            $res['thumb_url'] = $thumb_url;
            $res['error'] = false;
            $res['url'] = $file_conf['file_path'] . '/' . $info['file']['savepath'] . $info['file']['savename'];
            $res['info'] = $info;
            $this->ajaxReturn($res);
        }
    }

    /**
     * 上传厂商商标
     */
    protected function supplier_icon() {
        $file_conf = C('FILE_CONFIG.COMPANY_ICON_CONF');
        $upload = new \Think\Upload();
        $upload->maxSize = $file_conf['max_size'];      // 设置附件上传大小
        $upload->exts = $file_conf['exts'];             // 设置附件上传类型
        $path = realpath(ROOT_PATH . $file_conf['file_path']) . '/';
        $upload->rootPath = $path;                      // 设置附件上传根目录
        $upload->savePath = $file_conf['save_path'];    // 保存路径目录
        $info = $upload->upload();
        $res = array('error' => true, 'info' => '');
        if (!$info) {
            $res['error'] = true;
            $res['info'] = $upload->getError();
            $this->ajaxReturn($res);
        } else {
            $res['error'] = false;
            $res['url'] = $file_conf['file_path'] . '/' . $info['file']['savepath'] . $info['file']['savename'];
            $res['info'] = $info;
            $this->ajaxReturn($res);
        }
    }



    /**
     * 获取App信息
     */
    public function get_app_info($data) {
        $type = $data['type'];      // 0表示一条数据，1表示多条数据
        $id = $data['ids'];
        if(empty($id)) {
            $res['error'] = true;
            $res['code'] = 1;
            $res['info'] = '参数错误，缺少ID';
            return $res;
        }
        if($type == 0) {
            $app_info = M('', C('DB_ZHIYU.DB_PREFIX'), 'DB_ZHIYU')->query("SELECT al.*,au.nickname,at.type_name,alist.sj_time,
                        s.supplier_id,s.supplier_name,s.supplier_icon,s.supplier_info,s.other_game
                        FROM (SELECT * FROM ".C('DB_ZHIYU.DB_PREFIX')."app_lib WHERE app_id={$id}) al
                        left JOIN ".C('DB_ZHIYU.DB_PREFIX')."app_list alist on alist.app_id=al.app_id 
                        left JOIN ".C('DB_ZHIYU.DB_PREFIX')."admin_user au on au.id=al.admin_id 
                        left JOIN ".C('DB_ZHIYU.DB_PREFIX')."app_type at on al.app_type1=at.id 
                        left JOIN ".C('DB_ZHIYU.DB_PREFIX')."supplier s on s.supplier_id=al.supplier_id");
            $app_info = $app_info[0];
            if(!empty($app_info['app_file_url'])) {
                $app_info['app_file_url'] = format_url($app_info['app_file_url']);
            }
            if(!empty($app_info['icon'])) {
                $app_info['icon'] = format_url($app_info['icon']);
            }
            if(!empty($app_info['pic_url'])) {
                $pic_url = explode(',', $app_info['pic_url']);
                foreach ($pic_url as &$val) {
                    $val = format_url($val);
                }
                $pic_url = implode(',', $pic_url);
            }
            $app_info['pic_url'] = $pic_url;
        }
        else if($type == 1) {
            $list_where = array();
            $list_where['al.app_id'] = array('in', $id);
            $app_info = M('', C('DB_ZHIYU.DB_PREFIX'), 'DB_ZHIYU')->query("SELECT al.*,au.nickname,at.type_name,alist.sj_time,
                        s.supplier_id,s.supplier_name,s.supplier_icon,s.supplier_info,s.other_game
                        FROM (SELECT * FROM ".C('DB_ZHIYU.DB_PREFIX')."app_lib WHERE app_id in ($id)) al
                        left JOIN ".C('DB_ZHIYU.DB_PREFIX')."app_list alist on alist.app_id=al.app_id 
                        left JOIN ".C('DB_ZHIYU.DB_PREFIX')."admin_user au on au.id=al.admin_id 
                        left JOIN ".C('DB_ZHIYU.DB_PREFIX')."app_type at on al.app_type1=at.id 
                        left JOIN ".C('DB_ZHIYU.DB_PREFIX')."supplier s on s.supplier_id=al.supplier_id");
//                M('app_lib al', C('DB_ZHIYU.DB_PREFIX'), 'DB_ZHIYU')->field('al.*,au.nickname')
//                ->where($list_where)
//                ->join(C('DB_PREFIX') . 'admin_user au on au.id=al.admin_id', 'left')
//                ->select();
            if(empty($app_info)) {
                $res['error'] = false;
                $res['code'] = 2;
                $res['info'] = '未找到数据';
                return $res;
            }
            foreach($app_info as $key => &$value) {
                if(!empty($value['app_file_url'])) {
                    $value['app_file_url'] = format_url($value['app_file_url']);
                }
                if(!empty($value['icon'])) {
                    $value['icon'] = format_url($value['icon']);
                }
                if(!empty($value['pic_url'])) {
                    $pic_url = explode(',', $value['pic_url']);
                    foreach ($pic_url as &$val) {
                        $val = format_url($val);
                    }
                    $pic_url = implode(',', $pic_url);
                }
                $value['pic_url'] = $pic_url;
            }
        }
        else {
            $res['error'] = true;
            $res['code'] = 3;
            $res['info'] = '参数错误';
            return $res;
        }
        $res['error'] = false;
        $res['code'] = 0;
        $res['data'] = $app_info;
        $res['info'] = '加载成功';
        return $res;
    }

    /**
     * 根据游戏名查找游戏信息
     */
    public function get_app_info_from_name($data) {
        $app_name = $data['name'];
        if(empty($app_name)) {
            $res['error'] = true;
            $res['code'] = 1;
            $res['info'] = '参数错误，缺少游戏名';
            return $res;
        }
        $where = array();
        $where['app_name'] = array('like', "%{$app_name}%");
        $app_list = M('app_lib', C('DB_ZHIYU.DB_PREFIX'), 'DB_ZHIYU')->field('app_id')->where($where)->select();
        if(empty($app_list)) {
            $res['error'] = true;
            $res['code'] = 2;
            $res['info'] = '未找到数据';
            return $res;
        }
        $res['error'] = false;
        $res['code'] = 0;
        $res['data'] = $app_list;
        $res['info'] = '加载成功';
        return $res;
    }

    /**
     * 查询视频库
     */
    public function video_from_name($data) {
        $video_name = trim($data['v_name']);
        $use_type = empty($data) ? 2 : $data['type'];
        $where = array();
        if(!empty($video_name)) {
            $where['video_name'] = array('like', "%{$video_name}%");
        }
        $where['_string'] = "FIND_IN_SET({$use_type}, use_type)";
        $video_list = M('video_lib', C('DB_ZHIYU.DB_PREFIX'), 'DB_ZHIYU')->field('video_id as value,video_name as label')->where($where)->select();
        $res['error'] = false;
        $res['code'] = 0;
        $res['data'] = $video_list;
        $res['info'] = '获取成功';
        return $res;
    }

    /**
     * 查询视频库
     */
    public function video_from_id($data) {
        $video_id = trim($data['v_id']);
        $where = array();
        $where['video_id'] = $video_id;
        $video_ifno = M('video_lib', C('DB_ZHIYU.DB_PREFIX'), 'DB_ZHIYU')->field('video_id,video_name,video_url,video_img')->where($where)->find();
        $res['error'] = false;
        $res['code'] = 0;
        $res['data'] = $video_ifno;
        $res['info'] = '获取成功';
        return $res;
    }

    /**
     * 更新公司信息
     */
    public function update_company($data) {
        $company_add_data = array();
        $supplier_id = $data['supplier_id'];
        $supplier = M('supplier', C('DB_ZHIYU.DB_PREFIX'), 'DB_ZHIYU')->where("supplier_id={$supplier_id}")->find();
        if(!$supplier) {
            $res['error'] = true;
            $res['code'] = 1;
            $res['info'] = '操作失败';
            return $res;
        }
        $company_add_data['supplier_id'] = $supplier_id;
        $company_add_data['other_game'] = $data['other_game'];
        $company_add_data['supplier_info'] = $data['supplier_info'];
        $supplier_icon = I('supplier_icon');
        if($supplier['supplier_icon'] != $supplier_icon && !empty($supplier_icon)) {
            $company_add_data['supplier_icon'] = $supplier_icon;
        }
        $company_add_data['admin_id'] = I('admin_id');
        if(M('supplier', C('DB_ZHIYU.DB_PREFIX'), 'DB_ZHIYU')->save($company_add_data) === false) {
            $res['error'] = true;
            $res['code'] = 3;
            $res['info'] = '操作失败';
            return $res;
        }
        if($supplier['supplier_icon'] != $supplier_icon && !empty($supplier_icon)) {
            $res['error'] = false;
            $res['code'] = 2;
            $res['info'] = '操作成功';
            return $res;
        }
        $res['error'] = false;
        $res['code'] = 0;
        $res['info'] = '操作成功';
        return $res;
    }
}
