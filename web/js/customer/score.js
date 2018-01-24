$(document).ready(function () {
    $('#score').css('height', $(window).height() - 55);

    $('.change').click(function(){
        var id = $(this).attr('data-id');
        var score = parseInt($('#current_score').html());
        var needScore = parseInt($(this).attr('data-score'));

        if (score < needScore) {
            $.helper.alert('积分不够');
            return ;
        }

        $.ajax({
            url: '/customer/change',
            type: 'post',
            data: "id=" + id,
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    $.helper.confirm('已兑换成功，继续兑换？', function(result){
                        if (!result) {
                            location.href="/customer";
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    $.helper.alert(data.msg);
                }
            }
        });
    });
});