<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html lang="en">
<head>
    <!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/Public/H-ui/lib/html5.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/respond.min.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/Public/H-ui/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/Public/H-ui/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/Public/H-ui/lib/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/Public/H-ui/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="/Public/H-ui/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/Public/H-ui/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="/Public/H-ui/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->

<meta name="keywords" content="">
<meta name="description" content="">
<script type="text/javascript" src="/Public/H-ui/lib/jquery/1.9.1/jquery.min.js"></script>
<script>
    var title = '<?php echo ($title); ?>';
    if(title != '') {

        var topWindow=$(window.parent.document),
                show_nav=topWindow.find('#min_title_list');
        show_nav.find('li.active').find('span').html(title);
        var width = 0;
        show_nav.find('li').each(function(){
            width += parseFloat($(this).width()+60);
        });
        show_nav.css({'width':width});
    }
</script>
    <link href="/Public/H-ui/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet">
    <title>添加图片内容</title>
</head>
<body>
<article class="page-container">
    <form class="form form-horizontal" id="form-add" novalidate="novalidate" action="<?php echo U('Admin/Ad/image_add');?>">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>标题名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="请输入标题名称" id="title" name="title" style="width: 300px">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>所属栏目：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="hidden" class="input-text" value="<?php echo ($category["id"]); ?>"  id="category_id" name="category_id" style="width: 300px">
                <input type="text" class="input-text" disabled value="<?php echo ($category["name"]); ?>" placeholder="请输入专题分类ID" style="width: 300px">
            </div>
        </div>
        <?php if($category[keyword] == 'HOT_GUIDE'): ?><div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>专题分类ID：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="请输入专题分类ID" id="art_category_id" name="art_category_id" style="width: 300px">
                </div>
            </div><?php endif; ?>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>图片：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <!--图片上传-->
                <div id="uploader-banner">
                    <!--用来展示图片-->
                    <div id="imagePicture" class="uploader-list">

                    </div>
                    <div class="icon-btn-wrap mt-10">
                        <div id="imagePicker">选择图片</div>
                        <a id="imagePickerUploadBtn" class="btn btn-default">开始上传</a>
                        <span id="pic_info" style="color: #17983b;margin-top: 5px; " class="r"><?php echo ($category["image_size"]); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">跳转链接：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="请输入跳转链接" id="href_link" name="href_link" style="width: 300px">
                <span style="color: #17983b;margin-top: 5px; display: block">格式：http://www.baidu.com</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>是否发布：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box" style="width: 300px">
                    <select id="is_publish" name="is_publish" class="select valid" aria-required="true" aria-invalid="false">
                        <option value="1">发布</option>
                        <option value="2">下架</option>
                    </select>
                    <label id="is_publish-error" class="error valid" style="display: block;"></label>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">排序：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="请输入整数" id="sort" name="sort" style="width: 300px">
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button  class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
                <button onClick="removeIframe();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
            </div>
        </div>
    </form>
</article>
<script type="text/javascript" src="/Public/H-ui/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/icheck/jquery.icheck.min.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/jquery.validation/1.14.0/messages_zh.min.js"></script>
<script type="text/javascript" src="/Public/H-ui/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/Public/H-ui/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/jquery.form/jquery.form.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/webuploader/0.1.5/webuploader.min.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" src="/Public/H-ui/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script>

    $(function() {
        //表单验证
        $("#form-add").validate({
            rules: {
                title: {
                    required: true
                },
                category_id:{
                    required: true,
                    remote:{
                        url: "<?php echo U('Admin/Ad/ajax_check_image_limit');?>",
                        type: "post",
                        dataType: "json",
                        data: {
                            category_id: function () {
                                return $("#category_id").val();　　　　//这个是取要验证的密码
                            }
                        },
                        dataFilter: function (data) {　　　　//判断控制器返回的内容
                            data = $.parseJSON(data);
                            if (data.error) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }
                },
                href_link:{
                    required: true,
                    remote:{
                        url: "<?php echo U('Admin/Ad/ajax_check_url');?>",
                        type: "post",
                        dataType: "json",
                        data: {
                            href_link: function () {
                                return $("#href_link").val();　　　　//这个是取要验证的密码
                            }
                        },
                        dataFilter: function (data) {　　　　//判断控制器返回的内容
                            data = $.parseJSON(data);
                            if (data.error) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }
                },
                sort: {
                    number: true
                }
            },
            messages: {
                title: {
                    required: "请填写标题名称！"
                },
                category_id: {
                    required: "请选择所属栏目分类",
                    remote: "该分类下图片数量超出限制"
                },
                href_link: {
                    required: "请填写跳转链接",
                    remote: "跳转链接格式不正确"
                }
            },
            onkeyup: false,
            focusCleanup: true,
            success: "valid",
            submitHandler: function (form) {
                if(!is_image_success || no_upload_image || !is_update_image){
                    layer.alert('请上传图片', {
                        skin: 'layui-layer-lan',
                        closeBtn: 0,
                        anim: 4 //动画类型
                    });
                    return false;
                }
                $(form).ajaxSubmit({
                    type: "post",  //提交方式
                    dataType: "json", //数据类型
                    data: {image_path:img_url_image[0]},
                    url: "<?php echo U('Admin/Ad/image_add');?>", //请求url
                    success: function (data) { //提交成功的回调函数
                        if (data.error) {
                            $(form).find(":submit").attr("disabled", false).attr("value", "提交");
                            layer.alert(data.detail, {
                                skin: 'layui-layer-lan',
                                closeBtn: 0,
                                anim: 4 //动画类型
                            });
                        } else {
                            window.parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        }
                    }
                });

            }
        });

    });

    //获取分类的备注信息
    var cateId = $('#category_id').val();
    if( !(cateId == ''|| typeof (cateId) == 'undefined' || cateId ==0) ){
        ajaxGetCategoryInfo(cateId);
    } else {
        $('#pic_info').empty();
    }

    //获取分类的备注信息
    $('#category_id').change(function(){
        var cateId = $(this).val();
        if( !(cateId == ''|| typeof (cateId) == 'undefined' || cateId ==0) ){
            ajaxGetCategoryInfo(cateId);
        } else {
            $('#pic_info').empty();
        }
    });

    //获取图片分类的备注信息
    function ajaxGetCategoryInfo(cateId) {
        $.ajax({
            type: "post",  //提交方式
            dataType: "json", //数据类型
            data: {id:cateId},
            url: "<?php echo U('Admin/Ad/ajax_get_category_info');?>", //请求url
            success: function (data) { //提交成功的回调函数
                if (!data.error) {
                    $('#pic_info').empty().html(data.data.image_size)
                }
            }
        });
    }

    //关闭窗口
    function removeIframe(){
        var index = parent.layer.getFrameIndex(window.name);
        // parent.$('.btn-refresh').click();
        parent.layer.close(index);
    }
</script>
<script>
    /*上传背景图片*/
    var img_url_image = [];        // banner图片地址
    var is_image_success = false;  // banner图片上传是否成功
    var no_upload_image = true;    // 是否有上传banner图片文件
    var is_update_image = false;   // 是否有更新上传banner图片文件

    var imageThumbnailWidth = 300;       //缩略图宽度
    var imageThumbnailHeight = 150;      //缩略图高度
    // 初始化Web Uploader
    uploader_1 = WebUploader.create({
        auto: false,    //选完文件后，是否自动上传。false否

        swf: '/Public/H-ui/lib/webuploader/0.1.5/Uploader.swf',// swf文件路径

        server: "<?php echo U('Admin/Ad/ajax_upload_image');?>", // 文件接收服务端。
        formData: {fun: 'upload_ad_image'},

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: {
            multiple: false,
            id: '#imagePicker'
        },

        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif,image/bmp'
        },
        fileSizeLimit: 2 * 1024 * 1024,
    });
    // 点击上传
    $('#imagePickerUploadBtn').click(function () {
        uploader_1.upload();
    });
    uploader_1.on('error', function (error) {
        if (error == 'Q_EXCEED_SIZE_LIMIT') {
            layer.alert('文件大小超过500k，不允许上传', {
                skin: 'layui-layer-lan',
                closeBtn: 0,
                anim: 4 //动画类型
            });
        }
        if (error == 'Q_TYPE_DENIED') {
            layer.alert('文件类型错误', {
                skin: 'layui-layer-lan',
                closeBtn: 0,
                anim: 4 //动画类型
            });
        }
    });
    // 当文件被加入队列之前触发
    uploader_1.on('beforeFileQueued', function (file) {
        uploader_1.reset();
        no_upload_image = false;
        is_update_image = true;
    });
    // 当有文件添加进来的时候
    uploader_1.on('fileQueued', function (file) {
        file_id = file.id;
        var $li = $(
                '<div id="' + file.id + '" class="file-item thumbnail">' +
                '<img>' +
                '<div class="info">' + file.name + '</div>' +
                '</div>'
            ),
            $img = $li.find('img');
        // $list为容器jQuery实例
        $imageList = $("#imagePicture");
        $imageList.html($li);
        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        uploader_1.makeThumb(file, function (error, src) {
            if (error) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }
            $img.attr('src', src);
        }, imageThumbnailWidth, imageThumbnailHeight);
    });
    // 文件上传过程中创建进度条实时显示。
    uploader_1.on('uploadProgress', function (file, percentage) {
        var $li = $('#' + file.id),
            $percent = $li.find('.progress span');

        // 避免重复创建
        if (!$percent.length) {
            $percent = $('<p class="progress"><span></span></p>')
                .appendTo($li)
                .find('span');
        }
        $percent.css('width', percentage * 100 + '%');
    });

    uploader_1.on("uploadAccept", function (file, data) {
        if (data.error) { // 通过return false来告诉组件，此文件上传有错。 return false;
            layer.alert(data.info, {
                skin: 'layui-layer-lan',
                closeBtn: 0,
                anim: 4 //动画类型
            });
            return false;
        }
        else {
            is_image_success = true;
            img_url_image[0] = data.url;
        }
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader_1.on('uploadSuccess', function (file) {
        is_image_success = true;
        $('#' + file.id).addClass('upload-state-done');
    });

    // 文件上传失败，显示上传出错。
    uploader_1.on('uploadError', function (file) {
        is_image_success = false;
        var $li = $('#' + file.id),
            $error = $li.find('div.error');
        // 避免重复创建
        if (!$error.length) {
            $error = $('<div class="error"></div>').appendTo($li);
        }

        $error.text('上传失败');
    });
    // 完成上传完了，成功或者失败，先删除进度条。
    uploader_1.on('uploadComplete', function (file) {
        $('#' + file.id).find('.progress').remove();
    });
</script>
</body>
</html>