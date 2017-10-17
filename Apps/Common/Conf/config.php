<?php
return array(
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'pt.dmfive.com', // 服务器地址
//    'DB_NAME'   => 'media', // 数据库名
//    'DB_USER'   => 'root', // 用户名
//    'DB_PWD'    => '', // 密码
    'DB_NAME'   => 'zy_media_test', // 数据库名
    'DB_USER'   => 'admin', // 用户名
    'DB_PWD'    => 'game123456', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PARAMS' =>  array(), // 数据库连接参数
    'DB_PREFIX' => 'zy_media_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_ZHIYU' => array(
        'DB_TYPE'  => 'mysql',
        'DB_USER'  => 'admin',//admin
        'DB_PWD'   => 'game123456',//game123456
        'DB_HOST'  => 'pt.dmfive.com',//pt.dmfive.com
        'DB_PORT'  => '3306',
        'DB_NAME'  => 'zhiyu_test',//wjb_test
        'DB_PREFIX' => 'zy_', // 数据库表前缀
        'DB_CHARSET'=> 'utf8', // 字符集
    ),
    //redis 配置
    'DATA_CACHE_PREFIX' => 'media_', //缓存前缀
    'DATA_CACHE_TYPE'   => 'Redis',//默认动态缓存为Redis
    'REDIS_RW_SEPARATE' => false, //Redis读写分离 true 开启
    'REDIS_HOST'        => 'redis.dmfive.com', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
    'REDIS_PORT'        => '6379',//端口号
    'REDIS_TIMEOUT'     => 756000,//超时时间  一周：3600 * 7 * 30 = 756000
    'REDIS_PERSISTENT'  => false,//是否长连接 false=短连接
    'REDIS_AUTH'        => 'redis123456!@#$%^',//AUTH认证密码redis123456!@#$%^

//    'ERROR_PAGE' => '/error.html',
//    'TMPL_ACTION_ERROR'     =>  APP_PATH.'Public/error.html', // 默认错误跳转对应的模板文件
//    'TMPL_ACTION_SUCCESS' => './Public/Tpl/success.html', // 默认成功跳转对应的模板文件

    //Redis Session配置
    'SESSION_AUTO_START'    =>  false,    // 是否自动开启Session
    'SESSION_TYPE'          =>  'Redis',    //session类型
    'SESSION_PERSISTENT'    =>  1,        //是否长连接(对于php来说0和1都一样)
    'SESSION_CACHE_TIME'    =>  1,        //连接超时时间(秒)
    'SESSION_EXPIRE'        =>  0,        //session有效期(单位:秒) 0表示永久缓存
    'SESSION_PREFIX'        =>  'zy_session_',  //session前缀
    'SESSION_REDIS_HOST'    =>  'redis.dmfive.com', //分布式Redis,默认第一个为主服务器
    'SESSION_REDIS_PORT'    =>  '6379',           //端口,如果相同只填一个,用英文逗号分隔
    'SESSION_REDIS_AUTH'    =>  'redis123456!@#$%^',    //Redis auth认证(密钥中不能有逗号),如果相同只填一个,用英文逗号分隔redis123456!@#$%^
    'SESSION_EXPIRE'        =>  24*3600, //SESSION过期时间一天

    // 下载游戏域名：http://dl.zhiyugame.com
    'SDK_DL_URL' => 'http://dl.zhiyugame.com',
    // 下载app域名  ：http://dlapp.zhiyugame.com
    'APP_DL_URL' => 'http://dlapp.zhiyugame.com',
    // 指娱域名不要加'/'
    'ZHIYU_URL' => 'http://zy.zhiyugame.com',
    // 指娱APPmd5("zhiyuappkey")
    'APP_KEY' => '6e70865e4d831f4503e69f4a5b754fd6',

    // 本站域名
    'BASE_URL' => 'http://' . $_SERVER['HTTP_HOST'],
    // 落地页地址
    'MEDIA_LD_URL' => 'http://' . $_SERVER['HTTP_HOST'] .'/yxk/',

    // 默认游戏
    'DEFAULT_APP' => 1,
    
    //重写,去掉index.php
    'URL_MODEL' => 2,

    // 开启路由
    'URL_ROUTER_ON'   => true,

    'URL_ROUTE_RULES'=>array(
        '/^yxk\/(\d+)$/' => 'Home/floorpage/index?app_id=:1',
        '/^yxk\/(.*[^0-9].*)$/' =>
            function() {
                redirect('http://' . $_SERVER['HTTP_HOST'] . '/yxk/' .C('DEFAULT_APP'));
            },
    ),

    //加载配置文件
    'LOAD_EXT_CONFIG' => 'connect',
    //开启页面跟踪信息展示，正式上线需关闭
    'SHOW_PAGE_TRACE' =>true,
);