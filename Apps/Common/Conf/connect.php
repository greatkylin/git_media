<?php
/**
 * 授权信息配置
 * User: xy
 * Date: 2017/8/18
 * Time: 15:08
 */

// 爱奇艺视频托管 授权信息
$connect['iqiyi']['appKey'] = 'ddc5266229b54bd5a81c4eb8995ae774';
$connect['iqiyi']['appSecret'] = 'bfbed058a29670d1d6c053113f295cee';
$connect['iqiyi']['managerUrl'] = 'http://openapi.iqiyi.com/';
$connect['iqiyi']['uploadUrl'] = 'http://upload.iqiyi.com/';

//指娱正式服域名 http://app.zhiyugame.com ，上线时需要修改此项，
$connect['URL']['ZHIYU_ADMIN_URL'] = 'http://test.app.zhiyugame.com';
return $connect;