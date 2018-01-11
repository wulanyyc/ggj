$(document).ready(function () {
    cart = {};
    var back = -1;
    timeLimit = 120;
    clock = 0;

    function init() {
        $('#order_scroll').css('height', $(window).height() - 185);

        var id = parseInt($('#scroll_id').val());
        if (id > 0) {
            if ($('#pid_' + id).length > 0) {
                var scroll = $('#pid_' + id).offset().top - $('#order_scroll').offset().top;
                $("#order_scroll").animate({scrollTop : scroll}, 600);
            }
        }

        var cartHistory = $('#buyCart').val();

        if (cartHistory.length > 0) {
            var cartJson = $.parseJSON(cartHistory);
            this.cart = cartJson;

            for( var i in cartJson) {
                $("#pid_" + cartJson[i]['id'] + " .operator-num").html(cartJson[i]['num']);
                $("#pid_" + cartJson[i]['id'] + " .operator").addClass('active');
                $("#pid_" + cartJson[i]['id'] + " .operator-left").addClass('active');
                $("#pid_" + cartJson[i]['id'] + " .operator-right").addClass('active');
            }
            calculateTotal();
        }
    }

    init();

    $('.list-group-item').click(function(){
        back -= 1;
        $.cookie('buy-history-back', back, { path: '/' });
    });

    $('#order_scroll').scrollspy({ target: '#menu_list' });

    $('.operator-right').click(function(){
        var num = $(this).parent().find('.operator-num').html();

        num = parseInt(num) + 1;

        var limit = parseInt($(this).attr('data-limit'));
        var buyLimit = parseInt($(this).attr('data-buy-limit'));
        if (buyLimit > 0 && buyLimit <= limit) {
            limit = buyLimit;
        }

        if (num > limit) {
            num = num - 1;
        }

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
                delete cart[id];
            }
        }

        calculateTotal();
    });

    function calculateTotal() {
        var total = 0;
        $.each(cart, function(k, v) {
            total += v.num * v.price;
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
            $('#order').html(limit + '元起购');
        }
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
        if ($.isEmptyObject(cart)) {
            return false;
        }

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
            $(this).attr("data-filter", 1);
            $(this).html("显示全部");
            $(this).blur();
        } else {
            $(this).attr("data-filter", 0);
            $(this).html("仅显示订购");
            $(this).blur();
        }
    });
});

