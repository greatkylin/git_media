/**
 * Created by Administrator on 2017/10/17.
 */


/**
 * 按钮的倒计时
 * @param elem html 元素对象
 * @param time 倒计时时间
 * @param normalColor 对象正常的颜色 如'#00E3CB'
 */
var setTime;
function sendCaptchaSetCountDown(elem, time, nomalColor) {
    var tarName = elem[0].tagName.toLowerCase();
    console.log(tarName);
    time = parseInt(time);
    timeCount = time;
    setTime = setInterval(function(){
        if(time<=0){
            clearInterval(setTime);
            elem.css('background-color', nomalColor);
            elem.val('获取验证码');
            return;
        }
        time--;
        var text = '剩余'+time+'秒';
        if(tarName === 'input' || tarName === 'select'){
            elem.val(text);
        }else{
            elem.empty().html(text);
        }
    },1000);
}

$(function() {
    //登录后 网页头部的点击弹出用户信息
    var hdlt = 0;
    $(".headdownlart").click(function() {
        hdlt++;
        if (hdlt % 2) {
            $(".headdownlart").attr("src", "/Public/Home/static/gulp/img/Downarrow1.png")
            $(".header-up-login-div").css("background-color", "white");
            $(".colorsseta").css("color", "red");
            $(".headloginalert").show();
            $(".headpcenter").attr("src", "/Public/Home/static/img/pcenter1.png");
        } else {
            $(".headdownlart").attr("src", "/Public/Home/static/gulp/img/Downarrow2.png")
            $(".header-up-login-div").css("background-color", "");
            $(".colorsseta").css("color", "white");
            $(".headloginalert").hide();
            $(".headpcenter").attr("src", "/Public/Home/static/img/pcenter.png");
        }
    });
    //头部用户登录信息下拉框 我的礼包按钮 我的优惠券按钮
    $(".pc-headbtnwallet , .pc-headbtncoupon").click(function() {
        var href = $(this).data('href');
        window.location.href = href
    });
});