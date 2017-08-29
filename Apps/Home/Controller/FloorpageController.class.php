<?php
/**
 * Created by PhpStorm.
 * User: songsl
 * Date: 2017/4/7
 * Time: 15:33
 */
namespace Home\Controller;
use Think\Controller;
class FloorpageController extends Controller
{
    public function index() {
        $app_id = I('app_id');
        $media_info = M('app')->where("app_id=$app_id and status=1")->find();
        $id = $media_info['id'];
        if(!$id) {
            redirect(C('DEFAULT_APP'));
        }
        // 获取游戏信息
        $post_data = array();
        $post_data['type'] = 0;
        $post_data['ids'] = $app_id;
        $app_data = array();
        $app_data = $this->get_app_info($post_data);
        $app_info = $app_data['data'];
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
        // 游戏特点
        $game_tag = explode(';', $media_info['game_tag']);
        $this->assign('game_tag', $game_tag);
        // 获取落地页信息
        $this->assign('media_info', $media_info);
        $this->assign('media_id', $id);
        // 视频信息
        $video_id = $media_info['video_id'];
        $video_url = '';
        $video_img = '';
        $video_name = '';
        if(!empty($video_id)) {
            $post_data = array();
            $post_data['v_id'] = $video_id;
            $video_data = array();
            $video_data = $this->video_from_id($post_data);
            if(!$video_data['error']) {
                $video_info = $video_data['data'];
                $video_url = $video_info['video_url'];
                $video_img = C('ZHIYU_URL') .'/' . $video_info['video_img'];
                $video_name = $video_info['video_name'];
            }
        }
        $this->assign('video_name', $video_name);
        $this->assign('video_img', $video_img);
        $this->assign('video_url', $video_url);
        // 精美图片
        $fine_arr = array();
        if(!empty($media_info['fine_imgs_url'])) {
            $fine_arr1 = array();
            $fine_arr1 = explode(',', $media_info['fine_imgs_thumb_url']);
            $fine_arr2 = array();
            $fine_arr2 = explode(',', $media_info['fine_imgs_url']);
            foreach($fine_arr2 as $f_key => &$f_value) {
                $fine_arr[$f_key]['fine_url'] = C('BASE_URL') .'/'. $f_value;
                $fine_arr[$f_key]['fine_thumb_url'] = C('BASE_URL') .'/'. $fine_arr1[$f_key];
            }
        }
        $this->assign('fine_count', count($fine_arr));
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
        $gs_count = count($game_special_list);
        $this->assign('gs_count', $gs_count);
        // 攻略
        $gl_list = M('gonglue')->where("app_id=$app_id")->select();
        $this->assign('gl_list', $gl_list);
        $gl_count = count($gl_list);
        $this->assign('gl_count', $gl_count);
        $this->display('index');
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