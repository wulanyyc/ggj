$(document).ready(function () {
    var cart = {};
    var back = -1;

    $('.list-group-item').click(function(){
        back -= 1;
        $.cookie('order-history-back', back);
    });

    $('#order_scroll').scrollspy({ target: '#order_list' });

    $('.operator-right').click(function(){
        var num = $(this).parent().find('.operator-num').html();
        num = parseInt(num) + 1;

        $(this).parent().find('.operator-num').html(num);
        if (!$(this).parent().hasClass('active')) {
            $(this).parent().addClass('active');
            $(this).parent().find('.operator-btn').addClass('active');
        }

        var id = $(this).parent().attr('data-id');
        var price = $(this).parent().attr('data-price');
        cart[id] = {'num': num, 'price': price, 'id': id};

        calculateTotal();
    });

    $('.operator-left').click(function(){
        var num = $(this).parent().find('.operator-num').html();
        num = parseInt(num) - 1;

        var id = $(this).parent().attr('data-id');

        if (num > 0) {
            $(this).parent().find('.operator-num').html(num);
            cart[id].num = num;
        } else {
            $(this).parent().removeClass('active');
            $(this).parent().find('.operator-btn').removeClass('active');
            $(this).parent().find('.operator-num').html(0);
            if (cart[id]) {
                cart[id].num = 0;
            }
        }

        calculateTotal();
    });

    function calculateTotal() {
        var total = 0;
        $.each(cart, function(k, v) {
            total += v.num * v.price;
        });

        total = Math.round(total * 100) / 100;

        $('#tongji .realprice').html(total);
        if (total >= 29) {
            $('#order').removeClass('btn-secondary');
            $('#order').addClass('btn-success');
            $('#order').html('选好了');
        } else {
            $('#order').addClass('btn-secondary');
            $('#order').removeClass('btn-success');
            $('#order').html('29元起购');
        }
    }

    $('#close_userinfo').click(function() {
        $('#userinfo').hide();
    });

    $('#order').click(function() {
        var money = $('#tongji .realprice').html();
        if (money >= 29) {
            $('#pay_money').html(money + "元");
            $('#userinfo').show();
        }
    });

    $('#pay').click(function() {
        var formData = $('#userinfo_form').serialize();
        var cartStr = JSON.stringify(cart);
        var money = $('#tongji .realprice').html();
        $.cookie('cellphone', $('#cellphone').val(), { path: '/' });

        console.log($.cookie('cellphone'));

        $.ajax({
            url: '/package/pay',
            type: 'post',
            data: formData + '&cart=' + cartStr + '&money=' + money,
            dataType: 'html',
            success: function (data) {
                if (data > 0) {
                    location.href = "/order/detail?id=" + data;
                } else {
                    alert(data);
                }
            }
        });
    });
});

