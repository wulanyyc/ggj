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
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/pay/add',
            type: 'post',
            dataType: 'html',
            data: "id=" + id,
            success: function (data) {
                $.helper.alert(data);
            }
        });
    });
});