/**
 * Created by songsl on 2017/3/10.
 */

/**
 * 绑定指定单元单击修改事件
 *
 * @param   string        BindTarget              用于JQ选择器定位到要绑定的标签的关键字
 * @param   string        datatag                 存放记录惟一标识的属性名
 * @param   string        dataval                 存放完整的原始值的属性名
 * @param   string        sub_len                 字符串截取宽度
 * @param   string        field_tag               要更新的字段的标识（注：请不要直接使用数据库字段名，不安全）
 * @param   string        ajax_url                处理ajax的页面地址
 * @param   function      success_callback        更新成功后要执行的回调函数（可选）（该回调函数必须带三个参数）
 *
 */
function bind_ajax_change_value(BindTarget, datatag, dataval, sub_len, field_tag, ajax_url, success_callback) {
    if (datatag == '' || dataval == '' || field_tag == '' || ajax_url == '') {
        return false;
    }
    sub_len = parseInt(sub_len);
    sub_len = isNaN(sub_len) ? 0 : sub_len;
    // 绑定列表鼠标移过改变背景颜色的事件
    $(BindTarget).mouseover(function () {
        $(this).addClass("ajax_edit");
    });
    $(BindTarget).mouseout(function () {
        $(this).removeClass("ajax_edit");
    });
    // 绑定容器点击事件
    $(BindTarget).click(function () {
        // 获取容器标签对象
        var this_obj = $(this);
        // 获取数据库中要修改的那条记录的标识
        var data_tag_value = this_obj.attr(datatag);
        if (data_tag_value == '') {
            alert("对不起，您的参数错误！请刷新页面后重试！");
            return false;
        }
        // 获取原始的完整值
        var data_value = this_obj.attr(dataval);
        // 获取窗口标签的父级标签的宽度
        var this_parent_width = this_obj.parent().width() - 4;
        // 加入input标签
        this_obj.html('<input type="text" style = "width:' + this_parent_width + 'px;" value ="" class="input-text radius size-MINI" />');
        // 获取input对象
        var input_obj = this_obj.children('input').first();
        // 把值写进input
        input_obj.val(data_value);
        // 把焦点定位到input
        input_obj[0].focus();
        // 防止冒泡事件
        input_obj.click(function (e) {
            e.stopPropagation();
        });
        // 绑定input的回车事件
        input_obj.keydown(function (e) {
            if (e.which == 13) {
                input_obj[0].blur();
                e.stopPropagation();
            }
        });
        // 绑定文本框失去焦点事件
        input_obj.blur(function () {
            // 取出input值
            var param = $(this).val();
            // ajax处理
            if (param == data_value) {
                // 如果没有进行过修改，直接返回
                bind_ajax_change_value_Respones({
                    "error": false,
                    "info": "",
                    "new_value": data_value
                }, this_obj, data_value, dataval, sub_len, "");
            }
            else {
                // 如果有做了修改，进行ajax处理
                var record_id = data_tag_value;

                var field_value = param;
                var data = $.ajax({
                    url: ajax_url,
                    global: false,
                    type: "POST",
                    data: ({record_id: record_id, field_tag: field_tag, field_value: field_value}),
                    dataType: "json",
                    async: false
                }).responseText;
                bind_ajax_change_value_Respones(JSON.parse( data ), this_obj, data_value, dataval, sub_len, success_callback);
            }
        });
    });
}
/**
 * 回调函数
 *
 * @param   json        result               ajax返回的值
 * @param   obj         this_jq_obj          容器标签的JQ对象
 * @param   string      old_value            原始的完整值，用于如果ajax处理失败时，恢复span为原来的值
 * @param   string      dataval              获取完整记录值的属性名
 * @param   string      sub_len              字符串截取宽度
 * @param   function    success_callback     更新成功后要执行的回调函数（可选）（该回调函数必须带5个参数）
 *
 */
function bind_ajax_change_value_Respones(result, this_jq_obj, old_value, dataval, sub_len, success_callback) {
    if (result.error == false) {
        // 如果ajax修改成功，修改为新的值
        this_jq_obj.text(get_subStr(result.new_value, 0, sub_len, false, '...'));
        this_jq_obj.attr(dataval, result.new_value);
        if (typeof(success_callback) == "function") {
            success_callback(result, this_jq_obj, old_value, dataval, sub_len);
        }
    }
    else {
        alert(result.message);
        // 如果ajax修改错误，把span设置为原始的值
        this_jq_obj.text(get_subStr(old_value, 0, sub_len, false, '...'));
        this_jq_obj.attr(dataval, old_value);
    }
}


/**
 * 自定义字符串截取函数
 *
 *  @param        string        str_value     待截取的源字符串
 *
 *  @param        int            start        要截取的起始 - 字符位置(下标，从0开始算)。
 *                                            start > 0：从源字符串左边开始计算位置；
 *                                            start = 0：以源字符串左边第一个字符为起始位置；
 *                                            start < 0：从源字符串右边(末尾)开始计算位置。
 *
 *  @param        int            sub_len      要截取的"字符数/字节数"。
 *                                            sub_len > 0：从start位置开始向右截取指定"字符数/字节数"；
 *                                            sub_len = 0：从start位置开始向右截取至源字符串末尾；
 *                                            sub_len < 0：从start位置开始向左截取指定"字符数/字节数"。
 *
 *  @param        boolen        len_type      标识是进行 "字符数截取" 还是 "字节数截取"。
 *                                            len_type = true：表示进行    - 字符数截取。sub_len变量所代表的是"字符数"；
 *                                            len_type = false：表示进行    - 字节数截取。sub_len变量所代表的是"字节数"。
 *
 *  @param        string        end_str       截取之后要加在末尾的字符串。
 *
 *  @returns      string                      返回截取完之后的新字符串。(源字符串为空或起始字符位置大于源字符串长度，返回""；源字符串不为字符串类型或数字，则照不处理原样返回。
 */
function get_subStr(str_value, start, sub_len, len_type, end_str) {
    // 如果待截取的数据不是字符串或者数字，则原样返回
    if (typeof(str_value) != "string" && typeof(str_value) != "number") {
        return str_value;
    }
    // 将源数据强制转化为字符串
    str_value = str_value.toString();
    // 如果起始字符位置不合法，则强制设置为0
    start = parseInt(start);
    start = isNaN(start) ? 0 : start;
    // 定义结束字符位置
    var end = 0;
    // 如果截取长度不合法，则强制设置为0
    sub_len = parseInt(sub_len);
    sub_len = isNaN(sub_len) ? 0 : sub_len;
    // 如果截取类型不为bool类型，则强制设置为true(截取字符数)
    len_type = typeof(len_type) == "boolean" ? len_type : true;
    // 如果截取后要加上的字符串不合法，则设置为"..."
    end_str = typeof(end_str) == "string" ? end_str : "...";
    // 获取源字符串的总字符数
    var str_char_count = str_value.length;
    if (str_char_count <= 0) {
        return "";      // 源字符串为空，则直接返回空
    }
    // 验证并重置合法的起始字符位置
    if ((start > 0 && start >= str_char_count) || (start < 0 && (str_char_count + start) < 0)) {
        return "";      // 起始字符位置错误，则直接返回空
    }
    if (start < 0) {
        start = str_char_count + start;
    }
    // 拆分字符串中的字符
    var str_value_array = new Array();      // 字符信息数组
    var str_value_byte_count = 0;                // 字符串总字节长度
    var start_byte = 0;                // 起始字符所在字节位置
    for (var i = 0; i < str_char_count; i++) {
        str_value_array[i] = new Array();
        str_value_array[i]['val'] = str_value.charAt(i);
        str_value_array[i]['byte'] = /^[\u0000-\u00ff]$/.test(str_value_array[i]['val']) ? 1 : 2;
        str_value_byte_count += str_value_array[i]['byte'];
        if (start > i) {
            start_byte += str_value_array[i]['byte'];
        }
    }
    /**** 如果为截取指定字符数 ****/
    if (len_type) {
        /*=== 转化为左起并向右截取 ===*/
        if (sub_len != 0) {
            end = start + sub_len;      // 获取结束字符位置
            // 如果起始字符位置大于结束字符位置，则交换起始和结束的位置
            if (start > end) {
                var temp_var = start;
                start = end;
                end = temp_var;
            }
            start = start < 0 ? 0 : start;  // 如果起始字符位置小于0，则设置起始字符位置为0
            if (start == 0 && end == 0) {
                return "";                  // 如果起点为0且向左截取，则直接返回空值
            }
            sub_len = end - start;
        }
    }
    /**** 如果为截取指定字节长度 ****/
    else {
        /*=== 转化为左起并向右截取 ===*/
        if (sub_len != 0) {
            var end_byte = start_byte + sub_len;            // 获取结束字节位置
            // 如果起始字节位置大于结束字节位置，则交换起始和结束的位置
            if (start_byte > end_byte) {
                var temp_var = start_byte;
                start_byte = end_byte;
                end_byte = temp_var;
            }
            start_byte = start_byte < 0 ? 0 : start_byte;   // 如果起始字节位置小于0，则设置起始字节位置为0
            if (start_byte == 0 && end_byte == 0) {
                return "";                                  // 如果起点为0且向左截取，则直接返回空值
            }
            // 将起始字节位置转化为起始字符位置
            var temp_index_byte = 0;
            var start_find = false;
            var end_find = false;
            for (var n = 0; n < str_value_array.length; n++) {
                // 如果找到了起始字节所在的字符，则重置起始字符位置
                if (start_byte >= temp_index_byte && start_byte < temp_index_byte + str_value_array[n]['byte']) {
                    start = n;
                    start_find = true;
                }
                // 如果找到了结束字节所在的字符，则根据字符类型重置结束字节位置
                if (end_byte >= temp_index_byte && end_byte < temp_index_byte + str_value_array[n]['byte']) {
                    end = end_byte == temp_index_byte ? n : n + 1;
                    end_find = true;
                }
                if (start_find && end_find) {
                    break;
                }
                temp_index_byte += str_value_array[n]['byte'];
            }
            if (!start_find || (!start_find && !end_find)) {
                return "";					// 如果起点未找到，或者起点终点都没找到，则直接返回空值
            }
            else if (start_find && !end_find) {
                sub_len = 0;				// 如果起点找到，终点没找到，则直接从起点截取到末尾
                end = 0;
            }
            else {
                sub_len = end - start;		// 如果起点和终点都找到，则将截取字节数转化为截取字符数
            }
        }
    }
    // 定义截取后的新字符串变量
    var str_cut = new String();
    for (var i = start; i < str_value_array.length; i++) {
        // 如果截取字符数设置为0，则截取到尾部；如果还没达到截取字符数，则继续截取
        if (sub_len == 0 || i < end) {
            str_cut = str_cut.concat(str_value_array[i]['val']);
        }
        else {
            str_cut = (end < str_value_array.length) ? str_cut.concat(end_str) : str_cut;
            break;
        }
    }
    // 截取完成，返回截取后的新字符串
    return str_cut;
}

/**
 * 验证是否为整数
 * @param str
 * @param is_plus   是否是正整数
 * @returns {boolean}
 */
function is_int(str, is_plus) {
    if(str.length != 0){
        if(is_plus) {
            reg=/^[1-9]\d*$/;
        }
        else {
            reg=/^[-+]?\d*$/;
        }
        if(!reg.test(str)){
            return false;
        }
        return true;
    }
}
