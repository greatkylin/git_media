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
    <title>图片表</title>
</head>
<body>
<nav class="breadcrumb">
    图片列表
    <span class="r">
        <a title="全部发布"
           href="javascript:;"
           onclick="publish_all()"
           class="btn btn-success radius" style="text-decoration:none">
            <i class="Hui-iconfont Hui-iconfont-shangjia"></i>全部发布
        </a>
        <a title="编辑分类"
           href="javascript:;"
           onclick="layer_show('编辑','<?php echo U('Admin/Ad/category_edit/id/'.$categoryId);?>', 600, 550)"
           class="btn btn-success radius" style="text-decoration:none">
            <i class="Hui-iconfont Hui-iconfont-edit"></i>编辑分类
        </a>
        <a href="javascript:;" onclick="layer_show('添加','<?php echo U('Admin/Ad/category_add');?>', 600, 550)" class="btn btn-success radius"><i class="Hui-iconfont Hui-iconfont-add2"></i>添加分类</a>
    </span>
</nav>
<div class="page-container">
    <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="text-c">
            <form action="<?php echo U('Admin/Ad/image_list');?>" method="post">
                <input type="text" value="<?php echo ($title); ?>" name="title" placeholder=" 图片名称" style="width:250px" class="input-text">
                <span class="select-box inline">
                    <select name="category_id" class="select">
                        <option value="0">请选择所属栏目</option>
                        <?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val['id']); ?>" <?php if($categoryId == $val['id']): ?>selected<?php endif; ?>><?php echo ($val['name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </span>
                <span class="select-box inline">
                    <select name="is_delete" class="select">
                        <option value="0">删除状态</option>
                        <option value="1" <?php if($isDelete == 1): ?>selected<?php endif; ?>>正常</option>
                        <option value="2" <?php if($isDelete == 2): ?>selected<?php endif; ?>>删除</option>
                    </select>
                </span>
                <span class="select-box inline">
                    <select name="is_publish" class="select">
                        <option value="0">发布状态</option>
                        <option value="1" <?php if($isPublish == 1): ?>selected<?php endif; ?>>发布</option>
                        <option value="2" <?php if($isPublish == 2): ?>selected<?php endif; ?>>下架</option>
                    </select>
                </span>
                <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜图片</button>
                <button name="reset" id="reset" class="btn btn-success"><i class="Hui-iconfont"></i> 重置</button>
            </form>
        </span>
    </div>
    <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="r">
            <a href="javascript:;" onclick="image_add('添加图片','<?php echo U('Admin/Ad/image_add/category_id/'.$categoryId);?>', 700, 680)" class="btn btn-success radius"><i class="Hui-iconfont Hui-iconfont-add"></i>添加图片</a>
        </span>
    </div>
    <table class="table table-border table-bordered table-bg">
        <thead>
        <tr class="text-c">
            <th>ID</th>
            <th>图片名称</th>
            <th>所属分类</th>
            <th>图片</th>
            <th>链接</th>
            <th>状态</th>
            <th>是否发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody id="tbody_data">
        <?php if(is_array($imageList)): $i = 0; $__LIST__ = $imageList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr class="text-c">
                <td><?php echo ($val["id"]); ?></td>
                <td><?php echo ($val["title"]); ?></td>
                <td><?php echo ($val["category_name"]); ?></td>
                <td>
                    <?php if(!empty($val[image_path])): ?><img src="<?php echo (format_url($val["image_path"])); ?>" alt="" width="140px" height="80px"><?php endif; ?>
                </td>
                <td><?php echo ($val["href_link"]); ?></td>
                <td>
                    <?php if($val['is_delete'] == 1): ?>正常<?php endif; ?>
                    <?php if($val['is_delete'] == 2): ?>删除<?php endif; ?>
                </td>
                <td>
                    <?php if($val['is_publish'] == 1): ?>发布<?php endif; ?>
                    <?php if($val['is_publish'] == 2): ?>下架<?php endif; ?>
                </td>
                <td>
                    <a title="编辑"
                       href="javascript:;"
                       onclick="image_edit('编辑图片','<?php echo U('Admin/Ad/image_edit/id/'.$val[id]);?>', 700, 700)"
                       class="btn btn-success radius" style="text-decoration:none">
                        <i class="Hui-iconfont Hui-iconfont-edit2"></i>编辑
                    </a>
                    <?php if($val['is_delete'] == 1){ $statusMessage='确定要删除这条记录吗？'; $statusTips = '删除'; }else{ $statusMessage='确定要恢复这个记录吗？'; $statusTips = '恢复'; } if($val['is_publish'] == 1){ $publishMessage='确定要下架这张图片？'; $publishTips = '下架'; }else{ $publishMessage='确定要发布这张图片吗？'; $publishTips = '发布'; } ?>
                    <a title="<?php echo ($publishTips); ?>"
                       href="javascript:;"
                       onclick="publish_change('<?php echo ($val[id]); ?>','<?php echo ($publishMessage); ?>')"
                       class="btn btn-success radius" style="text-decoration:none">
                        <i class="Hui-iconfont Hui-iconfont"></i><?php echo ($publishTips); ?>
                    </a>
                    <a title="<?php echo ($statusTips); ?>"
                       href="javascript:;"
                       onclick="status_change('<?php echo ($val[id]); ?>','<?php echo ($statusMessage); ?>')"
                       class="btn btn-success radius" style="text-decoration:none">
                        <i class="Hui-iconfont Hui-iconfont"></i><?php echo ($statusTips); ?>
                    </a>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>

    <div id="page" class="r"></div>
    <div id="div_pagesize" class="r">
        <span>每页显示：<input type="text" class="radius" size="1" value="<?php echo ($pagesize); ?>" id="pagesize"/>&nbsp;条<a href="javascript:void(0);" style="border: 1px solid #ddd;padding:1px 4px 4px 4px;margin: 0 3px 6px;" id="page_go">Go</a></span>
    </div>
</div>
<script type="text/javascript" src="/Public/H-ui/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/icheck/jquery.icheck.min.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/jquery.validation/1.14.0/messages_zh.min.js"></script>
<script type="text/javascript" src="/Public/H-ui/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/Public/H-ui/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/jquery.form/jquery.form.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/webuploader/0.1.5/webuploader.min.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" src="/Public/H-ui/lib/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" src="/Public/H-ui/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script>
    var title = "<?php echo ($title); ?>";
    var category_id = "<?php echo ($categoryId); ?>";
    var is_delete = "<?php echo ($isDelete); ?>";
    var is_publish = "<?php echo ($isPublish); ?>";

    var pages = "<?php echo ($pages); ?>";
    if(parseInt(pages) > 1)
    {
        $("#div_pagesize").show();
        laypage({
            cont: 'page', //容器。值支持id名、原生dom对象，jquery对象。【如该容器为】：<div id="page1"></div>
            pages: <?php echo ($pages); ?>, //通过后台拿到的总页数
            curr: <?php echo ($page); ?>, //初始化当前页

            jump: function(e, first){ //触发分页后的回调
                if(!first) { //一定要加此判断，否则初始时会无限刷新
                    var pagesize = $("#pagesize").val();
                    var url = "<?php echo U('Admin/Ad/image_list/p/"+e.curr+"/pagesize/"+pagesize+"/_param_/title/"+title+"/category_id/"+category_id+"/is_delete/"+is_delete+"/is_publish/"+is_publish+"');?>";
                    var param = '<?php echo ($param); ?>';
                    var new_url = url.replace('/_param_', param);
                    window.location.href = new_url;
                }
            }
        });
    }
    $('#page_go').click(function () {
        var page_size = $('#pagesize').val();
        var url = "<?php echo U('Admin/Ad/image_list/p/1/pagesize/"+page_size+"/_param_/title/"+title+"/category_id/"+category_id+"/is_delete/"+is_delete+"/is_publish/"+is_publish+"');?>";
        var param = '<?php echo ($param); ?>';
        var new_url = url.replace('/_param_', param);
        window.location.href = new_url;
    });

    /*编辑按钮点击弹框*/
    function image_edit(title, url, w, h) {
        layer_show(title, url, w, h);
    }
    /*添加按钮弹框*/
    function image_add(title, url, w, h) {
        layer_show(title, url, w, h);
    }

    //删除或启用分类
    function status_change(id,message) {
        layer.confirm(message, function (index) {
            //此处请求后台程序，下方是成功后的前台处理……
            $.post("<?php echo U('Admin/Ad/image_status_change');?>", {id:id}, function (result) {
                if (!result.error) {
                    layer.alert(result.detail, {
                        skin: 'layui-layer-lan',
                        closeBtn: 0,
                        anim: 4, //动画类型
                        title: '提示',
                    }, function (index, layero) {
                        //按钮【按钮一】的回调
                        window.location.reload();
                    });
                }
                else {
                    layer.alert(result.detail, {
                        skin: 'layui-layer-lan',
                        closeBtn: 0,
                        anim: 4, //动画类型
                        title: '提示',
                    }, function (index, layero) {
                        //按钮【按钮一】的回调
                        window.location.reload();
                    });
                }
            });
        });
    }

    //删除或启用分类
    function publish_change(id,message) {
        layer.confirm(message, function (index) {
            //此处请求后台程序，下方是成功后的前台处理……
            $.post("<?php echo U('Admin/Ad/publish_status_change');?>", {id:id}, function (result) {
                if (!result.error) {
                    layer.alert(result.detail, {
                        skin: 'layui-layer-lan',
                        closeBtn: 0,
                        anim: 4, //动画类型
                        title: '提示',
                    }, function (index, layero) {
                        //按钮【按钮一】的回调
                        window.location.reload();
                    });
                }
                else {
                    layer.alert(result.detail, {
                        skin: 'layui-layer-lan',
                        closeBtn: 0,
                        anim: 4, //动画类型
                        title: '提示',
                    }, function (index, layero) {
                        //按钮【按钮一】的回调
                        window.location.reload();
                    });
                }
            });
        });
    }
    /**
     * 发布当前分类下的所有图片
     */
    function publish_all() {
        layer.confirm('确定要发布当前分类下的所有图片吗？', function (index) {
            //此处请求后台程序，下方是成功后的前台处理……
            $.post("<?php echo U('Admin/Ad/publish_all');?>", {category_id:category_id}, function (result) {
                if (!result.error) {
                    layer.alert(result.detail, {
                        skin: 'layui-layer-lan',
                        closeBtn: 0,
                        anim: 4, //动画类型
                        title: '提示',
                    }, function (index, layero) {
                        //按钮【按钮一】的回调
                        window.location.reload();
                    });
                }
                else {
                    layer.alert(result.detail, {
                        skin: 'layui-layer-lan',
                        closeBtn: 0,
                        anim: 4, //动画类型
                        title: '提示',
                    }, function (index, layero) {
                        //按钮【按钮一】的回调
                        window.location.reload();
                    });
                }
            });
        });
    }
    //搜索表单重置按钮
    $("#reset").click(function(){
        $("input[name=title]").val('');
        $("select[name=category_id]").val(category_id);
        $("select[name=is_delete]").val(0);
        $("select[name=is_publish]").val(0);
    });

    $(function() {

    });
</script>
</body>
</html>