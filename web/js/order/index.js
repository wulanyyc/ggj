$(document).ready(function () {
    function disableScrollFn(e) { 
        e.preventDefault();
        e.stopPropagation();
    };

    $('.show_detail').click(function() {
        $('#detail').show();
        $('#cover').show();

        $('body').addClass('forbid');

        var id = $(this).attr('data-cid');

        $.ajax({
            url: '/order/product',
            type: 'get',
            data: {
                id: id
            },
            dataType: 'html',
            success: function (data) {
                $('#table-content').html(data);
            }
        });
    });

    $('#close_detail').click(function(){
        $('#detail').hide();
        $('body').removeClass('forbid');
        $('#cover').hide();
    });

    $('.edit').click(function(){
        var type = $(this).attr('data-type');
        var cid = $(this).attr('data-cid');
        if (type == 0) {
            location.href="/buy?cid=" + cid;
        } else {
            location.href="/buy/booking?cid=" + cid;
        }
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
                type: 'get',
                data: {
                    id: oid
                },
                dataType: 'html',
                success: function (data) {
                    location.reload();
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
        // console.log(status);
        $(".order-item").each(function(){
            if (status == "") {
                $(this).show();
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

        $('body').addClass('forbid');

        var id = $(this).attr('data-id');
        var express_num = $(this).attr('data-express-num');
        $('#express_copy_num').val(express_num);

        $.ajax({
            url: '/order/expressinfo',
            type: 'get',
            data: {
                id: id
            },
            dataType: 'html',
            success: function (data) {
                $('#express_info_content').html(data);
                $('#express_detail').find('.step').first().addClass('active');
            }
        });
    });

    $('#close_express').click(function(){
        $('#express_info').hide();
        $('body').removeClass('forbid');
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
            type: 'get',
            data: {
                id: id
            },
            dataType: 'html',
            success: function (data) {
                if (data == 'ok') {
                    location.reload();
                } else {
                    $.helper.alert("更新失败");
                }
            }
        });
    });
});