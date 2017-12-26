$(document).ready(function () {
    timeLimit = 60;
    clock = 0;

    $('#getcode').click(function() {
        if ($.helper.validatePhone($('#userphone').val())) {
            $.ajax({
                url: '/sms/getcode',
                type: 'post',
                data: "phone=" + $('#userphone').val(),
                dataType: 'html',
                success: function (data) {
                    console.log(data);
                    if (parseInt(data) > 0) {
                        clock = setInterval(function(){
                            timeLimit--;
                            $('#getcode').html(timeLimit + 's');
                            if (timeLimit == 0) {
                                clearInterval(clock);
                                $('#getcode').val('');
                                $('#getcode').html('发送验证码');
                                $('#getcode').removeAttr('disabled');
                            }
                        }, 1000);

                        $('#getcode').attr('disabled', 'disabled');
                    } else {
                        bootbox.alert(data);
                    }
                }
            });
        } else {
            bootbox.alert('手机号码格式有误');
        }
    });

    $('#submit').click(function(){
        clearInterval(clock);
        $.ajax({
            url: '/sms/vcode',
            type: 'post',
            data: "phone=" + $('#userphone').val() + "&code=" + $('#code').val(),
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    $.cookie('userphone', $('#userphone').val(), { path: '/' });
                    $.cookie('secret', data.secret, { path: '/' });
                    $('#login').hide();
                    $('#getcode').val('');
                    $('#getcode').html('发送验证码');
                    $('#getcode').removeAttr('disabled');

                    location.reload();
                } else {
                    bootbox.alert(data.msg);
                }
            }
        });
    });
});