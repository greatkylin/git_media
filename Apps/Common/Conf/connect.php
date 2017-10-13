<?php
/**
 * 授权信息配置
 * User: xy
 * Date: 2017/8/18
 * Time: 15:08
 */
// 指娱APP
$connect['APP']['KEY'] = '6e70865e4d831f4503e69f4a5b754fd6';	//md5("zhiyuappkey")
// 云信留客（发送短信）
$connect['sms']['userCode'] = 'xmrk';
$connect['sms']['userPass'] = 'xmrk741';
$connect['sms']['smsUrl'] = 'http://h.1069106.com:1210/Services/MsgSend.asmx/SendMsg';
// 爱奇艺视频托管 授权信息
$connect['iqiyi']['appKey'] = ['ddc5266229b54bd5a81c4eb8995ae774','f56dd22a3bae417e9cb62d0da404c02a'];
$connect['iqiyi']['appSecret'] = ['bfbed058a29670d1d6c053113f295cee','3af0f6b6948c4ea1bffe145d958a089c'];
$connect['iqiyi']['managerUrl'] = 'http://openapi.iqiyi.com/';
$connect['iqiyi']['uploadUrl'] = 'http://upload.iqiyi.com/';

//指娱正式服域名 http://app.zhiyugame.com/ ，上线时需要修改此项，
$connect['URL']['ZHIYU_URL'] = 'http://app.zhiyu.local/';
return $connect;