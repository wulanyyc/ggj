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
            dataType: 'html',
            success: function (data) {
                if (data == 'ok') {
                    location.href="/customer";
                } else {
                    $.helper.alert(data);
                }
            }
        });
    });

    $('#ask').click(function(){
        $('#question').show();
        $('#cover').show();
        $('body').addClass('forbid');
    });

    $('#close_question').click(function(){
        $('#question').hide();
        $('#cover').hide();
        $('body').removeClass('forbid');
    });
});