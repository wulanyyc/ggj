$(document).ready(function () {
    function calculateRealPrice() {
        var product_price = parseFloat($('#product_price').html());
        var express_fee = parseFloat($('#express_fee_show').html());
        var coupon = parseFloat($('#coupon_fee').html());

        var real_price = parseFloat(product_price + express_fee - coupon);
        var real_price = $.helper.round(real_price, 1);

        if (real_price < 0) {
            real_price = 0;
        }

        $("#realprice").html(real_price);

        return real_price;
    }

    $('#express_time').click(function(){
        $('#express_info').show();
        $('#cover').show();
        $('html,body').addClass('forbid');
    });

    $('#close_express_info, #close_express_info_bottom').click(function(){
        $('#express_info').hide();
        $('#cover').hide();
        $('html,body').removeClass('forbid');
    });

    $('#ask').click(function(){
        $('#question').show();
        $('#cover').show();
        $('html,body').addClass('forbid');
    });

    $('#close_question, #close_question_bottom').click(function(){
        $('#question').hide();
        $('#cover').hide();
        $('html,body').removeClass('forbid');
    });

    $('#edit, #inner_edit_product').click(function(){
        var type = $('#order_type').val();
        var cid = $('#cart_id').val();

        location.href="/site?cid=" + cid;
    });

    $('#product_detail').click(function() {
        $('#detail').show();
        $('#cover').show();
        $('html,body').addClass('forbid');
    });

    $('#close_detail, #close_detail_bottom').click(function(){
        $('#detail').hide();
        $('#cover').hide();
        $('html,body').removeClass('forbid');
    });

    $('#add_address').click(function(){
        $('#address_info').show();
        $('#cover').show();
        $('html,body').addClass('forbid');
    });

    $('#close_address, #close_address_bottom').click(function(){
        $('#address_info').hide();
        $('#cover').hide();
        $('html,body').removeClass('forbid');
    });

    $('#label_add').click(function(){
        $(this).hide();
        $('#label_add_input').show();
    });

    $('#label_add_input_ok').click(function(){
        if ($('#label_add_text').val().length > 0) {
            $('.label_choose').removeClass('active');
            $('#label_add').before('<div class="label_choose active">' + $('#label_add_text').val() + '</div>');
            $('#label_add_input').hide();
            $('#label_add_text').val('');
            $('#label_add').show();
        }
    });

    $('#label_add_group').delegate('.label_choose', 'click', function() {
        $('.label_choose').each(function() {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
    });


    function refreshShowaddress(id) {
        $('.show_address').show();
        $('.no_address').hide();

        $.ajax({
            url: '/address/info',
            type: 'get',
            dataType: 'json',
            data: "id=" + id,
            success: function (ret) {
                if (ret.status == 'fail') {
                    $.helper.alert(ret.msg);
                    return ;
                }

                var data = ret.data;
                $('.show_address').attr('data-id', data.id);
                $('#show_rec_name').html(data.rec_name);
                $('#show_rec_phone').html(data.rec_phone);
                $('#show_address').html(data.rec_city + data.rec_district + data.rec_detail);
                if (data.label.length > 0) {
                    if ($('#show_label').length > 0) {
                        $('#show_label').html(data.label);
                    } else {
                        $('#rec_phone').after('<span id="show_label" class="border border-success text-success">' + data.label + '</span>');
                    }
                }
            }
        });
    }

    function initEditAddress(id) {
        $.ajax({
            url: '/address/info',
            type: 'get',
            dataType: 'json',
            data: "id=" + id,
            success: function (ret) {
                if (ret.status == 'fail') {
                    $.helper.alert(ret.msg);
                    return ;
                }

                var data = ret.data;
                document.getElementById("address_form").reset();
                $('#rec_name').val(data.rec_name);
                $('#phone').val(data.rec_phone);
                $('#rec_city').val(data.rec_city);

                initAddressDistrict(data.rec_city, data.rec_district);

                // $('#rec_district').val(data.rec_district);
                $('#rec_detail').val(data.rec_detail);
                if (data.label.length > 0) {
                    $('.label_choose').removeClass('active');
                    if (data.label != '家' && data.label != '公司' && data.label != '学校') {
                        $('#label_add').before('<div class="label_choose active">' + data.label + '</div>');
                    } else {
                        $('.label_choose').each(function(){
                            if ($(this).html() == data.label) {
                                $(this).addClass('active');
                            }
                        });
                    }
                }
            }
        });
    }

    function reloadAddress() {
        $.ajax({
            url: '/address/carthtml',
            type: 'get',
            dataType: 'json',
            success: function (data) {
                $('#all_address_items').html(data.data);
            }
        });
    }

    $('#save_address').click(function(){
        var label = '';
        if ($('.label_choose.active').html() != undefined) {
            label = $('.label_choose.active').html();
        }

        if (!$.helper.validatePhone($('#phone').val())){
            $.helper.alert('收件人手机号码格式不正确');
            return ;
        }

        $.ajax({
            url: '/address/add',
            type: 'post',
            dataType: 'json',
            data: $('#address_form').serialize()+"&label=" + label,
            success: function (ret) {
                if (ret.data > 0) {
                    $('#edit_address_id').val('');
                    $('#close_address').click();
                    $('#close_all_address').click();
                    refreshShowaddress(ret.data);
                    reloadAddress();
                } else {
                    $.helper.alert(ret.msg);
                }
            }
        });
    });

    $('#show_address_content').delegate('.show_address', 'click', function(){
        var id = $(this).attr("data-id");
        $('.address-status').each(function() {
            if ($(this).attr("data-id") == id) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });
        $('#all_address_info').show();
        $('#cover').show();
        $('html,body').addClass('forbid');
    });

    $('#close_all_address, #close_all_address_bottom').click(function(){
        $('#all_address_info').hide();
        $('#cover').hide();
        $('html,body').removeClass('forbid');
    });

    $('#inner_add_address').click(function(){
        document.getElementById("address_form").reset();
        $('#all_address_info').hide();
        $('#address_info').show();
        $('#cover').show();
        $('html,body').addClass('forbid');
        $('#rec_city').change();
    });


    $('#all_address_items').delegate('.address-status', 'click', function(){
        $('#close_all_address').click();
        refreshShowaddress($(this).attr('data-id'));
    });

    $('#all_address_items').delegate('.address-content', 'click', function(){
        $('#close_all_address').click();
        refreshShowaddress($(this).attr('data-id'));
    });

    $('#all_address_items').delegate('.edit_address_item', 'click', function(){
        var id = $(this).attr('data-id');
        $('#edit_address_id').val(id);
        initEditAddress(id);

        $('#all_address_info').hide();
        $('#address_info').show();
        $('html,body').addClass('forbid');
        $('#cover').show();
    });

    $('#all_address_items').delegate('.del_address_item', 'click', function(){
        var id = $(this).attr("data-id");
        $.helper.confirm('确认删除改地址?', function (result) {
            if (result) {
                $.ajax({
                    url: '/address/del',
                    type: 'post',
                    dataType: 'json',
                    data: "id=" + id,
                    success: function (data) {
                        if (data.status == 'ok') {
                            reloadAddress();
                            // TODO
                            var showId = $('.show_address').attr('data-id');
                            if (id == showId) {
                                $('.no_address').show();
                                $('.show_address').hide();
                                $('.show_address').attr('data-id', '');
                            }
                        } else {
                            $.helper.alert(data.msg);
                        }
                    }
                });
            }
        });
    });

    $('#choose_coupon').click(function() {
        var id = $('#cart_id').val();
        $.ajax({
            url: '/cart/coupon',
            type: 'post',
            dataType: 'json',
            data: 'cid=' + id,
            success: function (data) {
                if (data.status == 'fail') {
                    $('#coupon_items').html(data.msg);
                    return ;
                }

                $('#coupon_items').html(data.data);
                var choosed = $('#coupon_items').attr('data-ids');
                if (choosed.length > 0) {
                    var arr = choosed.split(',');
                    for(var i = 0; i < arr.length; i++) {
                        $('#coupon_' + arr[i]).click();
                    }
                }
            }
        });

        $('#coupon').show();
        $('html,body').addClass('forbid');
        $('#cover').show();
    });

    $('#close_coupon, #close_coupon_bottom').click(function(){
        $('#coupon').hide();
        $('html,body').removeClass('forbid');
        $('#cover').hide();
    });

    $('#coupon_items').delegate('.coupon_item', 'click', function(){
        $('#coupon_items').each(function(){
            $(this).find('.coupon_check').html('<i class="fa fa-square-o" aria-hidden="true"></i>');
            $(this).find('.coupon_check').removeClass('text-danger');
        });

        if ($(this).find('.fa-square-o').length > 0) {
            $(this).find('.coupon_check').html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');
            $(this).find('.coupon_check').addClass('text-danger');
        } else {
            $(this).find('.coupon_check').html('<i class="fa fa-square-o" aria-hidden="true"></i>');
            $(this).find('.coupon_check').removeClass('text-danger');
        }
    });

    $('#ok_coupon').click(function(){
        $('#close_coupon').click();
        var money = 0;
        var coupons = [];
        $('.coupon_check').each(function(){
            if ($(this).find('.fa-check-square-o').length > 0) {
                money += parseInt($(this).attr('data-money'));
                coupons.push($(this).attr('data-id'));
            }
        });

        $('#coupon_fee').html(money);
        calculateRealPrice();

        $('#coupon_items').attr('data-ids', coupons.toString());
    });

    $('#order').click(function(){
        var address_id = $('.show_address').attr('data-id');

        if (address_id == undefined || address_id == '') {
            $.helper.alert('请添加收货地址');
            return ;
        }

        $.ajax({
            url: '/order/add',
            type: 'post',
            dataType: 'json',
            data: {
                address_id: address_id,
                cart_id: $('#cart_id').val(),
                cart_num: $('#cart_num').val(),
                memo: $('#memo').val(),
                product_price: $('#product_price').html(),
                pay_money: $('#realprice').html(),
                express_fee: $('#express_fee_show').html(),
                coupon_ids: $('#coupon_items').attr('data-ids'),
                coupon_fee: $('#coupon_fee').html()
            },
            success: function (data) {
                if (data.status == 'ok') {
                    location.href = "/order/pay?oid=" + data.data;
                } else {
                    $.helper.alert(data.msg);
                }
            }
        });
    });

    $('#rec_city').change(function() {
        var city = $(this).val();
        $.ajax({
            url: '/address/getcitymap',
            type: 'post',
            dataType: 'html',
            data: {
                city: city,
            },
            success: function (html) {
                $('#rec_district').html(html);
            }
        });
    });

    function initAddressDistrict(city, district) {
        $.ajax({
            url: '/address/getcitymap',
            type: 'post',
            dataType: 'html',
            data: {
                city: city,
            },
            success: function (html) {
                $('#rec_district').html(html);
                $('#rec_district').val(district);
            }
        });
    }

    function init() {
        var rule    = $('#history_express_rule').val();
        var cart_id = $('#cart_id').val();

        $.cookie('cart_id', cart_id, {expires: 7, path: '/'});

        $.ajax({
            url: '/cart/getexpressrule',
            type: 'post',
            dataType: 'json',
            data: 'cid=' + cart_id,
            success: function (data) {
                if (data.status == 'ok') {
                    $('#history_express_rule').val(data.data);
                    $('#express_rule_' + data.data).click();
                }
            }
        });
    }

    init();
    calculateRealPrice();
});