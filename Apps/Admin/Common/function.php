<?php

if ( ! function_exists('get_children_category_array'))
{
    /**
     * 获取指定父id下的所有子孙级类别(数组，并且经过了排序的)
     * (自动添加两个字段(深度-以1为起点, 是否有子类别)，字段名可默认，也可自己取$level_name,$has_child_name)
     *
     * @param  array 			$data_old			用于过滤的原始数组
     * @param  int 				$parend_id			父级id
     * @param  string 			$id_name			原始数组中记录id字段的字段名 			(默认值 -- id)
     * @param  string 			$parend_id_name		原始数组中记录父id字段的字段名		(默认值 -- parent_id)
     * @param  string			$level_name			新数组中保存深度的字段名				(默认 -- level)
     * @param  string			$has_child_name		新数组中标记是否有子类别字段名		(默认 -- has_child)
     * @return array
     */
    function get_children_category_array($data_old, $parend_id = 0, $id_name = 'id', $parend_id_name = 'parent_id', $level_name = 'level', $has_child_name = 'has_child')
    {
        // 循环原数组，  和  添加 三个相应的字段
        foreach ($data_old as $key => $value)
        {
            // 添加记录深度的字段
            $data_old[$key][$level_name] = 0;
            // 添加是否有子类的字段
            $data_old[$key][$has_child_name] = 0;
            // 查找是否有子类
            foreach ($data_old as $temp_key => $temp_value)
            {
                if($data_old[$key][$id_name] == $temp_value[$parend_id_name])
                {
                    $data_old[$key][$has_child_name] = 1;
                    break;
                }
            }
        }
        // 创建一个用于存放结果的 数组
        $type_array = array();
        // 调用递归函数
        dg_get_child_type_array($data_old,$type_array,$parend_id,$id_name,$parend_id_name,$level_name,$has_child_name,1);
        return $type_array;
    }
}

if ( ! function_exists('dg_get_child_type_array'))
{
    /**
     * 递归过滤数组
     *
     * @param 	array 		$all_array			用于过滤的原始数组
     * @param 	array 		$new_array			用于接收结果的新数组
     * @param 	int 		$parent_id			父级id
     * @param 	string 		$id_name			原始数组中记录id字段的字段名
     * @param 	string 		$parend_id_name		原始数组中记录父id字段的字段名
     * @param  	string		$level				深度字段名
     * @param  	string		$has_child_name		是否有子类别字段名
     * @param	int			$level				当前深度值
     */
    function dg_get_child_type_array($all_array,&$new_array,$parent_id,$id_name,$parend_id_name,$level_name ,$has_child_name,$level)
    {
        foreach($all_array as $key => $value)
        {
            // 如果记录的父id为我们要查找的
            if($value[$parend_id_name] == $parent_id)
            {
                $value[$level_name] = $level;
                $new_array[] = $value;
                if($value[$has_child_name] > 0)
                {
                    dg_get_child_type_array($all_array,$new_array,$value[$id_name],$id_name,$parend_id_name,$level_name,$has_child_name,$level+1);
                }
            }
        }
    }
}


if ( ! function_exists('get_children_category_tree'))
{
    /**
     * 获取指定父id下的所有子孙级类别 (树型结构)
     * (自动添加三个字段(深度-以1为起点,子类别,是否有子类别)，字段名可默认，也可自己取$level_name,$childs_name,$has_child_name)
     *
     * @param  array 			$data_old			用于过滤的原始数组
     * @param  int 				$parend_id			父级id
     * @param  string 			$id_name			原始数组中记录id字段的字段名 			(默认值 -- id)
     * @param  string 			$parend_id_name		原始数组中记录父id字段的字段名		(默认值 -- parent_id)
     * @param  string			$level_name			新数组中保存深度的字段名				(默认 -- level)
     * @param  string			$childs_name		新数组中保存子类别字段名				(默认 -- childs)
     * @param  string			$has_child_name		新数组中标记是否有子类别字段名		(默认 -- has_child)
     *
     * @return array
     */
    function get_children_category_tree($data_old, $parend_id = 0, $id_name = 'id', $parend_id_name = 'parent_id', $level_name = 'level', $childs_name = 'childs', $has_child_name = 'has_child')
    {
        // 循环原数组，  和  添加 三个相应的字段
        foreach ($data_old as $key => $value)
        {
            // 添加记录深度的字段
            $data_old[$key][$level_name] = 0;
            // 添加是否有子类的字段
            $data_old[$key][$has_child_name] = 0;
            // 查找是否有子类
            foreach ($data_old as $temp_key => $temp_value)
            {
                if($data_old[$key][$id_name] == $temp_value[$parend_id_name])
                {
                    $data_old[$key][$has_child_name] = 1;
                    break;
                }
            }
            // 添加用于存放子类的字段
            $data_old[$key][$childs_name] = array();
        }
        // 创建一个用于存放结果的 数组
        $type_array = array();
        // 调用递归函数
        dg_get_child_type_tree($data_old,$type_array,$parend_id,$id_name,$parend_id_name,$level_name,$childs_name,$has_child_name,1);
        return $type_array;
    }
}

if ( ! function_exists('dg_get_child_type_tree'))
{
    /**
     * 递归过滤数组
     *
     * @param 	array 		$all_array			用于过滤的原始数组
     * @param 	array 		$new_array			用于接收结果的新数组
     * @param 	int 		$parent_id			父级id
     * @param 	string 		$id_name			原始数组中记录id字段的字段名
     * @param 	string 		$parend_id_name		原始数组中记录父id字段的字段名
     * @param  	string		$level_name			深度字段名
     * @param  	string		$childs_name		子类别字段名
     * @param  	string		$has_child_name		是否有子类别字段名
     * @param  	string		$level				当前深度值
     */
    function dg_get_child_type_tree($all_array, &$new_array, $parent_id, $id_name, $parend_id_name, $level_name, $childs_name, $has_child_name,$level)
    {
        foreach($all_array as $key => $value)
        {
            // 如果记录的父id为我们要查找的
            if($value[$parend_id_name] == $parent_id)
            {
                $value[$level_name] = $level;
                $new_array[$key] = $value;
                if($value[$has_child_name] > 0)
                {
                    dg_get_child_type_tree($all_array,$new_array[$key][$childs_name],$value[$id_name],$id_name,$parend_id_name,$level_name,$childs_name,$has_child_name,$level+1);
                }
            }
        }
    }
}


if ( ! function_exists('get_option_html'))
{
    /**
     * 获取下拉框中的option代码
     *
     * @param 	$array_data		array		类别数组(树型结构)
     * @param 	$result_str		&string		接收html代码的变量
     * @param	$field_config	array		字段描述
     * @param	$field_value	string		选中的项(默认为  NULL)
     * @param	$field_debar	array		要排除的项(默认为  NULL)
     * @param	$level_arr		array		深度(默认为  NULL)
     *
     *
     * @example	$field_config = array(
     * @example							'field' => '',					// 关键字段名
     * @example							'value_name' => '',				// 写入value的字段名
     * @example							'text_name' => '',				// 写入text的字段名
     * @example							'level_name' => 'level',		// 记录深度的字段名
     * @example							'has_child' => 'has_child',		// 记录是否存在子节点的字段名
     * @example							'childs' => 'childs' 			// 记录存放子节点的字段名
     * @example						);
     */
    function get_option_html($array_data, &$result_str, $field_config, $field_value = NULL, $field_debar = NULL, $level_arr = NULL)
    {
        foreach($array_data as $key => $value)
        {
            // 如果深度在范围内，或者深度设置为不限
            if ($level_arr === NULL || in_array($value[$field_config['level_name']], $level_arr))
            {
                // 如果不在要排除的项中或者排除项设置为不排除
                if($field_debar === NULL || !in_array($value[$field_config['field']], $field_debar))
                {
                    $space_str = get_space_string(($value[$field_config['level_name']] - 1)*2);
                    $result_str .= '<option '.($value[$field_config['field']] == $field_value? 'selected="selected"':'').' value="'.$value[$field_config['value_name']].'">'.$space_str.$value[$field_config['text_name']].'</option>';
                    if($value[$field_config['has_child']] == 1)
                    {
                        get_option_html($value[$field_config['childs']], $result_str, $field_config , $field_value, $field_debar, $level_arr);
                    }
                }
            }
        }
    }
}

if ( ! function_exists('get_clean_option_html'))
{
    /**
     * 获取干净的下拉框标签中option的html代码
     *
     * @param 	$array_data		array		类别数组(普通数组)
     * @param	$field_config	array		字段描述 <br/>
     * array(											<br/>
     * 			'field' 	 	=> '',		// 关键字段名<br/>
     * 			'value_name' 	=> '',		// 写入value的字段名<br/>
     * 			'text_name'  	=> '',		// 写入text的字段名<br/>
     * 			'inner_option' 	=> '',		// 写入前几项的option<br/>
     * 		);
     * @param	$field_value	string		选中的项(默认为  NULL)
     * @param	$field_debar	array		要排除的项(默认为 array())
     *
     *

     *
     * @return					string		返回select的html代码
     */
    function get_clean_option_html($array_data, $field_config, $field_value = NULL, $field_debar = array())
    {
        $result_str = '';
        $result_str = $field_config['inner_option'];
        foreach($array_data as $key => $value)
        {
            // 如果不在要排除的项中或者排除项设置为不排除
            if(empty($field_debar) || !in_array($value[$field_config['field']], $field_debar))
            {
                $result_str .= '<option '.($value[$field_config['field']] == $field_value? 'selected="selected"':'').' value="'.$value[$field_config['value_name']].'">'.$value[$field_config['text_name']].'</option>';
            }
        }
        return $result_str;
    }
}

if ( ! function_exists('get_space_string'))
{
    /**
     * 获取指定长度的空格字符串
     *
     * @param 	$space_num			int			长度
     *
     * @return						string		&nbsp;字符串
     *
     */
    function get_space_string($space_num = 0)
    {
        $result = "";
        for($i = 0; $i < $space_num; $i++)
        {
            $result .= "&nbsp;";
        }
        return $result;
    }
}

/**
 * 获取导航栏面包屑
 */
function get_nav()
{
    $name = CONTROLLER_NAME.'/'.ACTION_NAME;
    $auth_rule = M('auth_rule')->field('id,pid,name,title,css')->where("name='$name'")->find();
    $nav_html = "<span class=\"c-gray en\">&gt;</span>".$auth_rule['title'];

    if(!empty($auth_rule))
    {
        if($auth_rule['pid'] > 0)
        {
            dg_get_nav($auth_rule['pid'], $nav_html);
        }
    }
    return $nav_html;
}

/**
 * 递归获取父类导航栏
 * @param $pid          父类ID
 * @param $nav_html     返回的导航栏面包屑
 */
function dg_get_nav($pid, & $nav_html)
{
    $p_auth_rule = M('auth_rule')->field('id,pid,name,title,css')->where("id=$pid")->find();
    if(!empty($p_auth_rule))
    {
        if($p_auth_rule['pid'] == 0)
        {
            $nav_html = "<i class='Hui-iconfont ".$p_auth_rule['css']."'></i>".$p_auth_rule['title'].$nav_html;
        }
        else
        {
            $nav_html = "<span class=\"c-gray en\">&gt;</span>".$p_auth_rule['title'].$nav_html;
        }

    }
    if($p_auth_rule['pid'] > 0)
    {
        dg_get_nav($p_auth_rule['pid'], $nav_html);
    }
}

/**
 * 删除一维数组里的多个key
 */
function arr_unset(&$data,$keys){
    if($keys!='' && is_array($data)){
        $key = explode(',',$keys);
        foreach ($key as $v)unset($data[$v]);
    }
}

/**
 * 返回包含指定的数组下标的新数组
 * @param array $array_index    要处理的参数下标
 * @param array $data_source    所需的参数数组
 * @param array $arr            存储的数组
 * @param bool  $is_except      true要排除下标，false表示要返回的下标
 * @return string
 */
function get_designated_array($array_index = array(), $data_source = array(), &$arr, $is_except = false) {
    if(empty($array_index) || empty($data_source)) {
        return array();
    }
    foreach($data_source as $key => $val) {
        if(is_array($val)) {
            $data_source[$key] = implode(',', $val);
        }
        if(!empty($array_index)) {
            if(!$is_except && !in_array($key, $array_index)) {
                unset($data_source[$key]);
            }
            if($is_except && in_array($key, $array_index)) {
                unset($data_source[$key]);
            }
        }
    }
    $arr = $data_source;
}

/**
 * 产生随机字符串
 * 产生一个指定长度的随机字符串,并返回给用户
 * @access public
 * @param int $len 产生字符串的位数
 * @return string
 */

function genRandomString($len = 6) {
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

/**
 * 后台密码加密
 * @param $pass
 * @param string $verify
 * @return string
 */
function encryption($pass,$verify=""){
    $pass = md5($pass.md5($verify));
    return $pass;
}


if ( ! function_exists('is_one_region_active'))
{
    /**
     * 验证指定id和类型的区域是否正确(国家、省份、城市、区县)
     *
     * @param	int 	$ad_code		待判断的区域编码
     * @param	int 	$Region_Type	区域类型 0国家，1省，2市，3区
     *
     * @return	bool					有效返回TRUE，无效返回FALSE
     */
    function is_one_region_active($ad_code = 0, $Region_Type = SITE_COUNTRY)
    {
        if(!is_int($ad_code) || !is_int($Region_Type) || $ad_code < 0 || !in_array($Region_Type, array(0, 1, 2, 3)))
        {
            return FALSE;
        }
        // 设置查询条件
        $where = array();
        // 判断省份是否正确
        $where['ad_code'] = $ad_code;
        $where['level'] = $Region_Type;
        $where['is_active'] = 1;
        $Region_Info = M('region')->where($where)->getField('ad_code');
        return empty($Region_Info)? FALSE : TRUE;
    }
}

if ( ! function_exists('get_region_option'))
{
    /**
     * 获取指定父级区域的子级区域列表(option 的html代码)(国家、省份、城市、区县)
     *
     * @param 	int			$Parent_ID			父级区域id
     * @param	int			$Region_Type		子级区域类型 0国家，1省，2市，3区
     * @param	string		$select_item		选中项
     *
     */
    function get_region_option($Parent_ID = 0, $Region_Type = 0, $select_item = 0)
    {
        $inner_option = '<option value="0">请选择</option>';
        $result = array("error" => FALSE, "html" => $inner_option, "type_id" => $Region_Type);
        if($Parent_ID < 0 || !in_array($Region_Type, array(0, 1, 2, 3)))
        {
            $result['error'] = TRUE;
            $result['info'] = '参数错误';
            return $result;
        }
        // 设置查询条件
        $where = array();
        $where['parent_id'] = $Parent_ID;
        $where['level'] = $Region_Type;
        $Region_list = M('region')->where($where)->select();
        // 设置拼接条件
        $field_config = array(
            'field'		 	=> 'ad_code',
            'value_name' 	=> 'ad_code',
            'text_name' 	=> 'region_name',
            'inner_option' 	=> $inner_option
        );
        // 拼接html
        $result['html'] = get_clean_option_html($Region_list, $field_config, $select_item);
        // 返回html
        return $result;
    }
}

if ( ! function_exists('check_region_group'))
{
    /**
     * 验证区域组合是否正确(省份、城市、区县)
     *
     * @param	int		$group_type		区域组合类型，3 = 省-市-区(默认)，2 = 省-市，1 = 省
     * @param 	int		$Province		省份ID
     * @param 	int		$City			城市ID
     * @param 	int		$County			区县ID
     *
     * @return	bool					组合正确返回TRUE，错误返回FALSE
     */
    function check_region_group($group_type = 3, $Province = 0, $City = 0, $County = 0)
    {
        switch($group_type)
        {
            case 1:			// 验证“省”
                if($Province <= 0 || $City != 0 || $County != 0)
                {
                    return FALSE;
                }
                break;
            case 2:			// 验证“省-市”组合
                if($Province <= 0 || $City <= 0 || $County != 0)
                {
                    return FALSE;
                }
                break;
            case 3:			// 验证“省-市-区”组合
                if($Province <= 0 || $City <= 0 || $County <= 0)
                {
                    return FALSE;
                }
                break;
            default:
                return FALSE;
                break;
        }
        // 设置查询条件
        $where = array();
        // 判断省份是否正确
        $where['ad_code'] = $Province;
        $where['parent_id'] = 1;
        $where['level']     = 1;
        $Province_Info = M('region')->where($where)->find();
        if(empty($Province_Info))
        {
            return FALSE;
        }
        if(in_array($group_type, array(2,3)))
        {
            $where = array();
            // 判断城市是否正确
            $where['ad_code'] = $City;
            $where['parent_id'] = $Province;
            $where['level']     = 2;
            $City_Info = M('region')->where($where)->find();
            if(empty($City_Info))
            {
                return FALSE;
            }
        }
        if(in_array($group_type, array(3)))
        {
            $where = array();
            // 判断区县是否正确
            $where['ad_code'] = $County;
            $where['parent_id'] = $City;
            $where['level']     = 3;
            $County_Info = M('region')->where($where)->find();
            if(empty($County_Info))
            {
                return FALSE;
            }
        }
        return TRUE;
    }
}

if ( ! function_exists('get_change_region_option_html'))
{
    /**
     * ajax获取指定父级区域ID的子级区域的option的html(原生json)
     *
     * @return	json	返回json格式的结果(错误{"error":true}，成功{"error":FALSE,"html":"option的html代码","type_id":"子级区域类型"}
     *
     */
    function get_change_region_option_html()
    {
        $reg_id = I('reg_id');
        if(!empty($reg_id)) {
            $ajax_arr['reg_id'] = intval($reg_id);
        }
        $type_id = I('type_id');
        if(!empty($reg_id)) {
            $ajax_arr['type_id'] = intval($type_id);
        }
        if(!isset($ajax_arr['reg_id']) || !isset($ajax_arr['type_id']))
        {
            $result['error'] = TRUE;
            $result['info'] = '参数错误';
            ajax_return($result);
        }
        // 获取父级区域和子级类型
        if($reg_id < 0 || !in_array($type_id, array(0, 1, 2, 3)))
        {
            $result['error'] = TRUE;
            $result['info'] = '类型错误';
            ajax_return($result);
        }
        $result = get_region_option($reg_id, $type_id);
        ajax_return($result);
    }
}

if ( ! function_exists('filter_region_group'))
{
    /**
     * 过滤“省-市-区县”组合(返回值中，对不符合的选项其id设置0)
     *
     * @param	int		$province		选中的省份ID
     * @param	int		$city			选中的城市ID
     * @param	int		$county			选中的区县ID
     *
     * @return	array					返回一维数组array("province_id" => 0, "city_id" => 0, "county_id" => 0)
     */
    function filter_region_group($Province = 0, $City = 0, $County = 0)
    {
        $result = array("province_id" => 0, "city_id" => 0, "county_id" => 0);
        // 设置查询条件
        $where = array();
        if($Province <= 0)
        {
            return $result;
        }
        // 判断省份是否正确
        $where['ad_code'] = $Province;
        $where['parent_id'] = 100000;
        $where['level']     = 1;
        $Province_Info = $Province_Info = M('region')->where($where)->find();
        if(empty($Province_Info))
        {
            return $result;
        }
        $result['province_id'] = $Province;
        if($City <= 0)
        {
            return $result;
        }
        // 判断城市是否正确
        $where['ad_code'] = $City;
        $where['parent_id'] = $Province;
        $where['level']     = 2;
        $City_Info = $Province_Info = M('region')->where($where)->find();
        if(empty($City_Info))
        {
            return $result;
        }
        $result['city_id'] = $City;
        if($County <= 0)
        {
            return $result;
        }
        // 判断区县是否正确
        $where['ad_code'] = $County;
        $where['parent_id'] = $City;
        $where['level']     = 3;
        $County_Info = $Province_Info = M('region')->where($where)->find();
        if(empty($County_Info))
        {
            return $result;
        }
        $result['county_id'] = $County;
        return $result;
    }
}

if ( ! function_exists('get_region_group_option_html'))
{
    /**
     * 获取“省-市-区县”组合的下拉框option代码
     *
     * @param	int		$province		选中的省份ID
     * @param	int		$city			选中的城市ID
     * @param	int		$county			选中的区县ID
     * @param	bool	$is_filter		是否对组合进行过滤(默认TRUE = 过滤，FALSE = 不过滤)
     *
     * @return	array					返回一维数组array("province" => "", "city" => "", "county" => "")
     */
    function get_region_group_option_html($province = 0, $city = 0, $county = 0, $is_filter = TRUE)
    {
        if($is_filter)
        {
            $Active_Region_Group = filter_region_group($province, $city, $county);
            $province 	= $Active_Region_Group['province_id'];
            $city 		= $Active_Region_Group['city_id'];
            $county 	= $Active_Region_Group['county_id'];
        }
        $result = array("province" => "", "city" => "", "county" => "");
        // 获取省份的option代码
        $st_province_str = get_region_option(100000, 1, $province);
        $result['province'] = $st_province_str['html'];
        // 获取城市的option代码
        $st_city_str = get_region_option($province, 2, $city);
        $result['city'] = $st_city_str['html'];
        // 获取区县的option代码
        $st_county_str = get_region_option($city, 3, $county);
        $result['county'] = $st_county_str['html'];
        return $result;
    }
}

/**
 * 判断字符串是否是英文字符、下划线、字母组成
 * @author xy
 * @since 2017/07/24 15:25
 * @param string $keyword
 * @return bool
 */
function checkStringIsEnChar($keyword){
    if(empty($keyword)){
        return false;
    }
    //判断是否是英文字符、下划线、字母组成
    if (!preg_match('/^[_0-9a-zA-Z]/i', $keyword)) {
        return false;
    }
    return true;
}

/**
 * 判断字符串是否是正确的url
 * @author xy
 * @since 2017/07/24 18:09
 * @param string $keyword
 * @return bool
 */
function checkStringIsUrl($url){
    if(empty($url)){
        return false;
    }
    $pattern = '/^(http|https|ftp):\/\/[A-Za-z0-9][A-Za-z0-9\-\.]+[A-Za-z0-9][\.]*[A-Za-z]{2,}[\43-\176]*$/';
    //$pattern = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
    if (preg_match($pattern, $url,$matches)){
        return true;
    } else {
        return false;
    }
}