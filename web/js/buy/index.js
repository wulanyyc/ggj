$(document).ready(function () {
    cart = {};
    timeLimit = 120;
    clock = 0;

    function init() {
        $('#order_scroll').css('height', $(window).height() - 126);

        var id = parseInt($('#scroll_id').val());
        if (id > 0) {
            if ($('#pid_' + id).length > 0) {
                var scroll = $('#pid_' + id).offset().top - $('#order_scroll').offset().top;
                $("#order_scroll").animate({scrollTop : scroll}, 600);

                $('#pid_' + id).addClass('active');
            }
        }

        var cartHistory = $('#buyCart').val();

        if (cartHistory.length > 0) {
            var cartJson = $.parseJSON(cartHistory);
            this.cart = cartJson;

            for( var i in cartJson) {
                $("#pid_" + cartJson[i]['id'] + " .operator-num").html(cartJson[i]['num']);
                $("#pid_" + cartJson[i]['id'] + " .operator-left").css('visibility', 'visible');
                $("#pid_" + cartJson[i]['id'] + " .operator-num").css('visibility', 'visible');
            }
            calculateTotal();
        }
    }

    init();

    $('.product-img, .product-desc .title, .product-desc .slogan').click(function() {
        $('#cover').show();
        $('#imgs_alert').show();
        $('.img_slogan').html($(this).attr('data-desc'));

        $.ajax({
            url: '/buy/imgs',
            type: 'post',
            data: 'pid=' + $(this).attr('data-id'),
            dataType: 'html',
            success: function (ret) {
                $('#carouselContainer').html(ret);
                $('.carousel').carousel({
                    interval: false,
                    wrap: false
                });

                $.helper.touchdirection('carouselIndicators', function(){
                    if (Direction == 'right') {
                        $('.carousel').carousel('next');
                    }

                    if (Direction == 'left') {
                        $('.carousel').carousel('prev');
                    }
                });
            }
        });
    });

    $('#close_imgs, #cover').click(function(){
        $('#cover').hide();
        $('.carousel').html('加载中...');
        $('#imgs_alert').hide();
    });

    $('a.list-group-item').click(function(){
        // back -= 1;
        // $.cookie('buy-history-back', back, { path: '/' });
        
        $(".order-product").show();
        $('.list-group-item').removeClass('active');
        $(this).addClass('active');
    });

    $('div.list-group-item').click(function(){
        $('.list-group-item').removeClass('active');
        $(this).addClass('active');

        // if ($(this).attr('id') != 'list-shop' && $(this).attr('id') != 'list-today') {
        //     $(".order-product").show();
        // }
    });

    $('#order_scroll').scrollspy({ target: '#menu_list' });

    $('.operator-right').click(function() {
        var num = $(this).parent().find('.operator-num').html();

        num = parseInt(num) + 1;

        var limit = parseInt($(this).attr('data-limit'));
        var buyLimit = parseInt($(this).attr('data-buy-limit'));

        if (isNaN(buyLimit)) {
            buyLimit = 0;
        }

        if (num > limit) {
            num = num - 1;
        }

        $(this).parent().find('.operator-num').html(num);
        $(this).parent().find('.operator-left').css('visibility', 'visible');
        $(this).parent().find('.operator-num').css('visibility', 'visible');

        var id     = $(this).parent().attr('data-id');
        var price  = $(this).parent().attr('data-price');
        var oprice = $(this).parent().attr('data-orignal-price');


        cart[id] = {'num': num, 'price': price, 'oprice': oprice, 'id': id, 'limit': buyLimit};

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
            $(this).parent().find('.operator-left').css('visibility', 'hidden');
            $(this).parent().find('.operator-num').css('visibility', 'hidden');
            $(this).parent().find('.operator-num').html(0);

            if (cart[id]) {
                cart[id].num = 0;
                delete cart[id];
            }
        }

        calculateTotal();
    });

    function calculateTotal() {
        var total = 0;
        $.each(cart, function(k, v) {
            if (v.limit > 0 && v.num > v.limit) {
                total += (v.num - v.limit) * v.oprice + v.limit * v.price;
            } else {
                total += v.num * v.price;
            }
        });

        total = $.helper.round(total, 1);
        var limit = parseInt($('#buyLimit').val());

        $('#tongji .realprice').html(total);
        if (total >= limit) {
            $('#order').removeClass('btn-secondary');
            $('#order').addClass('btn-success');
            $('#order').html('选好了');
        } else {
            $('#order').addClass('btn-secondary');
            $('#order').removeClass('btn-success');
            if (total > 0) {
                var diff = limit - total;
                diff = $.helper.round(diff, 1);
                $('#order').html('还差¥' + diff);
            } else {
                $('#order').html(limit + '元起购');
            }
        }

        $('#cart_num').html(Object.keys(cart).length);
    }

    $('#close_login').click(function() {
        $('#getcode').val('');
        $('#getcode').html('发送验证码');
        $('#getcode').removeAttr('disabled');
        $('#login').hide();
        $('#cover').hide();
    });

    $('#getcode').click(function() {
        if ($.helper.validatePhone($('#userphone').val())) {
            $.ajax({
                url: '/sms/getcode',
                type: 'post',
                data: "phone=" + $('#userphone').val(),
                dataType: 'html',
                success: function (data) {
                    // console.log(data);
                    if (parseInt(data) > 0) {
                        clock = setInterval(function(){
                            timeLimit--;
                            $('#getcode').html(timeLimit + 's');
                            if (timeLimit == 0) {
                                clearInterval(clock);
                                $('#getcode').val('');
                                $('#getcode').html('发送验证码');
                                $('#getcode').removeAttr('disabled');
                            }
                        }, 1000);

                        $('#getcode').attr('disabled', 'disabled');
                    } else {
                        bootbox.alert(data);
                    }
                }
            });
        } else {
            bootbox.alert('手机号码格式有误');
        }
    });

    function order() {
        var cartStr = JSON.stringify(cart);
        var oid = $('#order_id').val();

        var money = $('#tongji .realprice').html();
        var type = $('#order_type').val();

        $.ajax({
            url: '/cart/add',
            type: 'post',
            data: 'cart=' + cartStr + "&oid=" + oid + "&product_price=" + money + "&order_type=" + type,
            dataType: 'json',
            success: function (ret) {
                if (ret.data > 0) {
                    location.href = "/cart?id=" + ret.data;
                } else {
                    if (ret.msg == '用户验证失败') {
                        $('#login').show();
                        $('#cover').show();
                    } else {
                        $.helper.alert(ret.msg);
                    }
                }
            }
        });
    }

    $('#order').click(function() {
        var realprice = parseFloat($('#tongji .realprice').html());
        var limit = parseFloat($('#buyLimit').val());

        if (realprice < limit) {
            return ;
        }

        if (($.cookie('cid') && $.cookie('secret')) || $.cookie('openid')) {
            order();
        } else {
            $('#login').show();
            $('#cover').show();
        }
    });

    $('#next').click(function(){
        clearInterval(clock);
        $.ajax({
            url: '/sms/vcode',
            type: 'post',
            data: "phone=" + $('#userphone').val() + "&code=" + $('#code').val(),
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    // $.cookie('userphone', $('#userphone').val(), { path: '/', expires: 30 });
                    $.cookie('secret', data.data.secret, { path: '/', expires: 30 });
                    $.cookie('cid', data.data.cid, { path: '/', expires: 30 });
                    
                    $('#login').hide();
                    $('#getcode').val('');
                    $('#getcode').html('发送验证码');
                    $('#getcode').removeAttr('disabled');

                    order();
                } else {
                    bootbox.alert(data.msg);
                }
                $('#cover').hide();
            }
        });
    });

    $("#filter").click(function(){
        var type = parseInt($(this).attr("data-filter"));
        $(".order-product").each(function(){
            if (type == 0) {
                var id = $(this).attr('data-id');
                if (cart[id] && cart[id].num > 0) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else {
                $(this).show();
            }
        });

        if (type == 0) {
            $(this).find('#cart_icon').html('<i class="fa fa-undo" aria-hidden="true"></i>');
            $(this).attr("data-filter", 1);
        } else {
            $(this).find('#cart_icon').html('<i class="fa fa-cart-arrow-down" aria-hidden="true"></i>');
            $(this).attr("data-filter", 0);
  
        }
    });

    $("#list-shop").click(function(){
        var special = $('#special').val();
        $(".order-product").each(function(){
            var id = $(this).attr('data-id');
            if (id == special) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $("#list-today").click(function(){
        var today = $('#today').val();
        $(".order-product").each(function(){
            var id = $(this).attr('data-id');
            if (id == today) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

