<!DOCTYPE html>
<html lang="en">
<head>
    <include file='Common:header'/>
</head>
<body>
<!--头部注册栏开始-->
<include file='Common:head_bar'/>
<!--头部注册栏结束-->
<!--菜单栏开始-->
<include file='Common:nav'/>
<!--菜单栏end-->
<!--主体内容开始-->
<style>
    .system-message {
        border: 1px solid #ddd;
        margin: 30px auto;
        padding: 30px;
        text-align: center;
        min-height:400px;
    }
    .system-message .info{
        margin-top: 100px;
        margin-bottom: 40px;
        word-wrap: break-word;
    }
    .system-message .info .success, .system-message .info .error{
        line-height: 60px;
        font-size: 50px;
    }
    .system-message .info .symbol{
        display: inline-block;
        margin-right: 40px;
    }
    .system-message .opt a{
        color: #00b7ee;
    }
</style>
<main>
    <div class="system-message" >
        <div class="info">
            <?php if(isset($message)) { ?>
            <p class="success"><span class="symbol">:)</span><?php echo($message); ?></p>
            <?php }else{ ?>
            <p class="error"><span class="symbol">:(</span><?php echo($error); ?></p>
            <?php } ?>
        </div>
        <div class="opt">
            <p class="detail"></p>
            <p class="jump">
                页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
            </p>
        </div>
</main>
<!--主体内容结束-->
<!--底部导航开始-->
<include file='Common:footer'/>
<!--底部导航结束-->
<script src="__PUBLIC__/Home/static/js/less.min.js"></script>
<script>
    $(function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })
</script>
</body>
</html>