<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_ACTION_ERROR'     =>  APP_PATH.'Home/jump.tpl', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  APP_PATH.'Home/jump.tpl', // 默认成功跳转对应的模板文件
    //游戏下载的二维码与logo保存路径
    'QRCODE_SAVE_PATH' => 'Uploads/QrCode',
    'QRCODE_LOGO_PATH' => 'Uploads/QrLogo',

    'FILE_CONFIG' => array(
        'USER_CONF' => array(
            'file_path'  => 'Uploads',                               // 用户头像图片缓存临时地址
            'save_path'  => 'Images/user/avatar',
            'max_size'   => 0.5 * 1024 * 1024,
            'exts'       => array('jpg', 'jpeg', 'png'),
        ),
    ),
);