/**
 * Created by Administrator on 2017/9/27.
 */
$(function () {
    //注册的密码与用户名是否验证通过，默认否
    var isValidPass = false;
    var isPwdPass = false;
    //注册弹窗容器
    var registerBox = $('#register-box');
    //显示注册框
    $('#header-sign').click(function () {
        registerBox.show();
        $('#register-box #register-form')[0].reset();
    });

    //点击注册弹框的关闭按钮则隐藏表单并清空表单
    $('#register-box .module-off').click(function(){
        registerBox.hide();
        $('#register-box #register-form')[0].reset();
    });

    //输入用户名时验证
    $("#register-box #input-account").change(function () {
        $(this).blur(function () {
            var parentObj = $(this).parent();
            var userName = $(this).val();
            ajaxCheckUserName(userName, parentObj);
        });
    });

    //输入密码时验证
    $("#register-box #input-pwd").change(function () {
        $(this).blur(function () {
            var parentObj = $(this).parent();
            var password = $(this).val();
            $.ajax({
                type: 'POST',
                url: "/Home/Login/ajax_check_password" ,
                data: {password : password} ,
                dataType:'json',
                async : false, //默认为true 异步
                success:function(res) {
                    if(res.error){
                        showNo(parentObj);
                        isPwdPass = false;
                    }else{
                        showYes(parentObj);
                    }
                },
                error : function() {
                    showNo(parentObj);
                    isPwdPass = false;
                }
            });
        });
    });

    //验证重复密码
    $("#register-box #input-pwd2").change(function () {
        $(this).blur(function () {
            var parentObj = $(this).parent();
            var password = $("#register-box #input-pwd").val();
            var password2 = $(this).val();

            $.ajax({
                type: 'POST',
                url: "/Home/Login/ajax_check_password" ,
                data: {password : password} ,
                dataType:'json',
                async : false, //默认为true 异步
                success:function(res) {
                    if(res.error){
                        showNo(parentObj);
                        isPwdPass = false;
                    }else{
                        if(password === password2){
                            showYes(parentObj);
                            isPwdPass = true;
                        }else{
                            showNo(parentObj);
                            isPwdPass = false;
                        }
                    }
                },
                error : function() {
                    showNo(parentObj);
                    isPwdPass = false;
                }
            });
        });
    });

    //勾选注册协议
    $('#register-box .ok-rules-img').click(function () {
        var isAgree = $(this).siblings('.ok-rules-img').data('val');
        $('#register-box #is_agree').val(isAgree);
        $(this).hide().siblings('#register-box .ok-rules-img').show();
    });

    //点击注册
    $('#register-btn').click(function(){
        if(!(isPwdPass && isValidPass)) {
            return false;
        }
        var isAgree = $('#register-box #is_agree').val();
        if(isAgree == 0){
            $('#register-box .agree-yes').hide().siblings('#register-box .agree-no').show();
            return false
        }
        $("#register-box #register-form").ajaxSubmit({
            type: 'POST',
            url: "/Home/Login/do_register" ,
            data: {} ,
            dataType:'json',
            async : false, //默认为true 异步
            success:function(res) {
                if(res.error){
                    if(res.code == '100001'){
                        var nameObj = $('#register-box #input-account').parent();
                        showNo(nameObj);
                    } else if(res.code == '100002'){
                        var pwdObj_1 = $('#register-box #input-pwd').parent();
                        var pwdObj_2 = $('#register-box #input-pwd2').parent();
                        showNo(pwdObj_1);
                        showNo(pwdObj_2);
                    }else if(res.code == '100003'){
                        $('#register-box .agree-yes').hide().siblings('#register-box .agree-no').show();
                    }
                    return false;
                }else{
                    window.location.reload();
                }
            },
            error : function() {
                return false;
            }
        });
    });

    //ajax方式验证用户名
    function ajaxCheckUserName(userName, elemObj) {
        var returnVal = false;
        $.ajax({
            type: 'POST',
            url: "/Home/Login/ajax_check_reg_user_name" ,
            data: {user_name : userName} ,
            dataType:'json',
            async : false, //默认为true 异步
            success:function(res) {
                if(res.error){
                    showNo(elemObj);
                    isValidPass = false;
                    returnVal = false;
                }else{
                    showYes(elemObj);
                    isValidPass = true;
                    returnVal = true;
                }
            },
            error : function() {
                showNo(elemObj);
                isValidPass = false;
                returnVal = false;
            }
        });
    }

    function showYes(elemObj) {
        elemObj.find(".no").hide();
        elemObj.find(".yes").show();
    }

    function showNo(elemObj) {
        elemObj.find(".no").show();
        elemObj.find(".yes").hide();
    }


    $("#input-account,#input-pwd,#input-pwd2").focus(function(){
        $(this).parent().find(".no,.yes").hide();
    });
});