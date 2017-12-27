$(document).ready(function () {
    $('#quit').click(function(){
        $.cookie('usephone', '', { expires: -1, path: '/' })
        $.cookie('secret', '', { expires: -1, path: '/'})
        location.reload();
    });

    $('#submit').click(function(){
        if ($('#advice').val().length == 0) {
            $.helper.alert('意见内容不能为空');
            return ;
        }

        $.ajax({
            url: '/customer/advice',
            type: 'post',
            data: "advice=" + $('#advice').val(),
            dataType: 'html',
            success: function (data) {
                if (data == 'ok') {
                    $.helper.alert('感谢您提供的宝贵意见');
                } else {
                    $.helper.alert(data);
                }
            }
        });
    });
});