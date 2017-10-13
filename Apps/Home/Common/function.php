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