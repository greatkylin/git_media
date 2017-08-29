<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
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
<title>媒体站管理中心</title>
<meta name="keywords" content="<?php echo ($keywords); ?>">
<meta name="description" content="<?php echo ($description); ?>">
	<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="<?php echo U('Admin/Index/nindex');?>">后台管理中心</a> <a class="logo navbar-logo f-l mr-10 hidden-xs" style="font-size: 12px;" target="_blank" href="<?php echo U('Home/Index/index');?>">【网站首页】</a>
			<nav class="nav navbar-nav">
				<ul class="cl">
					<?php if(is_array($topMenu)): $i = 0; $__LIST__ = $topMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t_menu): $mod = ($i % 2 );++$i;?><li class="navbar-levelone <?php if($type == $t_menu[type]): ?>current<?php endif; ?>"><a href="<?php echo U('Admin/Index/nindex/type/'.$t_menu[type]);?>"><?php echo ($t_menu["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</nav>
			<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
				<ul class="cl">
					<li><?php $user_info = session('user_info'); echo $user_info['nickname'];?></li>
					<li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A"><?php $user_info = session('user_info'); echo $user_info['username'];?> <i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;"
								   onclick="layer_show('网站信息编辑', '<?php echo U('Admin/SysConfig/site_config');?>', 550, 500)">网站信息</a></li>
							<li><a href="javascript:;" onclick="layer_show('系统配置', '<?php echo U('Admin/SysConfig/config_list');?>', 900, 600)">系统配置</a></li>
							<li><a href="<?php echo ($login_url); ?>">退出</a></li>
						</ul>
					</li>
					<li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
							<li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
							<li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
							<li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
							<li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
							<li><a href="javascript:;" data-val="orange" title="绿色">橙色</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header>
</head>
<body>
<style>
	.span-p {margin-left: 10px;font-weight: bold;color: #666;}
	.Hui-aside .menu_dropdown li:hover{background-color: #fafafa; color: #148cf1;cursor:pointer;}
	.span-p.on {margin-left: 10px; color: #148cf1}
	.child_menu {display: none;}
	.child_menu.show{display: block;}

</style>
<aside class="Hui-aside">
	<input runat="server" id="divScrollValue" type="hidden" value="" />
	<?php if($type == 3): ?><div id="search-game-box" class="text-c" style="position: relative;height: 30px;">
			<div class="search-box" style="position: fixed;left: 2px;z-index: 1">
				<input type="text" name="app-name" id="app-name" placeholder=" 游戏名称" style="width:130px" class="input-text">
				<button name="search-game" id="search-game" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i></button>
			</div>
		</div><?php endif; ?>
	<div class="menu_dropdown bk_2" id="menu-container">
		<?php if(is_array($leftMenu)): $i = 0; $__LIST__ = $leftMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl id="menu-<?php echo ($vo["id"]); ?>">
				<dt>
					<?php if(!empty($vo['css'])): ?><i class="Hui-iconfont <?php echo ($vo["css"]); ?>"></i>
						<?php else: ?>
						<i class="Hui-iconfont Hui-iconfont-system"></i><?php endif; ?>
					<?php if($vo['has_child'] == 1): echo ($vo["title"]); ?><i class="Hui-iconfont menu_dropdown-arrow Hui-iconfont-arrow2-bottom"></i>
						<?php else: ?>
						<a style="font-weight: bold;"
						<?php echo "data-href='".$vo['name']."'" ?>
						data-title="<?php echo ($vo["title"]); ?>" >&nbsp;&nbsp;<?php echo ($vo["title"]); ?></a><?php endif; ?>

				</dt>
				<dd>
					<?php if($vo['has_child'] == 1): ?><ul>
							<?php if(is_array($vo['menu_child'])): $i = 0; $__LIST__ = $vo['menu_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$mc_vo): $mod = ($i % 2 );++$i;?><li>
									<?php if($mc_vo['has_child'] == 1): ?><span class="span-p" data-title="<?php echo ($mc_vo["title"]); ?>" href="javascript:void(0);"><i class="Hui-iconfont Hui-iconfont-add"></i>&nbsp;&nbsp;<?php echo ($mc_vo["title"]); ?></span>
									<?php else: ?>
										<a style="font-weight: bold;"
										<?php echo "data-href='".U($mc_vo['name'])."'" ?>
										data-title="<?php echo ($mc_vo["title"]); ?>" >&nbsp;&nbsp;<?php echo ($mc_vo["title"]); ?></a><?php endif; ?>
									<?php if($mc_vo['has_child'] == 1): ?><ul class="child_menu">
										<?php if(is_array($mc_vo['menu_child'])): $i = 0; $__LIST__ = $mc_vo['menu_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$mcc_vo): $mod = ($i % 2 );++$i;?><li><a
												<?php echo "data-href='".U($mcc_vo['name'])."'" ?>
												data-title="<?php echo ($mcc_vo["title"]); ?>" href="javascript:void(0);"><i class="Hui-iconfont Hui-iconfont-arrow2-right"></i>&nbsp;&nbsp;<?php echo ($mcc_vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
										</ul><?php endif; ?>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul><?php endif; ?>
				</dd>
			</dl><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
</aside>
<script>
	$('.span-p').click(function () {
		var _this = $(this);
		var _parent = $(this).parent();
		if(_this.hasClass('on')) {
			_this.removeClass('on');
			_this.next('.child_menu.show').removeClass('show');
			_this.find('i.Hui-iconfont-jianhao').removeClass('Hui-iconfont-jianhao').addClass('Hui-iconfont-add');
		}
		else {
			_parent.siblings().find('span.span-p').removeClass('on');
			_this.addClass('on');
			_parent.siblings().find('span.span-p').find('i.Hui-iconfont-jianhao').removeClass('Hui-iconfont-jianhao').addClass('Hui-iconfont-add');
			_parent.siblings().find('span.span-p').next('.child_menu.show').removeClass('show');
			_this.next('.child_menu').addClass('show');
			_this.find('i.Hui-iconfont-add').removeClass('Hui-iconfont-add').addClass('Hui-iconfont-jianhao');
		}
	});



    $(function(){
        /**
         * 默认显示第一个菜单链接
         */
        openTheUrl('.menu_dropdown');

        function openTheUrl(className){
            var obj = $(className).find('a').first();
            var href = obj.data('href');
            var title = obj.data('title');
            $('#min_title_list').find('li').first().children('span').first().empty().html(title);
            $('#iframe_box .show_iframe').find('iframe').first().attr('src', href);
        }

        /**
		 * 执行ajax获取游戏专题菜单
         */
		$('#search-game').click(function(){
		    var inputVal = $.trim($('#app-name').val());

            $.ajax({
                url: '<?php echo U("Admin/Index/ajax_get_article_column");?>',
                type: 'POST',
                data: {app_name : inputVal},
                success: function(json) {
                    if (!json.error) {
						$('#menu-container').empty().html(json.data.html);
                    } else {
                        layer.msg(json.detail, {
                            icon: 2
                        });
                    }
                }
            });
		});
	});
</script>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
	<div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
		<div class="Hui-tabNav-wp">
			<ul id="min_title_list" class="acrossTab cl">
				<li class="active"><span title="我的桌面" data-href="welcome.html">我的桌面</span><em></em></li>
			</ul>
		</div>
		<div class="Hui-tabNav-more btn-group">
			<a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a>
			<a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a>
		</div>
	</div>
	<div id="iframe_box" class="Hui-article">
		<div class="show_iframe">
			<div style="display:none" class="loading"></div>
			<iframe scrolling="yes" frameborder="0" src="<?php echo U('Index/welcome');?>"></iframe>
		</div>
	</div>
</section>

<div class="contextMenu" id="myMenu1">
	<ul>
		<li id="open">Open </li>
		<li id="email">email </li>
		<li id="save">save </li>
		<li id="delete">delete </li>
	</ul>
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
<script type="text/javascript" src="/Public/H-ui/lib/jquery.contextmenu/jquery.contextmenu.r2.js"></script>
<script type="text/javascript">
$(function(){
//    menuTabChange(
//        ".navbar-wrapper .navbar-levelone",
//        ".Hui-aside .menu_dropdown",
//        "current",
//		'click',
//        0
//    );
    //菜单切换
    function menuTabChange (tabBar,tabCon,class_name,tabEvent,i){
        var $tab_menu=$(tabBar);
        // 初始化操作
        $tab_menu.removeClass(class_name);
        $(tabBar).eq(i).addClass(class_name);
        $(tabCon).hide();
        $(tabCon).eq(i).show();

        $tab_menu.on(tabEvent,function(){
            $tab_menu.removeClass(class_name);
            $(this).addClass(class_name);
            var index=$tab_menu.index(this);
            $(tabCon).hide();
            $(tabCon).eq(index).show();
        });
    }
});
</script>

</body>
</html>