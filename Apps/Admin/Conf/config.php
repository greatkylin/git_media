<?php
return array(
	//'配置项'=>'配置值'
    'NOT_AUTH' => array('Index/index', 'Index/welcome'),   //不需要验证权限区分大小写
    'AUTH_CONFIG' => array(
        'AUTH_ON' => true, //是否开启权限
        'AUTH_TYPE' => 1, //
        'AUTH_GROUP' => 'zy_auth_group', //用户组
        'AUTH_GROUP_ACCESS' => 'zy_auth_group_access', //用户组规则
        'AUTH_RULE' => 'zy_auth_rule', //规则中间表
        'AUTH_USER' => 'zy_admin_user'// 管理员表
    ),

    'FILE_CONFIG' => array(
        'APP_COVER_PIC_CONF' => array(
            'file_path'    => 'Uploads',                               // 高清图片地址
            'save_path'    => 'images/app/cover_pic/',
            'thumb_path'   => 'thumb/',
            'thumb_pre'    => 'thumb',
            'thumb_width'  => 540,
            'thumb_height' => 300,
            'max_size'     => 3 * 1024 * 1024,
            'exts'         => array('gif', 'jpg', 'jpeg', 'png'),
        ),
        'APP_FINE_PIC_CONF' => array(
            'file_path'  => 'Uploads',                               // 精美图片地址
            'save_path'  => 'images/app/fine_pic/',
            'thumb_path'   => 'thumb/',
            'thumb_pre'    => 'thumb',
            'thumb_width'  => 540,
            'thumb_height' => 300,
            'max_size'   => 3 * 1024 * 1024,
            'exts'       => array('gif', 'jpg', 'jpeg', 'png'),
        ),
        'COMPANY_ICON_CONF' => array(
            'file_path'  => 'Uploads',                               // 精美图片地址
            'save_path'  => 'images/company/icon/',
            'thumb_path'   => 'thumb/',
            'thumb_pre'    => 'thumb',
            'thumb_width'  => 540,
            'thumb_height' => 300,
            'max_size'   => 3 * 1024 * 1024,
            'exts'       => array('gif', 'jpg', 'jpeg', 'png'),
        ),
        'APPLIB_ICON_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/applib/icon',            // 应用库应用icon图片地址
            'max_width'  => 110,
            'max_height' => 110,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
        'APPLIB_VIDEO_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/applib/video',            // 应用库应用video图片地址
            'max_width'  => 1200,
            'max_height' => 1000,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
        'APPLIB_PIC_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/applib/pic',            // 应用库应用截图图片地址
            'max_width'  => 1200,
            'max_height' => 1000,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
        'APPLIB_GUIDE_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/applib/topic',            // 应用库图文攻略图片地址
            'max_width'  => 1200,
            'max_height' => 1000,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
        'APPTOPIC_COVER_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/apptopic/topic_conver',            // 游戏专题页模板封面图片地址
            'max_width'  => 1200,
            'max_height' => 1000,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
        'APPTOPIC_H5_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/apptopic/topic_h5',            // 游戏专题页H5封面图片地址
            'max_width'  => 1200,
            'max_height' => 1000,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
        'APPTOPIC_BACKGROUND_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/apptopic/topic_bg',            // 游戏专题页抬头图图片地址
            'max_width'  => 1200,
            'max_height' => 1000,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
        'AD_IMAGE_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/ad/',            // 广告图片上传地址配置
            'max_width'  => 1200,
            'max_height' => 1000,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
        'GIFT_DETAIL_BANNER_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/gift/gift_banner',            // 礼包详情页BANNER上传地址配置
            'max_width'  => 1200,
            'max_height' => 1000,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
        'ACTIVITY_DETAIL_IMAGE_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/activity/',            // 活动详情页页图片
            'max_width'  => 1200,
            'max_height' => 1000,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
        'APP_BEAUTY_IMAGE_CONF' => array(
            'file_path'  => 'Uploads',
            'save_path'  => 'Images/applib/beauty/',            // 游戏详情页精美图片
            'max_width'  => 1200,
            'max_height' => 1000,
            'max_size'   => 2 * 1024 * 1024,
            'exts'       => array('jpg', 'png', 'jpeg'),
        ),
    ),

    'SUPER_ADMIN' => array(1, 14, 25, 42, 45),  // 超级管理员权限（跳过权限验证的管理员ID）
	/*
     * 引入后台菜单配置
     */
    'LOAD_EXT_CONFIG' => array('ADMIN_LEFT_MENU'=>'left_menu',),
    /**
     * 视频文件格式
     */
    'VIDEO_TYPE'=>array('mpg','mpeg','mpe','mp4','rmvb','rm','avi','dat','mkv','flv','vob','3gp','wmv','asf','asx'),
);