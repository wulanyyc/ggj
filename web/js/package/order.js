$(document).ready(function () {
    var cart = {};

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
        cart[id] = {'num': num, 'price': price};

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
        if (total >= 39) {
            $('#order').removeClass('btn-secondary');
            $('#order').addClass('btn-success');
        } else {
            $('#order').addClass('btn-secondary');
            $('#order').removeClass('btn-success');
        }
    }
});

