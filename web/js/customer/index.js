$(document).ready(function () {
    $('#quit').click(function(){
        $.cookie('secret', '', {expires: -1, path: '/'});
        $.cookie('cid', '', {expires: -1, path: '/'})
        location.reload();
    });

    $('#top_right_link').html('首页');
    $('#top_right_link').attr('href', '/');

    $('#feedback').click(function(){
        if ($('#advice').val() == '') {
            $.helper.alert('意见内容不能为空');
            return ;
        }

        $.ajax({
            url: '/customer/advice',
            type: 'post',
            data: "advice=" + $('#advice').val(),
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    $.helper.alert('感谢您提供的宝贵意见');
                } else {
                    $.helper.alert(data.msg);
                }
            }
        });
    });
});