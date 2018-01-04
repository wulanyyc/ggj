$(document).ready(function () {
    var money = parseFloat($('#wallet').html());
    var order_money = parseFloat($('#order_money').html());

    $('.pay_tool').click(function(){
        var payTool = $(this).attr('data-id');
        $('.status').each(function(){
            $(this).html('<i class="fa fa-circle-o" aria-hidden="true"></i>');
        });

        $(this).find('.status').html('<i class="fa fa-dot-circle-o" aria-hidden="true"></i>');

        if (payTool == 'wx') {
            $('#pay_text').html("微信支付");
            $('#pay').attr('data-pay', 2);
        } else {
            $('#pay_text').html("支付宝支付");
            $('#pay').attr('data-pay', 1);
        }
    });

    if (money >= order_money) {
        $('#pay_text').html("钱包余额支付");
        $('#pay_price').html(order_money);
    } else {
        $('#pay_text').html("网上支付");
        var diff = $.helper.round(order_money - money, 1);
        $('#pay_price').html(diff);

        $('#wechat').click();
    }

    $('#pay').click(function(){
        if ($(this).attr('data-process') == 1) {
            console.log('repeat pay');
            return ;
        }

        $(this).attr('data-process', 1);

        var id = $(this).attr('data-id');
        var type = $(this).attr('data-pay');

        if (money < order_money && type == 0) {
            $.helper.alert('请选择支付方式');
            return ;
        }

        $.ajax({
            url: '/pay/add',
            type: 'post',
            dataType: 'json',
            data: "id=" + id + '&type=' + type,
            success: function (data) {
                if (data.status == 'ok') {
                    if (data.pay_type > 0) {
                        if (data.pay_type == 1) {
                            // 支付宝
                            $("body").append(data.html);
                        } else {
                            $.helper.alert('测试微信支付')
                        }
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