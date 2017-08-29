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
    <title>编辑图片栏目分类</title>
</head>
<body>
<article class="page-container">
    <form class="form form-horizontal" id="form-add" novalidate="novalidate" action="<?php echo U('Admin/Ad/category_edit');?>">
        <input type="hidden" value="<?php echo ($categoryInfo["id"]); ?>" name="id">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>栏目分类：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo ($categoryInfo["name"]); ?>" placeholder="请输入栏目分类名称" id="name" name="name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>关键词：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo ($categoryInfo["keyword"]); ?>" placeholder="请输入唯一关键词" id="keyword" name="keyword" disabled>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>图片数量：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo ($categoryInfo["image_num_limit"]); ?>" placeholder="当前分类允许上传图片的数量，0不限制" id="image_num_limit" name="image_num_limit">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">分类备注：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo ($categoryInfo["image_size"]); ?>" placeholder="描述分类相关信息，如图片宽高大小信息" id="image_size" name="image_size">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>状态：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box">
                    <select name="is_delete" class="select valid" aria-required="true" aria-invalid="false">
                        <option value="1" <?php if($categoryInfo['is_delete'] == 1): ?>selected<?php endif; ?>>正常</option>
                        <option value="2" <?php if($categoryInfo['is_delete'] == 2): ?>selected<?php endif; ?>>删除</option>
                    </select>
                    <label id="articlecolumn-error" class="error valid" style="display: block;"></label>
				</span>
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
                name: {
                    required: true
                },
                image_size:{
                    required: true
                },
                image_num_limit:{
                    required: true,
                    digits:true
                }
            },
            messages: {
                name: {
                    required: "请填写分类名称！"
                },
                image_size:{
                    required: "请填写图片信息，如图片宽高"
                }
            },
            onkeyup: false,
            focusCleanup: true,
            success: "valid",
            submitHandler: function (form) {
                $(form).ajaxSubmit({
                    type: "post",  //提交方式
                    dataType: "json", //数据类型
                    data: {},
                    url: "<?php echo U('Admin/Ad/category_edit');?>", //请求url
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
    //关闭窗口
    function removeIframe(){
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
    }
</script>
</body>
</html>