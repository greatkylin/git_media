<?php
/************************************公共函数*******************************************/

/**
 * API数据出口函数
 * @param $flag             标示
 * @param array $data      数据
 * @param string $info       相关提示
 * @param int $echo_type    类型（0表示非列表将data赋值给obj，1表示列表将data赋值给list）
 * @param int $count
 */
function json_echo($flag, $data = array(), $info = '', $echo_type = 0, $count = 0, $error_code = 0)
{
    $data = _unsetNull($data);//把 null转换为空'' 递归方式
    $result = status_header($flag, $data, $info, $echo_type, $count);
    ajax_return($result);
}

/**
 * Ajax方式返回数据到客户端
 * @access protected
 * @param mixed $data 要返回的数据
 * @param String $type AJAX返回数据格式
 * @param int $json_option 传递给json_encode的option参数
 * @return void
 */
function ajax_return($data,$type='', $json_option=0) {
    if(empty($type)) $type  =   C('DEFAULT_AJAX_RETURN');
    switch (strtoupper($type)){
        case 'JSON' :
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode($data,$json_option));
        case 'XML'  :
            // 返回xml格式数据
            header('Content-Type:text/xml; charset=utf-8');
            exit(xml_encode($data));
        case 'JSONP':
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
            exit($handler.'('.json_encode($data,$json_option).');');
        case 'EVAL' :
            // 返回可执行的js脚本
            header('Content-Type:text/html; charset=utf-8');
            exit($data);
        default     :
            // 用于扩展其他返回格式数据
            Hook::listen('ajax_return',$data);
    }
}

/**
 * API返回状态判断函数
 * @param $flag          标识
 * @param array $data    数据
 * @param string $info   提示
 * @param int $echo_type 返回类型（0表示非列表将data赋值给obj，1表示列表将data赋值给list）
 * @param int $count     总数(非当页条数，有翻页时)
 * @return mixed
 */
function status_header($flag, $data = array(), $info = "", $echo_type = 0, $count = 0) {

    switch ($flag) {
        case '#success':
            $status['flag'] = "success";
            $status['info'] = $info;
            break;
        case '#false':
            $status['flag'] = "false";
            $status['info'] = $info;
            break;
//        case '#upload'://上传状态
//            $status['flag'] = "success";
//            $status['info'] = $info;
//            break;
//        case '#address':
//            $status['flag'] = "address";
//            $status['info'] = $info;
//            break;
        case '#login':
            $status['flag'] = "login";
            $status['info'] = $info;
            break;
        case '#sys':
            $status['flag'] = "system";
            $status['info'] = "系统错误";
            break;
        case '#version':
            $status['flag'] = "version";
            $status['info'] = "您的版本过低，请升级"; //"您的版本过低，请升级";
            // 将下载相关信息写到data里面输出
            break;
        case '#newversion':
            $status['flag'] = "newversion";
            $status['info'] = "有新版本，请升级"; //"有新版本，请升级";
            // 将下载相关信息写到data里面输出
            break;
        default:
            $status['flag'] = $flag;
            $status['info'] = $info;
            break;
    }


    if (!$status['info'] && $flag == '#success') {
        $status['info'] = "操作成功";
    }

    $status['data']['obj'] = (object)array();
    $status['data']['list'] = array();
    $status['data']['count'] = 0;

    if (!empty($data)) {

        if ($echo_type == 0) {
            $status['data']['obj'] = $data;
            $status['data']['list'] = array();
            $status['data']['count'] = 0;
        }
        else {
            $count = ($count == 0) ? count($data) : $count;
            $status['data']['obj'] = (object)array();
            $status['data']['list'] = $data;
            $status['data']['count'] = $count;
        }
    }

    $status['time'] = time();
    return $status;
}

/**
 * 实例化Service中的类
 * @param string $name Service中的类名
 * @param array $params 类的参数
 * @return class 返回实例化的类
 */
function service($name, $params = array()) {
    $name .= "Service";
    $className = "Myclass.Service.".$name;
    //创建一个静态变量，用于缓存实例化的对象
    static $_service = array();
    //如果已经实例化过，则返回缓存实例化对象
    if (isset($_service[$className])){
        return $_service[$className];
    }
    //载入文件
    import($className);
    $name = "\\".$name;
    if (class_exists($name)) {
        $obj = new $name($params);
        $_service[$className] = $obj;
        return $obj;
    } else {
        return false;
    }
}

/**
 * 实例化Myclass类库 我创建的类放到Myclass目录下面
 * @param string $name  类库名（.class.php之前的名称）
 * @param string $file_name   目录
 * @param string $ext 类库后缀
 * @return boolean
 */
function myclass($name, $file_name = '', $ext='.class.php') {
    if(!empty($file_name)){
        $file_name = $file_name.".";
    }
    $className = "Myclass.".$file_name.$name;

    $baseUrl = LIB_PATH;

    //创建一个静态变量，用于缓存实例化的对象
    static $_myclass = array();
    //如果已经实例化过，则返回缓存实例化对象
    if (isset($_myclass[$className])){
        return $_myclass[$className];
    }

    //载入文件
    import($className, $baseUrl, $ext);
    $name = "\\".$name;
    if (class_exists($name)) {
        $obj = new $name();
        $_myclass[$className] = $obj;
        return $obj;
    } else {
        return false;
    }
}


/**
 * 写入日志
 * write_log('测试日志信息，这是警告级别', 'WARN', 'api_index1.log');
 * @param string $msg 日志信息
 * @param string $level 日志级别(
 *                              EMERG 严重错误，导致系统崩溃无法使用
 *                              ALERT 警戒性错误， 必须被立即修改的错误
 *                              CRIT 临界值错误， 超过临界值的错误
 *                              ERR 一般性错误
 *                              WARN 警告性错误， 需要发出警告的错误
 *                              NOTICE 通知，程序可以运行但是还不够完美的错误
 *                              INFO 信息，程序输出信息
 *                              DEBUG 调试，用于调试信息
 *                              SQL SQL语句，该级别只在调试模式开启时有效)
 * @param string $filename 写入文件名称，默认当天日期
 * @param string $file_path 写入路径（默认Public/Logs/）
 */
function write_log($msg = '', $level = 'DEBUG', $filename = '', $file_path = '')
{
    if (empty($file_path)) {
        mk_dir_ext(ROOT_PATH . 'Public/Logs');
        $file_path = realpath(ROOT_PATH . 'Public/Logs') . '/';
    }
    if (substr($file_path, -1) != '/') {
        $file_path .= '/';
    }
    if (empty($filename)) {
        $filename = date('y_m_d') . '.log';
    }
    else {
        $filename = date('y_m_d') . '_' . $filename;
    }
    // 具体路径文件名
    $destination = $file_path . $filename;
    \Think\Log::write($msg, $level, 'File', $destination);
}

/**
 * 判断文件是否存在不存在则创建
 * @param $file_path
 */
function mk_dir_ext($file_path) {
    if(!is_dir($file_path)) {
        mkdir($file_path);
        chmod($file_path, 0770);
    }
}

/**
 * 根据id获取省份市区信息
 * @param int $get_client_ip
 * @return array|bool
 */
function get_ip_data($get_client_ip = 0) {

    if (!$get_client_ip) {
        $get_client_ip = get_client_ip();
    }
    $ip = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . $get_client_ip);
    $ip = json_decode($ip);
    if ($ip->code) {
        return false;
    }
    $data = (array) $ip->data;
    return $data;
}

/**
 * 取客户端 ip
 * @return string
 */
function get_ip() {
    if (isset($_SERVER['HTTP_CLIENT_IP']) and !empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) and !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ',');
    }
    if (isset($_SERVER['HTTP_PROXY_USER']) and !empty($_SERVER['HTTP_PROXY_USER'])) {
        return $_SERVER['HTTP_PROXY_USER'];
    }
    if (isset($_SERVER['REMOTE_ADDR']) and !empty($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    } else {
        return "0.0.0.0";
    }
}

/**
 * 格式化整整数 用于判断是否正确
 * @param string $value
 * @return int
 */
function format_int($value) {
    if (preg_match('/^\d+$/', $value, $arr)) {
        return $arr[0];
    } else {
        return 0;
    }
}

/**
 *
 * @param  string $url    请求URL
 * @param  array  $params 请求参数
 * @param  string $method 请求方法GET/POST
 * @return array  $data   响应数据
 */
/**
 * 发送HTTP请求方法
 * @param $url              请求URL
 * @param $params           请求参数
 * @param string $method    请求方法GET/POST
 * @param array $header     header中传输内容的数组
 * @param bool $multi       是否传输文件
 * @return mixed            响应数据
 * @throws Exception
 */
function http($url, $params, $method = 'GET', $header = array(), $multi = false) {
    $opts = array(
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => $header
    );
    /* 根据请求类型设置特定参数 */
    switch (strtoupper($method)) {
        case 'GET':
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            break;
        case 'POST':
            //判断是否传输文件
            $params = $multi ? $params : http_build_query($params);
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        default:
            throw new Exception('不支持的请求方式！');
    }
    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if ($error)
        throw new Exception('请求发生错误：' . $error);
    return $data;
}

/**
 * curl请求
 * @param $url      请求的URL地址
 * @param $param    JSON类型字符串
 * @return mixed
 */
function http_json($url, $param) {
    $param=json_encode($param);
    #dump($param);exit;
    $ch = curl_init($url); //请求的URL地址
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);//$data JSON类型字符串
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($param)));
    $data = curl_exec($ch);
    return $data;
}

/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
    $string = str_replace('%20', '', $string);
    $string = str_replace('%27', '', $string);
    $string = str_replace('%2527', '', $string);
    $string = str_replace('*', '', $string);
    $string = str_replace('"', '&quot;', $string);
    $string = str_replace("'", '', $string);
    $string = str_replace('"', '', $string);
    $string = str_replace(';', '', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    $string = str_replace("{", '', $string);
    $string = str_replace('}', '', $string);
    return $string;
}

/**
 * 密码加密
 * @param $str
 * @param int $offSet
 * @return string
 */

function multiMD5($str, $offSet = 20) {
    $md5_str = md5($str);
    $length = $offSet > 30 ? 30 : $offSet;
    $md5_str = strtoupper($md5_str);
    $sub_str = substr($md5_str, $length);
    return strtoupper(md5($md5_str . $sub_str));
}

//获取今日开始的时间
function getToday() {
    return mktime(0, 0, 0, date("m"), date("d"), date("Y")); //2016/1/25 0:0:0
}

//获取明天开始的时间
function getNextday() {
    return mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")); //2016/1/26 0:0:0
}

// 格式化图片 拼完整路径 加速地址
function format_img ($url) {
    $config = F("Config");
    if ($url) {
        //echo 'rc:'.$config['rc_siteurl'];
        $sub = substr($url, 0, 7);
        if($sub == 'Upload/') {
            $url = strstr($url, "http:") ? $url : $config['rc_siteurl'] . $url;
            return $url;
        }
        else if($sub == 'package') {
            $url = strstr($url, "http:") ? $url : C('SDK.PACKAGE_URL') . $url;
            return $url;
        }
        else {
            $url = ltrim($url, '/');
            $url = strstr($url, "http:") ? $url : $config['old_siteurl'] . '/' . $url;
            return $url;
        }
    } else {
        $url = '';
    }
    return $url;
}

// 格式化图片 拼完整路径 加速地址
function format_url ($url) {
    if ($url) {
        if(strstr($url, "http")) {
            if(strstr($url, 'http://www.zhiyugame.com')) {
                $url = preg_replace('/^http:\/\/www.zhiyugame.com(.*)$/', 'http://app.zhiyugame.com$1', $url);
            }
            return $url;
        }
        $sub = substr($url, 0, 7);
        if($sub == 'Upload/') {
            $sub2 = substr($url, -4);
            if(strtolower($sub2) == '.apk') {
                $url = C('APP_DL_URL') . '/' . $url;
            }
            else {
                $url = C('ZHIYU_URL') . '/' . $url;
            }
            return $url;
        }
        else if($sub == 'package') {
            $url = C('SDK_DL_URL') . '/' . $url;
            return $url;
        }
        else if($sub == 'Uploads'){
            $url = C('BASE_URL') . '/' . $url;
            return $url;
        }
        else {
            $url = ltrim($url, '/');
            $sub3 = substr($url, -4);
            if(strtolower($sub3) == '.apk') {
                $url = C('APP_DL_URL') . '/' . $url;
            }
            else {
                $url = C('ZHIYU_URL') . '/' . $url;
            }
            return $url;
        }
    } else {
        $url = '';
    }
    return $url;
}

if ( ! function_exists('isPhone'))
{
    /**
     * 验证是否是手机格式
     *
     * @param 	string $str
     *
     * @return	bool
     */
    function isPhone($str)
    {
        if(!is_string($str))
        {
            return FALSE;
        }
        return preg_match('/^1[34578]\d{9}$/', $str)? TRUE : FALSE;
    }
}

if ( ! function_exists('isEmail'))
{
    /**
     * 验证是否是邮箱格式
     *
     * @param 	string $str
     *
     * @return	bool	合法返回 true，不合法返回 false
     */
    function isEmail($str)
    {
        if(!is_string($str))
        {
            return FALSE;
        }
        return preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)? TRUE : FALSE;
    }
}

if ( ! function_exists('array_unique_fb')) {

    /** 二维数组去掉重复值
     * @param $array2D
     * @return array
     */
    function array_unique_fb($array2D){
        foreach ($array2D as $v){
            $v = join(',',$v);//降维,也可以用implode,将一维数组转换为用逗号连接的字符串
            $temp[]=$v;
        }
        $temp=array_unique($temp);//去掉重复的字符串,也就是重复的一维数组
        foreach ($temp as $k => $v){
            $temp[$k]=explode(',',$v);//再将拆开的数组重新组装
        }
        return $temp;
    }
}

if ( ! function_exists('_unsetNull')) {
    //把 null转换为空'' 递归方式
    function _unsetNull($arr) {
        # 要过滤的参数
        $filter_param = array('relation_param');
        if ($arr !== null){
            if(is_array($arr)){
                if(!empty($arr)){
                    foreach($arr as $key => $value) {

                        if($value === null) {
                            if (in_array($key, $filter_param)) {
                                continue;
                            }
                            $arr[$key] = '';
                        } else {
                            $arr[$key] = _unsetNull($value);      //递归再去执行
                        }
                    }
                } else { // 数组不转为空
                    //$arr = '';
                }
            } else {
                //注意三个等号
                if($arr === null){
                    $arr = '';
                }
            }
        } else {
            $arr = '';
        }
        return $arr;
    }
}

if ( ! function_exists('get_subStr'))
{
    if(!defined("SITE_SUB_CHAR")){define("SITE_SUB_CHAR", 		1);}
    if(!defined("SITE_SUB_BYTE")){define("SITE_SUB_BYTE", 		2);}
    if(!defined("SITE_SUB_WEIGHT")){define("SITE_SUB_WEIGHT", 	3);}
    /**
     * 自定义字符串截取函数(拓展函数)
     *
     * @param 	string/int		$str_value		待截取的源字符串
     * @param	int				$start			要截取的起始 - 字符位置(下标，从0开始算)。<br/>
     *  											$start > 0：从源字符串左边开始计算位置；<br/>
     *  											$start = 0：以源字符串左边第一个字符为起始位置 - (默认)；<br/>
     *  											$start < 0：从源字符串右边(末尾)开始计算位置。
     * @param	int				$sub_len		要截取的"字符数/字节数/字符串占用宽度"。
     *  											$sub_len > 0：从start位置开始向右截取指定"字符数/字节数"；<br/>
     *  											$sub_len = 0：从start位置开始向右截取至源字符串末尾 - (默认)；<br/>
     *  											$sub_len < 0：从start位置开始向左截取指定"字符数/字节数"。
     * @param	boolen			$len_type		标识是进行 "字符数截取" 还是 "字节数截取"。<br/>
     *  											$len_type = 1：表示进行 	- 字符数截取(默认)。	sub_len变量所代表的是"字符数"；<br/>
     *  											$len_type = 2：表示进行	- 字节数截取。		sub_len变量所代表的是"字节数"。<br/>
     *  											$len_type = 3：表示进行	- 宽度截取。			sub_len变量所代表的是"宽度"。
     * @param 	string			$end_str		截取之后要加在末尾的字符串。默认(...)
     * @param 	boolen			$if_escape		是否先进行htmlspecialchars解码后再截取。默认(TRUE)
     * @param 	boolen			$if_strip		是否先剥去所有html、php、xml标签。默认(FALSE)
     * @param 	string			$char_site		字符串编码。默认(UTF-8)
     *
     * @return string
     */
    function get_subStr($str_value = '', $start = 0, $sub_len = 0, $len_type = SITE_SUB_CHAR, $end_str = '...', $if_escape = TRUE, $if_strip = FALSE, $char_site = 'UTF-8')
    {
        // 如果源字符串不是string，且不是数字类型，则原样返回
        if(!is_string($str_value) && !is_numeric($str_value))
        {
            return $str_value;
        }
        $str_value = strval($str_value);
        $if_escape = (bool)$if_escape;
        $if_strip  = (bool)$if_strip;
        // 是否先进行htmlspecialchars_decode解码
        $str_value = $if_escape? htmlspecialchars_decode($str_value, ENT_QUOTES) : $str_value;
        // 是否剥去所有html、php、xml标签
        $str_value = $if_strip? strip_tags($str_value) : $str_value;
        // 截取
        $str_value = get_subStr_Base($str_value, $start, $sub_len, $len_type, $end_str, $char_site);
        // 是否进行htmlspecialchars编码
        $str_value = $if_escape? htmlspecialchars($str_value, ENT_QUOTES, $char_site) : $str_value;
        return $str_value;
    }
}

if ( ! function_exists('subStr_SR'))
{
    if(!defined("SITE_SUB_CHAR")){define("SITE_SUB_CHAR", 		1);}
    if(!defined("SITE_SUB_BYTE")){define("SITE_SUB_BYTE", 		2);}
    if(!defined("SITE_SUB_WEIGHT")){define("SITE_SUB_WEIGHT", 	3);}
    /**
     * 自左边起点向右截取字符串
     *
     * @param 	string/int		$str_value		源字符串
     * @param 	int				$sub_len		截取长度(自动取整和绝对值 - 仅向右截取，默认 0 = 向右截取至源字符串末尾)
     * @param	boolen			$len_type		标识是进行 "字符数截取" 还是 "字节数截取"。<br/>
     *  											$len_type = 1：表示进行 	- 字符数截取。		sub_len变量所代表的是"字符数"；<br/>
     *  											$len_type = 2：表示进行	- 字节数截取。		sub_len变量所代表的是"字节数"。<br/>
     *  											$len_type = 3：表示进行	- 宽度截取(默认)。	sub_len变量所代表的是"宽度"。
     * @param 	string			$end_str		截取之后要加在末尾的字符串。默认(...)
     * @param 	boolen			$if_escape		是否先进行htmlspecialchars解码后再截取。默认(TRUE)
     * @param 	boolen			$if_strip		是否先剥去所有html、php、xml标签。默认(FALSE)
     * @param 	string			$char_site		字符串编码。默认(UTF-8)
     *
     * @return string
     */
    function subStr_SR($str_value = '', $sub_len = 0, $len_type = SITE_SUB_WEIGHT, $end_str = '...', $if_escape = false, $if_strip = FALSE, $char_site = 'UTF-8')
    {
        $sub_len   = intval($sub_len);
        $sub_len   = abs($sub_len);
        // 截取
        return get_subStr($str_value, 0, $sub_len, $len_type, $end_str, $if_escape, $if_strip, $char_site);
    }
}

if ( ! function_exists('get_subStr_Base'))
{
    if(!defined("SITE_SUB_CHAR")){define("SITE_SUB_CHAR", 		1);}
    if(!defined("SITE_SUB_BYTE")){define("SITE_SUB_BYTE", 		2);}
    if(!defined("SITE_SUB_WEIGHT")){define("SITE_SUB_WEIGHT", 	3);}
    /**
     * 自定义字符串截取函数(基础函数)
     *
     * @param	 	string		$str_value		待截取的源字符串
     *
     * @param	 	int			$start			要截取的起始 - 字符位置(下标，从0开始算)。<br/>
     *  											$start > 0：从源字符串左边开始计算位置；<br/>
     *  											$start = 0：以源字符串左边第一个字符为起始位置(默认)；<br/>
     *  											$start < 0：从源字符串右边(末尾)开始计算位置。
     * @param	 	int			$sub_len		要截取的"字符数/字节数"。<br/>
     *  											$sub_len > 0：从start位置开始向右截取指定"字符数/字节数"；<br/>
     *  											$sub_len = 0：从start位置开始向右截取至源字符串末尾(默认)；<br/>
     *  											$sub_len < 0：从start位置开始向左截取指定"字符数/字节数"。
     * @param		boolen		$len_type		标识是进行 "字符数截取" 还是 "字节数截取"。<br/>
     *  											$len_type = 1：表示进行 	- 字符数截取(默认)。	sub_len变量所代表的是"字符数"；<br/>
     *  											$len_type = 2：表示进行	- 字节数截取。		sub_len变量所代表的是"字节数"；<br/>
     *  											$len_type = 3：表示进行	- 宽度截取。	 		sub_len变量所代表的是"宽度"。
     * @param	 	string		$end_str		截取之后要加在末尾的字符串。(默认"...")
     * @param		string		$char_site		源字符串编码。(默认"UTF-8")
     *
     * @return		string						返回截取完之后的新字符串。(源字符串为空或起始字符位置大于源字符串长度，返回""；源字符串不为字符串类型或数字，则照不处理原样返回。
     */
    function get_subStr_Base($str_value = '', $start = 0, $sub_len = 0, $len_type = SITE_SUB_CHAR, $end_str = '...', $char_site = 'UTF-8')
    {
        // 如果源字符串不是string，且不是数字类型，则原样返回
        if(!is_string($str_value) && !is_numeric($str_value))
        {
            return $str_value;
        }
        $str_value 	= strval($str_value);
        $start		= intval($start);
        $end		= 0;
        $sub_len	= intval($sub_len);
        $len_type	= in_array(strval($len_type), array(SITE_SUB_CHAR, SITE_SUB_BYTE, SITE_SUB_WEIGHT))? $len_type : SITE_SUB_CHAR;
        $end_str	= strval($end_str);
        // 获取源字符串的总 - 字符数
        $str_char_count = mb_strlen($str_value, $char_site);
        // 如果源字符串为空，则返回空
        if($str_char_count <= 0)
        {
            return "";
        }
        // 验证并重置合法的起始字符位置
        if(($start > 0 && $start >= $str_char_count) || ($start < 0 && ($str_char_count + $start) < 0))
        {
            return "";      // 起始字符位置错误，则直接返回空
        }
        if($start < 0)
        {
            $start = $str_char_count + $start;
        }
        // 拆分字符串中的字符
        $str_value_array     	= array();      // 字符信息数组
        $str_value_byte_count	= 0;            // 字符串总字节长度
        $start_byte         	= 0;            // 起始字符所在字节位置
        $str_value_weight_count	= 0;			// 字符串总宽度
        $start_weight         	= 0;            // 起始字符所在宽度位置
        for($i = 0; $i < $str_char_count; $i++)
        {
            $str_value_array[$i]['val']   		= mb_substr($str_value, $i, 1, $char_site);	// 提取单个字符
            $str_value_array[$i]['byte']  		= strlen($str_value_array[$i]['val']);		// 提取该字符的字节长度
            $str_value_array[$i]['weight']		= preg_match('/^[\x00-\xFF]$/', $str_value_array[$i]['val'])? 1 : 2;	// 提取该字符的宽度

            $str_value_byte_count        		+= $str_value_array[$i]['byte'];
            $str_value_weight_count				+= $str_value_array[$i]['weight'];
            if($start > $i)
            {
                $start_byte              		+= $str_value_array[$i]['byte'];
                $start_weight					+= $str_value_array[$i]['weight'];
            }
        }
        $str_value_length = count($str_value_array);
        /**  如果为截取指定数量的字符  **/
        if($len_type == SITE_SUB_CHAR)
        {
            /*=== 转化为左起并向右截取 ===*/
            if($sub_len != 0)
            {
                $end = $start + $sub_len;      		// 获取结束字符位置
                // 如果起始字符位置大于结束字符位置，则交换起始和结束的位置
                if($start > $end)
                {
                    $temp_var 	= $start;
                    $start   	= $end;
                    $end 		= $temp_var;
                }
                $start = $start < 0 ? 0 : $start;  	// 如果起始字符位置小于0，则设置起始字符位置为0
                if($start == 0 && $end == 0)
                {
                    return "";                  	// 如果起点为0且向左截取，则直接返回空值
                }
                $sub_len = $end - $start;
            }
        }
        /**  如果为截取指定数量的字节  **/
        elseif($len_type == SITE_SUB_BYTE)
        {
            /*=== 转化为左起并向右截取 ===*/
            if($sub_len != 0)
            {
                $end_byte = $start_byte + $sub_len;            		// 获取结束字节位置
                // 如果起始字节位置大于结束字节位置，则交换起始和结束的位置
                if($start_byte > $end_byte)
                {
                    $temp_var 	= $start_byte;
                    $start_byte = $end_byte;
                    $end_byte   = $temp_var;
                }
                $start_byte = $start_byte < 0 ? 0 : $start_byte;   	// 如果起始字节位置小于0，则设置起始字节位置为0
                if($start_byte == 0 && $end_byte == 0)
                {
                    return "";                                  	// 如果起点为0且向左截取，则直接返回空值
                }
                // 将起始字节位置转化为起始字符位置
                $temp_index_byte = 0;
                $start_find      = FALSE;
                $end_find        = FALSE;
                for($n = 0; $n < $str_value_length; $n++)
                {
                    // 如果找到了起始字节所在的字符，则重置起始字符位置
                    if($start_byte >= $temp_index_byte && $start_byte < $temp_index_byte + $str_value_array[$n]['byte'])
                    {
                        $start 		= $n;
                        $start_find = TRUE;
                    }
                    // 如果找到了结束字节所在的字符，则根据字符类型重置结束字节位置
                    if($end_byte >= $temp_index_byte && $end_byte < $temp_index_byte + $str_value_array[$n]['byte'])
                    {
                        // 如果字节刚好卡在该字符的中间，则结束字符位置往前加1
                        $end = $end_byte == $temp_index_byte ? $n : $n + 1;
                        $end_find = TRUE;
                    }
                    if($start_find && $end_find)
                    {
                        break;
                    }
                    $temp_index_byte += $str_value_array[$n]['byte'];
                }
                if(!$start_find || (!$start_find && !$end_find))
                {
                    return "";						// 如果起点未找到，或者起点终点都没找到，则直接返回空值
                }
                else if($start_find && !$end_find)
                {
                    $sub_len = 0;					// 如果起点找到，终点没找到，则直接从起点截取到末尾
                    $end 	 = 0;
                }
                else
                {
                    $sub_len = $end - $start;		// 如果起点和终点都找到，则将截取字节数转化为截取字符数
                }
            }
        }
        /**  如果为截取指定宽度的字符串  **/
        elseif($len_type == SITE_SUB_WEIGHT)
        {
            /*=== 转化为左起并向右截取 ===*/
            if($sub_len != 0)
            {
                $end_weight = $start_weight + $sub_len;            			// 获取结束位置
                // 如果起始位置大于结束位置，则交换起始和结束的位置
                if($start_weight > $end_weight)
                {
                    $temp_var 		= $start_weight;
                    $start_weight 	= $end_weight;
                    $end_weight   	= $temp_var;
                }
                $start_weight = $start_weight < 0 ? 0 : $start_weight;   	// 如果起始字节位置小于0，则设置起始字节位置为0
                if($start_weight == 0 && $end_weight == 0)
                {
                    return "";                                  			// 如果起点为0且向左截取，则直接返回空值
                }
                // 将起始字节位置转化为起始字符位置
                $temp_index_byte = 0;
                $start_find      = FALSE;
                $end_find        = FALSE;
                for($n = 0; $n < $str_value_length; $n++)
                {
                    // 如果找到了起始位置所在的字符，则重置起始字符位置
                    if($start_weight >= $temp_index_byte && $start_weight < $temp_index_byte + $str_value_array[$n]['weight'])
                    {
                        $start 		= $n;
                        $start_find = TRUE;
                    }
                    // 如果找到了结束字节所在的字符，则根据字符类型重置结束字节位置
                    if($end_weight >= $temp_index_byte && $end_weight < $temp_index_byte + $str_value_array[$n]['weight'])
                    {
                        // 如果字节刚好卡在该字符的中间，则结束字符位置往前加1
                        $end = $end_weight == $temp_index_byte ? $n : $n + 1;
                        $end_find = TRUE;
                    }
                    if($start_find && $end_find)
                    {
                        break;
                    }
                    $temp_index_byte += $str_value_array[$n]['weight'];
                }
                if(!$start_find || (!$start_find && !$end_find))
                {
                    return "";						// 如果起点未找到，或者起点终点都没找到，则直接返回空值
                }
                else if($start_find && !$end_find)
                {
                    $sub_len = 0;					// 如果起点找到，终点没找到，则直接从起点截取到末尾
                    $end 	 = 0;
                }
                else
                {
                    $sub_len = $end - $start;		// 如果起点和终点都找到，则将截取字节数转化为截取字符数
                }
            }
        }
        else
        {
            return $str_value;
        }
        // 定义截取后的新字符串变量
        $str_cut = "";
        for ($i = $start; $i < $str_value_length; $i++)
        {
            // 如果截取字符数设置为0，则截取到尾部；如果还没达到截取字符数，则继续截取
            if($sub_len == 0 || $i < $end)
            {
                $str_cut .= $str_value_array[$i]['val'];
            }
            else
            {
                $str_cut .= ($end < $str_value_length) ? $end_str : $str_cut;
                break;
            }
        }
        // 截取完成，返回截取后的新字符串
        return $str_cut;
    }
}

/**
 * 去除html标签
 */
if( ! function_exists('strip_tags_ext')) {
    function strip_tags_ext ($data, $field = array()) {
        if(is_array($data)) {
            // 一维数组
            if (count($data) == count($data, 1)) {
                foreach ($data as $key => $value) {
                    if(in_array($key, $field)) {
                        $data[$key] = strip_tags(htmlspecialchars_decode($value));
                    }
                }
            }
            else {
                foreach ($data as $key => $value) {
                    foreach ($value as $c_key => $c_value) {
                        if(in_array($c_key, $field)) {
                            $data[$key][$c_key] = strip_tags(htmlspecialchars_decode($c_value));
                        }
                    }
                }
            }
            return $data;
        }
        else {
            return strip_tags(htmlspecialchars_decode($data));
        }
    }
}

/**
 * 转换app大小
 */
if(! function_exists('byte_to_m')) {
    function byte_to_m($baty = 0) {
        if($baty <= 0) {
            return 0;
        }
        else {
            return round($baty / 1024 /1024, 2);
        }
    }
}


/**
 * 导出excel表格
 *
 * @param   array $columName 第一行的列名称
 * @param   array $list 二维数组
 * @param   string $setTitle sheet名称
 * @param  string $fileName
 * @return string
 * @throws Exception
 */
function export($columName,$list,$setTitle='Sheet1',$fileName='demo')
{
    if ( empty($columName) || empty($list) ) {
        throw new \Exception('列名或者内容不能为空');
    }

    if ( count($list[0]) != count($columName) ) {
        throw new \Exception('列名跟数据的列不一致');
    }

    //实例化PHPExcel类
    $PHPExcel    =    new PHPExcel();

    $PHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");


    $obj = $PHPExcel->setActiveSheetIndex(0);
    //获得当前sheet对象
    $PHPSheet    =    $PHPExcel->getActiveSheet();
    //定义sheet名称
    $PHPSheet->setTitle($setTitle);

    //excel的列
    $letter        =    array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'AA','AB', 'AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM', 'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
        'BA','BB', 'BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM', 'BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
    );

    //把列名写入第1行 A1 B1 C1 ...
    for ($i=0; $i < count($list[0]); $i++) {
        //$letter[$i]1 = A1 B1 C1  $letter[$i] = 列1 列2 列3
        $obj->setCellValue("$letter[$i]1","$columName[$i]");
    }
    //内容第2行开始
    foreach ($list as $key => $val) {
        //array_values 把一维数组的键转为0 1 2 3 ..
        foreach (array_values($val) as $key2 => $val2) {
            //$letter[$key2].($key+2) = A2 B2 C2 ……
            $obj->setCellValue($letter[$key2].($key+2),$val2);
        }
    }

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $fileName .'.xls"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
    $objWriter->save('php://output');
}
//生成当前时间戳的三位毫秒数
if(! function_exists('get_time_tail')) {
    function get_time_tail() {
        $tail = floor(microtime() * 1000);

        if ($tail < 10) {
            $tail = $tail * 100;
        } elseif ($tail < 100) {
            $tail = $tail * 10;
        } else {
            $tail = $tail * 1;
        }

        return $tail;
    }
}

//去除p标签，将换行换成<br/><br/>
if(! function_exists('format_detail_html')) {
    function format_detail_html($str = '') {
        $str = preg_replace('/<([a-z]+)\s+[^>]*>/is', '<$1>', $str);    // 去掉标签属性
        $str = preg_replace('/<p><span><br\/><\/span><\/p>/', '<p></p>', $str); // 换行换成<br/><br/>
        $str = preg_replace('/<p><br\/><\/p>/', '<p></p>', $str);
        $str = preg_replace('/<\/p><p>/', '<br/>', $str);
        $str = preg_replace('/<p>/', '', $str);         // 去除头尾p标签
        $str = preg_replace('/<\/p>/', '', $str);       // 去除头尾/p标签
        return $str;
    }
}

/**
 * 格式化参数格式化成url参数
 * @param array $not_sign_param     不参与加密的参数名
 * @return string                   返回拼接后的参数
 */
function to_url_params($param_arr, $not_sign_param = array())
{
    $buff = "";
    foreach ($param_arr as $k => $v) {
        if (!in_array('sign', $not_sign_param)) {
            array_push($not_sign_param, 'sign');
        }
        if (!in_array($k, $not_sign_param) && $v != "" && !is_array($v)) {
            $buff .= $k . $v;
        }
    }
    return $buff;
}

/**
 * 生成签名
 * @param array $not_sign_param     不参与加密的参数名
 * @return string                   签名，本函数不覆盖sign成员变量
 */
function make_sign($param_arr, $not_sign_param = array())
{
    // 签名步骤一：按字典序排序参数(对关联数组按照键名进行升序排序)
    ksort($param_arr);
    $string = to_url_params($param_arr, $not_sign_param);
    // 签名步骤二：在string后加入KEY
    $string = $string . "appkey" . C("APP.KEY");
    // 签名步骤三：MD5加密
    $string = md5($string);
    // 签名步骤四：所有字符转为大写
    $result = strtoupper($string);
    return $result;
}

function thumb_image($file_info, $file_conf) {
    $image = new \Think\Image();
    $file = $file_info['file'];
    $thumb_file = $file_conf['file_path'] . '/' . $file['savepath'] . $file['savename'];
    $save_path = $file_conf['file_path'] . '/' . $file['savepath'] . $file_conf['thumb_path'];
    // 创建目录
    if (!is_dir($save_path)) {
        mkdir($save_path, 0777, true);
        chmod($save_path, 0777);
    }
    $save_file_path = $save_path . $file['savename'];
    $image->open( $thumb_file )->thumb( $file_conf['thumb_width'], $file_conf['thumb_height'], \Think\Image::IMAGE_THUMB_FILLED )->save($save_file_path);
    return $save_file_path;
}

/**
 * 递归获取id数组
 * @author xy
 * @since 2017/07/28 15:44
 * @param array $data 原数组
 * @param int $parentId 父级id
 * @param array $newArray 新数组
 * @param string $parentField 父级id的字段名称
 * @param string $idField //id字段名称
 * @return array|bool
 */
function get_id_array_recursive(array $data,$parentId,array &$newArray,$parentField = 'parent_id',$idField = 'id'){
    if(empty($data)){
        return false;
    }
    foreach ($data as $key=>$value){
        if( $value[$idField] == $parentId){
            $newArray[] = $value[$idField];
        }
        if($value[$parentField] == $parentId){
            $newArray[] = $value[$idField];
            get_id_array_recursive($data, $value[$idField], $newArray, $parentField, $idField);
        }
    }
    return array_unique($newArray);
}

/**
 * 递归获取分类名称
 * @author xy
 * @since 2017/07/31 13:51
 * @param array $data 分类数组
 * @param integer $id id值
 * @param array $newArray 保存分类名称的数组
 * @param string $parentField 父级id字段名称
 * @param string $idField id字段名称
 * @param string $categoryNameField 分类名称字段名称
 * @return array|bool
 */
function get_category_name_recursive(array $data, $id, array &$newArray, $parentField = 'parent_id', $idField = 'id',  $categoryNameField = 'name'){
    if(empty($data)){
        return false;
    }
    foreach ($data as $key=>$value){
        if($value[$idField] == $id){
            $newArray[] = $value[$categoryNameField];
            get_category_name_recursive($data, $value[$parentField], $newArray, $parentField, $idField, $categoryNameField);
        }
    }
    return array_unique($newArray);
}

/**
 * 递归获取无极限游戏分类数组
 * @author xy
 * @since 2017/08/28 15:19
 * @param array $data 原数组
 * @param integer $parentId 父级id
 * @param integer $currentLevel 当前层级
 * @param integer $levelLimit 限制层级
 * @param string $levelField 层级的数组键值
 * @param string $idField id字段的名称
 * @param string $parentField 父级id字段的名称
 * @param string $childField 保存子类的数组键值
 * @param string $hasChildField 保存是否有子类的数组键值
 * @return array|string
 */
function get_app_type_tree_recursive(array $data, $parentId = 0, $currentLevel, $levelLimit = 3, $levelField = 'level', $idField = 'id', $parentField = 'parent_id', $childField = 'children', $hasChildField = 'has_child')
{
    if(empty($data)){
        return false;
    }
    $tree = array();

    foreach($data as $k => $v)
    {
        if($v[$parentField] == $parentId)
        {
            //父亲找到儿子
            if($v[$hasChildField] > 0 || ($currentLevel == $levelLimit && $v[$hasChildField] == 0)){
                $v[$childField] = get_app_type_tree_recursive($data, $v[$idField], $currentLevel+1, $levelLimit);
                $v[$levelField] = $currentLevel;
                if($currentLevel < $levelLimit){
                    unset($v[$idField]);
                }
                $tree[] = $v;
            }
        }

    }
    return $tree;
}
