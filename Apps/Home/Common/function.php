<?php
/**
 * 前台的公共方法
 * User: xy
 * Date: 2017/10/10
 * Time: 10:22
 */

/**
 * 从session中获取用户信息
 * @author xy
 * @since 2017/10/11 11:22
 */
function get_user_info(){
    if(session('media_web_user')){
        return session('media_web_user');
    }
    return false;
}

/**
 * 注销当前登录用户的信息
 * @author xy
 * @since 2017/10/10 1：33
 */
function unset_user_login_info(){
    if(session('login_name')){
        session('login_name', null);
    }
    if(session('media_web_user')){
        session('media_web_user', null);
    }
}

/**
 * 用户上传头像临时保存，同时返回头像图片完整路径信息
 * @author xy
 * @since 2017/10/10 16:47
 * @return bool|string
 */
function upload_user_avatar_temp(){
    $userInfo = session('media_web_user');
    if(!$userInfo){
        return false;
    }
    $file_conf = C('FILE_CONFIG.USER_CONF');
    $upload = new \Think\Upload();
    $upload->maxSize = $file_conf['max_size'];    // 设置附件上传大小
    $upload->exts = $file_conf['exts'];    // 设置附件上传类型
    $path = realpath(ROOT_PATH . $file_conf['file_path']) . '/';
    $upload->rootPath = $path;     // 设置附件上传根目录
    $upload->savePath = $file_conf['save_path'];     // 保存路径目录
    $upload->subName = $userInfo['uid'];
    $info = $upload->upload();
    if($info){
        $filePath = dirname(__FILE__).'/../../../Uploads/'.$info['test_file']['savepath'].$info['test_file']['savename'];
        return realpath($filePath);
    }
    return false;
}

/**
 * 处理图片为圆形
 * @desc 用以下公式计算
 * (x-a)*(x-a)+(y-b)*(y-b)<r*r
 * 公式成立说明当前x,y点在圆内
 * x,y为当前的坐标
 * a,b为圆的圆心位置
 * r为半径
 * 先创建一张透明的图片，
 * 然后一行一行的扫描原图如图像素点在圆内就画出这个像素不在的就保持透明色
 * @param string $imgpath 图片路径
 * @param $r 圆的半径
 * @param $dotX 圆心坐标
 * @param $dotY 圆心坐标
 * @return resource
 */
function circle_img($imgpath = './tx.jpg', $r, $dotX, $dotY) {
    $ext = pathinfo($imgpath);
    $src_img = null;
    switch ($ext['extension']) {
        case 'jpg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'png':
            $src_img = imagecreatefrompng($imgpath);
            break;
        case 'gif':
            $src_img = imagecreatefromgif($imgpath);
            break;
    }
    $wh = getimagesize($imgpath);
    $w = $wh[0];
    $h = $wh[1];
//    $w = min($w, $h);
//    $h = $w;
    $img = imagecreatetruecolor($w, $h);
    //这一句一定要有
    imagesavealpha($img, true);
    //拾取一个完全透明的颜色,最后一个参数127为全透明
    $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
    imagefill($img, 0, 0, $bg);
//    $r = $w / 2; //圆半径
    $y_x = $r; //圆心X坐标
    $y_y = $r; //圆心Y坐标
    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $rgbColor = imagecolorat($src_img, $x, $y);
            if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) < ($r * $r))) {
                imagesetpixel($img, $x, $y, $rgbColor);
            }
        }
    }
    return $img;
}