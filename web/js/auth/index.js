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
            url: '/auth/login',
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
                    location.href = '/';
                }
            }
        });
    }


    $("#submit_login_form").click(function() {
        submit_form();
    });

    $(document).keyup(function(e){
        var curKey = e.which; 
        if(curKey == 13){
            submit_form();
        }
    });
});
