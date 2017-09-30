<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Auth;
class AdminBaseController extends Controller {
    public $Config;
    public $user_info;          // 登陆管理员信息
    public $app_list_status_arr;    // 应用列表状态
    public function __construct() {
        //控制器初始化
        parent::__construct();
        //session不存在时，不允许直接访问
        $sid = I('sid');
        // 单点登录时把SESSION_AUTO_START设置为false
        if(!empty($sid)) {
            session_id($sid);
        }
        session('[start]');
        $this->user_info = session('user_info');

        $redirect_url = C('BASE_URL') . "/admin/index/index";
        $login_url = C('ZHIYU_URL')."/Admin/login/index?redirect_url=" . urlencode($redirect_url);
        $this->assign('login_url', $login_url);
        $jump_url = "javascript:top.location.href='". $login_url ."'";
        if(!$this->user_info){
            $this->error('还没有登录，正在跳转到登录页', $jump_url);
        }
        if(!empty($sid) && !empty($this->user_info)) {
            redirect($redirect_url);
        }

        //session存在时，不需要验证的权限
        $not_check = C('NOT_AUTH');
        //当前操作的请求                 模块名/方法名
        if(!in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $not_check)){
            //下面代码动态判断权限
            if(is_array($this->user_info['user_auth'])) {
                if(!in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $this->user_info['user_auth']) && !in_array($this->user_info['id'], C('SUPER_ADMIN'))) {
                    if($this->user_info['user_auth'] != 'super_admin') {
                        if(isset($_SERVER['HTTP_REFERER'])) {
                            $this->error('没有权限', $_SERVER['HTTP_REFERER']);
                        }
                        else {
                            $this->error('没有权限', $jump_url);
                        }
                    }
                }
            }
            else {
                if($this->user_info['user_auth'] != 'super_admin') {
                    if(isset($_SERVER['HTTP_REFERER'])) {
                        $this->error('没有权限', $_SERVER['HTTP_REFERER']);
                    }
                    else {
                        $this->error('没有权限', $jump_url);
                    }
                }
            }
        }
//        $this->assign('nav', get_nav());
        $this->assign('user_info', $this->user_info);

        // 默认翻页中的每页显示条数 如果有设置则缓存

        $i_pagesize = intval(I('pagesize'));
        if(!$i_pagesize) {
            $pagesize = session('pagesize');
            if(empty($pagesize)) {
                session('pagesize', 10);
                define('DEFAULT_PAGE_SIZE', 10);
            }
            else {
                define('DEFAULT_PAGE_SIZE', $pagesize);
            }
        }
        else {
            session('pagesize', $i_pagesize);
            define('DEFAULT_PAGE_SIZE', $i_pagesize);
        }

        // 游戏列表状态为1,2时才可用于查询增加等操作1表示正式上架，2表示测试上架
        $this->app_list_status_arr = array(1,2);
    }

    /**
     * 大文件分片上传
     */
    public function uploadchunk()
    {
        // 唯一ID
        $guid = I('guid');
        // 分片序号
        $chunk = I('chunk', 0, 'intval');
        // 总分片数
        $chunks = I('chunks', 0, 'intval');
        // 分片保存名称
        $chunkName = (string)($chunk + 100000);
        $upload = new \Think\Upload();// 实例化上传类
        $upload->exts = array('apk');    // 设置附件上传类型
        $upload->maxSize = 5 * 1024 * 1024;// 设置附件上传大小
        $upload->rootPath = './'; // 设置附件上传根目录
//        $file_conf = C('FILE_CONFIG.APPLIB_CONF');
        $upload->savePath = '/Uploads/app/android/tmp/' . $guid;
        $upload->saveExt = 'tmp';
        $upload->saveName = $chunkName;
        $upload->replace = true;      // 替换同名文件

        // 上传单个文件
        $info = $upload->uploadOne($_FILES['file']);

        if (!$info) {
            // 上传错误提示错误信息
            $res['info'] = $upload->getError();
            $res['error'] = true;
            $res['code'] = 2;
            $this->ajaxReturn($res);
//            $this->error($upload->getError(), '', true);
        } else {
            // 最后一个分片,或者文件太小没有分片
            if ($chunks == 0 || $chunks == $chunk + 1) {
                $data = $this->_mergeChunk($info);
                if ($data) {
                    $res['info'] = '上传完成';
                    $res['size'] = $data['size'];
                    $res['url'] = $data['path'];
                    $res['error'] = false;
                    $res['code'] = 0;
                    $this->ajaxReturn($res);
//                    $this->success($data, '', true);
                }
            } else {
                $res['info'] = '上传成功';
                $res['error'] = false;
                $res['code'] = 1;
                $this->ajaxReturn($res);
//                $this->success('上传成功', '', true);
            }
        }
        $res['info'] = '未知错误';
        $res['error'] = true;
        $res['code'] = 2;
        $this->ajaxReturn($res);
//        $this->error('未知错误', '', true);
    }

    /**
     * 合并文件
     * @param $info
     * @return array|bool
     */
    private function _mergeChunk($info)
    {
        $tmpDir = '.' . $info['savepath'];
        $tmpDir = rtrim($tmpDir, '/') . "/";
        if (is_dir($tmpDir)) {
            $time = time();
            // 根目录
            $rootPath = './';
            // 文件目录
//            $file_conf = C('FILE_CONFIG.APPLIB_CONF');
            $videoPath = '/Uploads/app/android/' . date('Y-m-d', $time) . '/';
            // 文件名
            $filename = $info['name'];
            $rule = array('uniqid', '');
            $func = $rule[0];
            $param = (array)$rule[1];
            foreach ($param as &$value) {
                $value = str_replace('__FILE__', $filename, $value);
            }
            $name = call_user_func_array($func, $param);
            $name = $name . '.' . $info['ext'];

            // 保存目录
            $saveDir = rtrim($rootPath, '/') . $videoPath;
            // 保存文件路径
            $savePath = $saveDir . $name;

            // 遍历临时目录所有文件
            $files = array_diff(scandir($tmpDir), array('.', '..'));
//            //确保目录可写
//            if(!$this->ensure_writable_dir($saveDir)) {
//                $data = array(
//                    'info' => '目录不可写',
//                );
//                return $data;
//            }
            // 创建目录
            if (!is_dir($saveDir)) {
                mkdir($saveDir, 0777, true);
                chmod($saveDir, 0777);
            }

            $image = '';
            // 创建保存文件
            file_put_contents($savePath, $image);
            // 修改app文件权限
            chmod($savePath, 0777);
            // 排序升序
            sort($files);
            foreach ($files as $file) {
                $image = file_get_contents($tmpDir . $file);
                file_put_contents($savePath, $image, FILE_APPEND);
                // 删除文件
                unlink($tmpDir . $file);
            }
            // 删除临时目录
            rmdir($tmpDir);
            // 保存文件
            $data = array(
                'type' => $info['type'],
                'md5' => md5_file($savePath),
                'ext' => $info['ext'],
                'size' => filesize($savePath),
                'name' => $name,
                'path' => $videoPath . $name,
            );
//            $data['id'] = M('file')->add($data);
            return $data;
        }

        return false;
    }

    /**
     * 返回指定的拼接参数
     * @param array $out_param_key  要返回的参数下标
     * @param array $all_param      所有的参数数组
     * @return string
     */
    public function get_out_param($out_param_key = array(), $all_param = array()) {
        if(empty($out_param_key) || empty($all_param)) {
            $this->assign('param', '');
        }
        $param_str = '';
        $param_arr = array();
        foreach($all_param as $key => $val) {
            if(in_array($key, $out_param_key) && !empty($val)) {
                $param_str .= '/'.$key.'/'.$val;
//                $param_arr[$key] = $val;
//                $param_str .= $key.'='.$val.'&';
            }
        }
        $this->assign('param', $param_str);
    }

    /**
     * 确保文件夹存在并可写
     *
     * @param string $dir
     */
    function ensure_writable_dir($dir) {
        if(!file_exists($dir)) {
            mkdir($dir, 0766, true);
            chmod($dir, 0766);
            chmod($dir, 0777);
        }
        else if(!is_writable($dir)) {
            chmod($dir, 0766);
            chmod($dir, 0777);
            if(!is_writable($dir)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 上传图片公共方法
     * @author xy
     * @since 2017/07/06 11:54
     */
    public function ajax_upload_image(){
        $fun = I('fun');
        $config = '';
        switch ($fun){
            case 'upload_icon':
                $config = C('FILE_CONFIG.APPLIB_ICON_CONF');//游戏详情页icon
                break;
            case 'upload_pic':
                $config = C('FILE_CONFIG.APPLIB_PIC_CONF');//游戏详情页上传截图
                break;
            case 'upload_guide':
                $config = C('FILE_CONFIG.APPLIB_GUIDE_CONF'); //游戏详情页上传攻略封面
                break;
            case 'upload_video':
                $config = C('FILE_CONFIG.APPLIB_VIDEO_CONF'); //游戏详情视频弹框
                break;
            case 'upload_cover':
                $config = C('FILE_CONFIG.APPTOPIC_COVER_CONF'); //游戏专题页封面
                break;
            case 'upload_h5':
                $config = C('FILE_CONFIG.APPTOPIC_H5_CONF'); //游戏专题页h5封面
                break;
            case 'upload_bg':
                $config = C('FILE_CONFIG.APPTOPIC_BACKGROUND_CONF'); //游戏专题页抬头图
                break;
            case 'upload_ad_image':
                $config = C('FILE_CONFIG.AD_IMAGE_CONF'); //广告、首页图片配置
                break;
            case 'upload_gift_banner':
                $config = C('FILE_CONFIG.GIFT_DETAIL_BANNER_CONF'); //礼包详情页BANNER图片配置
                break;
            case 'upload_activity_image':
                $config = C('FILE_CONFIG.ACTIVITY_DETAIL_IMAGE_CONF');//活动详情页图片
                break;
            case 'upload_beauty_image':
                $config = C('FILE_CONFIG.APP_BEAUTY_IMAGE_CONF');//详情页精美图片
                break;
            case 'upload_slide_image':
                $config = C('FILE_CONFIG.SLIDE_IMAGE_CONF'); //幻灯片图片
                break;
            default:
                break;
        }
        if(!empty($config)){
            $this->upload_common($config);
        }else{
            echo '未找到方法';
        }
    }

    /**
     * 上传图片公共方法
     * @author xy
     * @since 2017/07/05 18:22
     * @param array $file_conf 文件上传配置
     */
    protected function upload_common(array $file_conf) {
        $upload = new \Think\Upload();
        $upload->maxWidth = $file_conf['max_width']; // 最大宽度
        $upload->maxHeight = $file_conf['max_height']; // 最大高度
        $upload->maxSize = $file_conf['max_size'];    // 设置附件上传大小
        $upload->exts = $file_conf['exts'];    // 设置附件上传类型
        $path = realpath(ROOT_PATH . $file_conf['file_path']) . '/';
        $upload->rootPath = $path;     // 设置附件上传根目录
        $upload->savePath = $file_conf['save_path'];//文件保存的目录
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
     * ajax方式验证跳转链接是否符合要求
     * @author xy
     * @since 2017/07/24 18:16
     */
    public function ajax_check_url(){
        $url = trim(I('url'));
        if(empty($url)){
            $this->outputJSON(true,'100001','跳转链接不能为空');
        }
        if(!checkStringIsUrl($url)){
            $this->outputJSON(true,'100001','跳转链接的格式不正确');
        }
        $this->outputJSON(false,'000000','验证通过');
    }
}
