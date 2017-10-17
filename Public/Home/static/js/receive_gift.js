/**
 * Created by Administrator on 2017/10/17.
 */
//领取礼包
function receive_gift(gift_id) {
    var url = '/Home/User/receive_gift';
    $.ajax({
        type:'POST',
        url:url,
        data: {gift_id:gift_id},
        dataType: 'json',
        async : false, //默认为true 异步
        success:function(res){
            var gift_code;
            var gift_tips;
            var copy_code_html;
            if($('.get-gift-mask').length > 0){
                $('.get-gift-mask').remove();
            }
            if(res.error){
                gift_code = '';
                gift_tips = res.detail;
                copy_code_html = '';
            }else{
                if(res.data.code == 1000 ){
                    gift_code = res.data.gift_code;
                    gift_tips = '领取成功，可在我的礼包中查看';
                    copy_code_html = '<span class="copy copy-gift" data-clipboard-target="">复制</span></div>';
                }else{
                    gift_code = '';
                    gift_tips = res.detail;
                    copy_code_html = '';
                }
            }
            var html =
                '<div id="get-gift-dialog">'+
                '    <div class="system-alert get-gift-mask" style="display: none">' +
                '        <div class="container">' +
                '            <div class="hd">' +
                '                <span>系统提示</span><img class="close" alt="" src="/Public/Home/static/img/close-mask.png">' +
                '            </div>' +
                '            <div class="main-content">' +
                '                <div><img alt="" src="/Public/Home/static/img/success.png"></div>' +
                '                <div>' +
                '                    <div class="text">' +
                '                        <span>'+gift_code+'</span>' +
                '                        '+ copy_code_html +
                '                    <div>' +gift_tips +'</div>' +
                '                </div>' +
                '            </div>' +
                '            <!--<div class="use-now"><img alt="" src="__PUBLIC__/Home/static/img/dengpao.png">您领取的礼包将60分钟后进入淘号，请您尽快激活使用</div>-->' +
                '        </div>' +
                '    </div>'+
                '</div>';
            $('body').append(html);
            $(".get-gift-mask").show();
            $(".taohao-mask").hide();
            $(".close").click(function () {
                $(".get-gift-mask").remove();
            });
        },
        error : function() {
        }
    });
}

