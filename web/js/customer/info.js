$(document).ready(function () {
    timeLimit = 120;
    clock = 0;

    $('#edit_phone').click(function(){
        $('#edit_phone_card').show();
        $('#cover').show();
        $('body').addClass('forbid');
    });

    $('#close_phone').click(function(){
        $('#edit_phone_card').hide();
        $('#cover').hide();
        $('body').removeClass('forbid');
    });

    $('#getcode').click(function() {
        if ($.helper.validatePhone($('#new_userphone').val())) {
            $.ajax({
                url: '/sms/getcode',
                type: 'post',
                data: "phone=" + $('#new_userphone').val(),
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

    $('#next').click(function(){
        clearInterval(clock);
        $.ajax({
            url: '/sms/vcode',
            type: 'post',
            data: "phone=" + $('#new_userphone').val() + "&code=" + $('#code').val(),
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    $('#userphone').val($('#new_userphone').val());
                    $('#close_phone').click();
                } else {
                    bootbox.alert(data.msg);
                }
            }
        });
    });

    $('#submit').click(function(){
        $.ajax({
            url: '/customer/edit',
            type: 'post',
            data: "phone=" + $('#userphone').val(),
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    $.cookie('secret', data.secret, { path: '/', expires: 30 });
                    $.cookie('cid', data.cid, { path: '/', expires: 30 });
                    location.href="/customer";
                } else {
                    $.helper.alert(data.msg);
                }
            }
        });
    });
});