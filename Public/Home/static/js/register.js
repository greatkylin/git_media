/**
 * Created by Administrator on 2017/9/27.
 */
$(function () {
    //弹出的层
    var popBox = $('.pop-box');
    //弹出层的关闭按钮
    var popBoxCloseBtn = $('.pop-box .module-off, .pop-box .alt-close');
    popBoxCloseBtn.click(function () {
        popBox.hide();
        window.location.reload();
    });

    //注册的密码与用户名是否验证通过，默认否
    var isValidPass = false;
    var isPwdPass = false;

    //注册弹窗容器
    var registerBox = $('#register-box');
    //显示注册框
    $('#header-sign').click(function () {
        popBox.hide();
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

    /************************************登录弹框*************************************/
    var isRememberPwd = 1;
    var loginBox = $('#login-box');
    var loginNameInput = $('#login_name');
    var loginPwdInput = $('#login_password');
    var loginNameStatus = $('#login-box .login_name_status');
    var passwordStatus = $('#login-box .password_status');
    //点击头部的登录 弹出弹框并重置
    $('#header-login').click(function () {
        popBox.hide();
        loginBox.show();
        loginNameInput.val('');
        loginPwdInput.val('');
        if($(this).hasClass('remember_pwd_no')){
            isRememberPwd = 1;
            $(this).removeClass('remember_pwd_no');
            $(this).css('background-image', 'url("/Public/Home/static/img/alerticon.png")');
        }
    });

    //关闭登录弹框
    $('#login-box .alt-close').click(function () {
        loginBox.hide();
    });

    /*勾选记住密码*/
    $('#login-box .remember_pwd_status').click(function () {
        if($(this).hasClass('remember_pwd_no')){
            isRememberPwd = 1;
            $(this).removeClass('remember_pwd_no');
            $(this).css('background-image', 'url("/Public/Home/static/img/alerticon.png")');
        }else{
            isRememberPwd = 0;
            $(this).addClass('remember_pwd_no');
            $(this).css('background-image', 'url("/Public/Home/static/img/gth.png")');
        }
    });

    //点击登录按钮
    $('#login_submit_btn').click(function(){
        var login_name = $.trim(loginNameInput.val());
        var password = $.trim(loginPwdInput.val());

        if(login_name == ''){
            InputPassStatus(loginNameStatus, false);
            return false;
        }else{
            InputPassStatus(loginNameStatus, true);
        }
        if(password == ''){
            InputPassStatus(passwordStatus, false);
            return false;
        }else{
            InputPassStatus(passwordStatus, true);
        }
        $.ajax({
            type: 'POST',
            url: "/Home/Login/do_login" ,
            data: {login_name : login_name, password: password, is_remember: isRememberPwd} ,
            dataType:'json',
            async : false, //默认为true 异步
            success:function(res) {
                if(res.error){
                    loginNameInput.focus();
                    InputPassStatus(loginNameStatus,false)
                }else{
                    window.location.reload();
                }
            },
            error : function() {

            }
        });
    });

    //点击立即注册
    $('#login-box .register_now').click(function(){
        popBox.hide();
        registerBox.show();
        $('#register-box #register-form')[0].reset();
    });

    //点击忘记密码
    $('#login-box .forget_password').click(function(){
        popBox.hide();
        //重置密码弹框显示
        $('#reset-pwd-box').show();
    });

    loginPwdInput.change(function () {
        $(this).blur(function () {
            var login_pwd = $.trim(loginPwdInput.val());
            if(typeof (login_pwd) !== 'undefined'){
                InputPassStatus(passwordStatus, true);
            }else{
                InputPassStatus(passwordStatus);
            }
        });
    });

    loginNameInput.change(function () {
        $(this).blur(function () {
            var login_name = $.trim(loginNameInput.val());
            if(typeof (login_name) !== 'undefined'){
                InputPassStatus(loginNameStatus, true);
            }else{
                InputPassStatus(loginNameStatus);
            }
        });
    });

    //登录的表单验证状态
    function InputPassStatus(elem,isPass) {
        if(isPass === true){
            if(elem.hasClass('altb-icon')){
                elem.removeClass('altb-icon');
            }
            if(!elem.hasClass('altb-icon2')){
                elem.addClass('altb-icon2');
            }
        }else if(isPass === false){
            if(elem.hasClass('altb-icon2')){
                elem.removeClass('altb-icon2');
            }
            if(!elem.hasClass('altb-icon')){
                elem.addClass('altb-icon');
            }
        }else{
            if(elem.hasClass('altb-icon')){
                elem.removeClass('altb-icon');
            }
            if(elem.hasClass('altb-icon2')){
                elem.removeClass('altb-icon2');
            }
        }
    }

    /*******************************************重置密码弹框js*****************************************/
    var resetPwdBox = $('#reset-pwd-box');
    var phoneInput = $('#reset-pwd-box .phone');
    var passwordInput = $('#reset-pwd-box .new_password');
    var captchaInput = $('#reset-pwd-box .captcha');
    var sendCaptchaBtn = $('#reset-pwd-box .send_captcha');
    var phoneStatus = $('#reset-pwd-box .phone_status');
    var npasswordStatus = $('#reset-pwd-box .password_status');
    var captchaStatus = $('#reset-pwd-box .captcha_status');
    var resetPwdSubmitBtn = $('#reset_pwd_btn');
    var resetPwdPass = false;
    var resetPhonePass = false;
    //关闭重置密码弹框
    $('#reset-pwd-box .alt-close').click(function () {
        resetPwdBox.hide();
    });
    //输入手机号触发
    phoneInput.change(function () {
        $(this).blur(function () {
            var phone = $.trim(phoneInput.val());
            if(typeof (phone) === 'undefined' || phone === ''){
                InputPassStatus(phoneStatus, false);
                phoneInput.focus();
                resetPhonePass = false;
                return false;
            }
            var pattern = /^1[34578]\d{9}$/;
            if(!pattern.test(phone)){
                phoneInput.focus();
                InputPassStatus(phoneStatus, false);
                resetPhonePass = false;
                return false;
            }
            resetPhonePass = true;
            InputPassStatus(phoneStatus, true);
        });
    });

    //点击发送验证码
    sendCaptchaBtn.click(function(){
        var phone = $.trim(phoneInput.val());
        var sendCodeUrl = '/Home/Login/ajax_send_sms_captcha.html';
        if(typeof (phone) === 'undefined' || phone === ''){
            phoneInput.focus();
            resetPhonePass = false;
            return false;
        }
        var pattern = /^1[34578]\d{9}$/;
        if(!pattern.test(phone)){
            phoneInput.focus();
            resetPhonePass = false;
            return false;
        }
        InputPassStatus(phoneStatus, true);
        $.ajax({
            type: 'POST',
            url: sendCodeUrl ,
            dataType:'json',
            data:{phone: phone, send_type: 0},
            async : false, //默认为true 异步
            success:function(res) {
                if(res.error){
                    phoneInput.focus();
                    InputPassStatus(phoneStatus, false);
                    resetPhonePass = false;
                    return false;
                }else{
                    InputPassStatus(phoneStatus, true);
                    //按钮倒计时
                    sendCaptchaBtn.css('background-color', '#CBCBCB');
                    sendCaptchaSetCountDown(sendCaptchaBtn, 60, '#FEBD58');
                    resetPhonePass = true;
                }
            },
            error : function() {
                resetPhonePass = false;
            }
        });
    });

    //密码表单验证
    passwordInput.change(function () {
        $(this).blur(function () {
            var password = $(this).val();
            $.ajax({
                type: 'POST',
                url: "/Home/Login/ajax_check_password" ,
                data: {password : password} ,
                dataType:'json',
                async : false, //默认为true 异步
                success:function(res) {
                    if(res.error){
                        InputPassStatus(npasswordStatus, false);
                        resetPwdPass = false;
                        return false;
                    }else{
                        InputPassStatus(npasswordStatus, true);
                        resetPwdPass = true;
                    }
                },
                error : function() {
                    resetPwdPass = false;
                }
            });
        });
    });

    //点击重置密码提交按钮
    resetPwdSubmitBtn.click(function () {
        var captchaPass = true;
        var phone = phoneInput.val();
        var captcha = captchaInput.val();
        var new_password = passwordInput.val();
        //提交前再验证手机号码是否符合要求
        if(typeof (phone) === 'undefined' || phone === ''){
            InputPassStatus(phoneStatus, false);
            phoneInput.focus();
            resetPhonePass = false;
            return false;
        }else{
            resetPhonePass = true;
        }
        var pattern = /^1[34578]\d{9}$/;
        if(!pattern.test(phone)){
            InputPassStatus(phoneStatus, false);
            phoneInput.focus();
            resetPhonePass = false;
            return false;
        }else{
            InputPassStatus(phoneStatus, true);
            resetPhonePass = true;
        }
        if(typeof (captcha) === 'undefined' || captcha === ''){
            captchaInput.focus();
            captchaPass = false;
            return false;
        }else{
            captchaPass = true;
        }
        var pattern = /^\d{6}$/;
        if(!pattern.test(captcha)){
            captchaInput.focus();
            captchaPass = false;
            return false;
        }else{
            captchaPass = true;
        }
        if(typeof (new_password) === 'undefined' || new_password === ''){
            passwordInput.focus();
            resetPwdPass = false;
            return false;
        }else{
            resetPwdPass = true;
        }
        if(resetPhonePass == false || resetPwdPass == false || captchaPass == false){
            return false;
        }
        $.ajax({
            type: 'POST',
            url: "/Home/Login/reset_password" ,
            data: {phone: phone, password : new_password, captcha:captcha} ,
            dataType:'json',
            async : false, //默认为true 异步
            success:function(res) {
                if(res.error){
                    InputPassStatus(phoneStatus, false);
                    resetPwdPass = false;
                    return false;
                }else{
                    resetPwdBox.hide();
                    alertInfoBox('修改成功', '密码修改成功', '请牢记新密码');
                }
            },
            error : function() {

            }
        });
    });

    /***************************************绑定手机***************************************/
    var bindPhoneBox = $('#bind-phone-box');
    var bindPhoneInput = $('#bind-phone-box .bind_phone');
    var bindCaptchaInput = $('#bind-phone-box .bind_captcha');
    var bindSendCaptchaBtn = $('#bind-phone-box .send_captcha_btn');
    var bindPhoneStatus = $('#bind-phone-box .phone_status');
    var bindCaptchaStatus = $('#bind-phone-box .captcha_status');
    var submitBindPhoneBtn = $('#bind-phone-box .bind_phone_btn');
    var canceltBindPhoneBtn = $('#bind-phone-box .bind_phone_cancel_btn');
    var bindPhonePass = false;
    var bindCaptchaPass = false;

    bindPhoneInput.change(function () {
        $(this).blur(function () {
            var phone = $.trim(bindPhoneInput.val());
            if(typeof (phone) === 'undefined' || phone === ''){
                bindPhoneInput.focus();
                bindPhonePass = false;
                return false;
            }
            var pattern = /^1[34578]\d{9}$/;
            if(!pattern.test(phone)){
                phoneInput.focus();
                InputPassStatus(bindPhoneStatus, false);
                bindPhonePass = false;
                return false;
            }
            bindPhonePass = true;
            InputPassStatus(bindPhoneStatus, true);
        });
    });

    //绑定手机发送手机验证码
    bindSendCaptchaBtn.click(function(){
        var phone = $.trim(bindPhoneInput.val());
        var sendCodeUrl = '/Home/User/ajax_send_sms_captcha.html';
        if(typeof (phone) === 'undefined' || phone === ''){
            InputPassStatus(bindPhoneStatus, false);
            bindPhoneInput.focus();
            bindPhonePass = false;
            return false;
        }
        var pattern = /^1[34578]\d{9}$/;
        if(!pattern.test(phone)){
            InputPassStatus(bindPhoneStatus, false);
            bindPhoneInput.focus();
            bindPhonePass = false;
            return false;
        }
        bindPhonePass = true;
        InputPassStatus(bindPhoneStatus, true);
        $.ajax({
            type: 'POST',
            url: sendCodeUrl ,
            dataType:'json',
            data:{phone: phone, send_type: 6},
            async : false, //默认为true 异步
            success:function(res) {
                if(res.error){
                    bindPhoneInput.focus();
                    InputPassStatus(bindPhoneStatus, false);
                    bindPhonePass = false;
                    return false;
                }else{
                    InputPassStatus(bindPhoneStatus, true);
                    //发送成功后倒计时
                    sendCaptchaBtn.css('background-color', '#CBCBCB');
                    sendCaptchaSetCountDown(sendCaptchaBtn, 60, '#FEBD58');
                    bindPhonePass = true;
                }
            },
            error : function() {
                bindPhonePass = false;
            }
        });
    });
    //绑定手机表单提交
    submitBindPhoneBtn.click(function () {
        var phone = bindPhoneInput.val();
        var captcha = bindCaptchaInput.val();
        //提交前再验证手机号码是否符合要求
        if(typeof (phone) === 'undefined' || phone === ''){
            InputPassStatus(phoneStatus, false);
            bindPhoneInput.focus();
            bindPhonePass = false;
            return false;
        }else{
            InputPassStatus(phoneStatus, true);
            bindPhonePass = true;
        }
        var pattern = /^1[34578]\d{9}$/;
        if(!pattern.test(phone)){
            InputPassStatus(phoneStatus, false);
            bindPhoneInput.focus();
            bindPhonePass = false;
            return false;
        }else{
            InputPassStatus(phoneStatus, true);
            bindPhonePass = true;
        }
        if(typeof (captcha) === 'undefined' || captcha === ''){
            bindCaptchaInput.focus();
            bindCaptchaPass = false;
            return false;
        }else{
            bindCaptchaPass = true;
        }
        var pattern = /^\d{6}$/;
        if(!pattern.test(captcha)){
            bindCaptchaInput.focus();
            bindCaptchaPass = false;
            return false;
        }else{
            bindCaptchaPass = true;
        }
        if(bindPhonePass == false || bindCaptchaPass == false){
            return false;
        }
        $.ajax({
            type: 'POST',
            url: "/Home/User/bind_phone" ,
            data: {phone: phone, captcha:captcha} ,
            dataType:'json',
            async : false, //默认为true 异步
            success:function(res) {
                if(res.error){
                    InputPassStatus(bindPhoneStatus, false);
                    return false;
                }else{
                    bindPhoneBox.hide();
                    alertInfoAutoCloseBox('绑定成功', '手机绑定成功', 3);
                }
            },
            error : function() {

            }
        });
    });

    //取消绑定
    canceltBindPhoneBtn.click(function () {
        console.log(1);
        bindPhoneBox.hide();
    });

    //消息提示弹框
    function alertInfoBox(title, message, detail) {
        var box = $('#message-alert-box');
        if(box.length>0){
            box.remove();
        }
        var html = '<div class="alertbackimg" id="message-alert-box">' +
            '        <div class="alt-phoneset">' +
            '            <div class="alt-div">' +
            '                <span class="alt-title">' + title + '</span>' +
            '                <span class="alt-close">' +
            '                    <img src="/Public/Home/static/img/alertclose.png" alt="">' +
            '                </span>' +
            '                <div class="alerticon">' +
            '                    <img src="/Public/Home/static/img/alerticon.png" alt="">' +
            '                    <div>' +
            '                        <p>' + message + '</p>' +
            '                        <p>'+ detail +'</p>' +
            '                    </div>' +
            '                </div>' +
            '                <button class="alt-button">我知道了</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>';
        $('body').append(html);
        var box = $('#message-alert-box');
        $('#message-alert-box .alt-close, #message-alert-box .alt-button').click(function () {
            box.remove();
        });
    }

    //自动关闭消息弹框
    function alertInfoAutoCloseBox(title, message, wait) {
        var box = $('#message-alert-box');
        if(box.length>0){
            box.remove();
        }
        if(wait == 0 || wait == '' || wait == 'undefined'){
            wait = 3;
        }
        var waitMessage = '<p><span class="wait-second">'+ wait +'</span>s 后关闭此页面</p>';
        var html = '<div class="alertbackimg" id="message-alert-box">' +
            '        <div class="alt-phoneset">' +
            '            <div class="alt-div">' +
            '                <span class="alt-title">' + title + '</span>' +
            '                <span class="alt-close">' +
            '                    <img src="/Public/Home/static/img/alertclose.png" alt="">' +
            '                </span>' +
            '                <div class="alerticon">' +
            '                    <img src="/Public/Home/static/img/alerticon.png" alt="">' +
            '                    <div>' +
            '                        <p>' + message + '</p>' +
            '                        <p>'+ waitMessage +'</p>' +
            '                    </div>' +
            '                </div>' +
            '                <button class="alt-button">我知道了</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>';
        $('body').append(html);
        var box = $('#message-alert-box');
        $('#message-alert-box .alt-close, #message-alert-box .alt-button').click(function () {
            box.remove();
        });
        var interval = setInterval(function(){
            var time = --wait;
            $('#message-alert-box .wait-second').empty().html(time);
            if(time <= 0) {
                //window.location.reload();
                clearInterval(interval);
            }
        }, 1000);
    }


});