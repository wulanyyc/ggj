$(document).ready(function () {
    var money = parseFloat($('#wallet').html());
    var order_money = parseFloat($('#order_money').html());

    if (money > order_money) {
        $('#pay_text').html("钱包余额支付");
        $('#pay_price').html(order_money);
    } else {
        $('#pay_text').html("支付宝支付");
        var diff = $.helper.round(order_money - money, 1);
        $('#pay_price').html(diff);
    }

    $('#pay').click(function(){
        if ($(this).attr('data-process') == 1) {
            console.log('repeat pay');
            return ;
        }

        $(this).attr('data-process', 1);

        var id = $(this).attr('data-id');

        $.ajax({
            url: '/pay/add',
            type: 'post',
            dataType: 'json',
            data: "id=" + id,
            success: function (data) {
                if (data.status == 'ok') {
                    if (data.pay_type == 1) {
                        // 支付宝
                        $("body").append(data.html);
                    } else {
                        // 钱包
                        location.href='/pay/wallet?id=' + data.id;
                    }
                } else {
                    $.helper.alert(data.msg);
                }
                $(this).attr('data-process', 0);
            }
        });
    });
});