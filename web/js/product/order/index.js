$(document).ready(function () {
    loadTable();

    $('.input-daterange').datepicker({
        format: "yyyymmdd",
        language: "zh-CN",
        autoclose: true,
        todayHighlight: true,
        todayHighlight: true
    });

    // 状态刷新
    $('#list').delegate('.order-refresh', 'click', function () {
        var pid = $(this).attr('data-pid');

        $.ajax({
            url: '/pay/refresh',
            type: 'post',
            data: {
                'pid': pid
            },
            dataType: 'json',
            success: function (data) {
                if (data.status == 'ok') {
                    bootbox.alert('刷新成功');
                    loadTable();
                } else {
                    bootbox.alert(data.msg);
                }
            }
        });
    });

    // 退款
    $('#list').delegate('.order-refund', 'click', function () {
        var money = $(this).attr('data-val');
        var id = $(this).attr('data-id');
        bootbox.confirm({
            message: "请确认是否退款" + money + '元, 钱包：' + $(this).attr('data-wallet') + ', 线上：' + $(this).attr('data-online'),
            buttons: {
                cancel: {
                    label: '取消'
                },
                confirm: {
                    label: '确定'
                }
            },
            callback: function(result) {
                if (result) {
                    $.ajax({
                        url: '/product/order/refund',
                        type: 'post',
                        data: {
                            'id': id
                        },
                        dataType: 'html',
                        success: function (data) {
                            if (data == 'ok') {
                                bootbox.alert('退款成功');
                                loadTable();
                            } else {
                                bootbox.alert(data);
                            }
                        }
                    });
                }
            }
        });
    });

    // 编辑
    $('#list').delegate('.order-edit', 'click', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/product/order/info',
            type: 'get',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                $('.rec_name').val(data.rec_name);
                $('.rec_phone').val(data.rec_phone);
                $('.rec_address').val(data.rec_address);
            }
        });

        bootbox.dialog({
            message: $('#edit_modal').html(),
            title: '编辑',
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        $.ajax({
                            url: '/product/order/edit',
                            type: 'post',
                            dataType: 'html',
                            data: $('.bootbox form').serialize()+"&id=" + id,
                            success: function (data) {
                                if (data !== 'suc') {
                                    bootbox.alert(data);
                                } else {
                                    location.reload();
                                }
                            }
                        });
                        return false;
                    }
                }
            }
        });
    });

    $('#list').delegate('.order-express', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');

        bootbox.dialog({
            message: $('#express_modal').html(),
            title: '设置快递号: ' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var num = $('.bootbox .express_num').val();
                        $.ajax({
                            url: '/product/order/express',
                            type: 'post',
                            data: {
                                'express_num': num,
                                'id': id
                            },
                            dataType: 'html',
                            success: function (data) {
                                if (data !== 'suc') {
                                    bootbox.alert(data);
                                } else {
                                    location.reload();
                                }
                            }
                        });

                        return false;
                    }
                }
            }
        });

        $.ajax({
            url: '/product/order/expressme',
            type: 'post',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                $('.bootbox .express_num').val(data.express_num);
            }
        });
    });


    $('#list').delegate('.order-status', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.dialog({
            message: $('#status_modal').html(),
            title: '设置状态: ' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var status = $('.bootbox select[name="status"]').val();
                        $.ajax({
                            url: '/product/order/status',
                            type: 'post',
                            data: {
                                'status': status,
                                'id': id
                            },
                            dataType: 'html',
                            success: function (data) {
                                if (data !== 'suc') {
                                    bootbox.alert(data);
                                } else {
                                    location.reload();
                                }
                            }
                        });

                        return false;
                    }
                }
            }
        });

        $('.bootbox select').select2({
            allowClear: true
        });

        $.ajax({
            url: '/product/order/statusme',
            type: 'post',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                $('.bootbox select[name="status"]').val(data.status);
                $('.bootbox select').select2().trigger('change');
            }
        });
    });

    $('#query, #status, #order_type, #start_date, #end_date').change(function () {
        loadTable();
    });

    function loadTable() {
        $.load.show('#list');
        var config = {};

        config['columnDefs'] = [{
            sortable: false,
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
        }];

        config['columns'] = [
            {data: 'id'},
            {data: 'userphone'},
            {data: 'rec_name'},
            {data: 'rec_phone'},
            {data: 'pay_money'},
            {data: 'rec_address'},
            {data: 'status'},
            {data: 'express_company'},
            {data: 'express_num'},
            {data: 'create_time'},
            {data: 'operation'}
        ];
        config['displayLength'] = 10;

        $.grid.createServerTable('/product/order/table', 
            {
                query: $('#query').val(), 
                status: $('#status').val(),
                order_type: $('#order_type').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val()
            }, 
            'list', config
        );
        $.load.hide('#list');
    }
});