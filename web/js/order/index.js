$(document).ready(function () {
    function disableScrollFn(e) { 
        e.preventDefault();
        e.stopPropagation();
    };

    $('.show_detail').click(function() {
        $('#detail').show();
        $('#cover').show();
        $('html,body').addClass('forbid');

        var cid = $(this).attr('data-cid');
        var id = $(this).attr('data-id');

        var giftHtml = $(this).parent().parent().find('.show_gifts').html();
        $('#inner-gifts').html(giftHtml);

        $.ajax({
            url: '/order/product',
            type: 'get',
            data: {
                cid: cid,
                id: id
            },
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    $('#table-content').html(data.data);
                } else {
                    $.helper.alert(data.msg);
                }
                
            }
        });
    });

    $('#close_detail').click(function(){
        $('#detail').hide();
        $('html,body').removeClass('forbid');
        $('#cover').hide();
    });

    $('.edit').click(function(){
        var cid = $(this).attr('data-cid');
        location.href="/?cid=" + cid;
    });

    $('.pay').click(function(){
        var cid = $(this).attr('data-cid');
        location.href="/cart?id=" + cid;
    });

    $('.del').click(function(){
        var oid = $(this).attr('data-id');
        $.helper.confirm('确定要删除该订单', function(result){
            if (!result) {
                return ;
            }
            
            $.ajax({
                url: '/order/del',
                type: 'post',
                data: {
                    id: oid
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 'ok') {
                        refresh();
                    } else {
                        $.helper.alert(data.msg);
                    }
                }
            });
        });
    });

    $('.status-item').click(function(e){
        $('.status-item').each(function(){
            $(this).removeClass('active');
        });

        $(this).addClass('active');
        var status = $(this).attr('data-type');

        $(".order-item").each(function(){
            if (status == "") {
                if ($(this).attr('data-type') != 5) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else {
                if ($(this).attr('data-type') == status) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });

    function init() {
        $('#first').click();
    }

    init();

    $('.express').click(function() {
        $('#express_info').show();
        $('#cover').show();

        $('html,body').addClass('forbid');

        var id = $(this).attr('data-id');
        var express_num = $(this).attr('data-express-num');
        $('#express_copy_num').val(express_num);

        $.ajax({
            url: '/order/expressinfo',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    $('#express_info_content').html(data.data);
                    $('#express_detail').find('.step').first().addClass('active');
                } else {
                    $('#unknown').html(data.msg);
                }
            }
        });
    });

    $('#close_express').click(function(){
        $('#express_info').hide();
        $('#cover').hide();
        $('html,body').removeClass('forbid');
    });

    var clipboard = new Clipboard('#copy');

    clipboard.on('success', function(e) {
        $.helper.alert('复制成功');
        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        $.helper.alert('复制失败');
    });

    $('.ok').click(function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/order/complete',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    refresh();
                } else {
                    $.helper.alert("更新失败");
                }
            }
        });
    });

    $('.del_forever').click(function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/order/delforever',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    refresh();
                } else {
                    $.helper.alert("删除失败");
                }
            }
        });
    });

    function refresh() {
        var active_type = $('.status-item.active').attr('data-type');
        if (active_type > 0) {
            location.href = "/order?type=" + active_type;
        } else {
            location.href = "/order";
        }
    }
});