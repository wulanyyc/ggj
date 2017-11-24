/**
 * @file 登录
 */

$(document).ready(function () {
    function submit_form() {
        var username = $("#username").val();
        var password = $("#password").val();

        if (username.length == 0 || password.length == 0) {
            $(".alert").html("用户名或密码不能为空");
            $(".alert").removeClass("hide");
        }

        $.ajax({
            url: '/admin/index',
            type: 'POST',
            dataType: 'json',
            data: {
                username: username,
                password: password
            },
            success: function (data) {
                if (data['status'] != 'suc') {
                    $(".alert").html(data['msg']);
                    $(".alert").removeClass("hide");
                } else {
                    location.href = '/product/admin/index';
                }
            }
        });
    }

    function submit_reset_form() {
        var pw1 = $("#password").val();
        var pw2 = $("#password2").val();

        if (pw1.length == 0 || pw2.length == 0) {
            $(".alert").html("密码不能为空");
            $(".alert").removeClass("hide");
        }

        $.ajax({
            url: '/admin/reset',
            type: 'POST',
            dataType: 'json',
            data: {
                password: pw1,
                password2: pw2
            },
            success: function (data) {
                if (data['status'] != 'suc') {
                    $(".alert").html(data['msg']);
                    $(".alert").removeClass("hide");
                } else {
                    location.href = '/';
                }
            }
        });
    }


    $("#submit_login_form").click(function() {
        submit_form();
    });

    $("#submit_reset_form").click(function() {
        submit_reset_form();
    });

    $(document).keyup(function(e){
        var curKey = e.which; 
        if(curKey == 13){
            if ($('#submit_login_form').length > 0) {
                submit_form();
            }

            if ($('#submit_reset_form').length > 0) {
                submit_reset_form();
            }
        }
    });
});
