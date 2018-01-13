$(document).ready(function () {
    $('#suc, #fail').css('height', $(window).height() - 55);

    $('#refresh').click(function() {
        var id = $(this).attr('data-id');

        $.ajax({
            url: '/pay/refresh',
            type: 'post',
            dataType: 'json',
            data: 'id=' + id, 
            success: function (data) {
                if (data.status !== 'ok') {
                    bootbox.alert(data);
                } else {
                    location.reload();
                }
            }
        });
    });

    $('#change').click(function(){
        location.href = '/customer/score';
    });
});