$(document).ready(function () {
    $('#prepare').click(function() {
        var token = $(this).attr('data-token');
        var id    = $(this).attr('data-id');

        $.ajax({
            url: '/order/prepare',
            type: 'post',
            data: {
                token: token,
                id: id
            },
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    $.helper.alert('发送通知成功');
                } else {
                    $.helper.alert(data.msg);
                }
            }
        });
    });
});