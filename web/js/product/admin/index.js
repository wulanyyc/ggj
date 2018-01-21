$(document).ready(function () {
    loadTable();

    $('#add').click(function () {
        bootbox.dialog({
            message: $('#add_modal').html(),
            title: '添加',
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        $.ajax({
                            url: '/product/admin/add',
                            type: 'post',
                            dataType: 'html',
                            data: $('.bootbox form').serialize(),
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

    // 删除
    $('#list').delegate('.product-del', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.confirm('确认删除: ' + name + '?', function (result) {
            if (result) {
                $.ajax({
                    url: '/product/admin/del',
                    type: 'post',
                    data: {
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
            }
        });
    });

    // 编辑
    $('#list').delegate('.product-edit', 'click', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/product/admin/info',
            type: 'get',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                $('.name').val(data.name);
                $('.price').val(data.price);
                $('.num').val(data.num);
                $('.desc').val(data.desc);
                $('.slogan').val(data.slogan);
                $('.unit').val(data.unit);
                $('.category').val(data.category);
                $('.buy_limit').val(data.buy_limit);
                $('.img').val(data.img);
                $('.fresh_percent').val(data.fresh_percent);
                $('.seller_id').val(data.seller_id);
                $('.recflag').val(data.recflag);
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
                            url: '/product/admin/edit',
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

    $('#list').delegate('.product-tag', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.dialog({
            message: $('#tag_modal').html(),
            title: '设置标签: ' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var pid = $('.bootbox select[name="tag"]').val();
                        $.ajax({
                            url: '/product/admin/tag',
                            type: 'post',
                            data: {
                                'tag': pid,
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
            url: '/product/admin/tagme',
            type: 'post',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    $('.bootbox select[name="tag"]').val(data);
                    $('.bootbox select').select2().trigger('change');
                }
            }
        });
    });


    $('#list').delegate('.product-status', 'click', function () {
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
                            url: '/product/admin/status',
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
    });

    $('#list').delegate('.booking-status', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.dialog({
            message: $('#booking_status_modal').html(),
            title: '设置预约状态: ' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var status = $('.bootbox select[name="booking_status"]').val();
                        $.ajax({
                            url: '/product/admin/bookingstatus',
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
    });

    $('#list').delegate('.product-connect', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        $.ajax({
            url: '/product/admin/packageinfo',
            type: 'post',
            data: 'id=' + id,
            dataType: 'json',
            success: function (data) {
                for(var i in data) {
                    // console.log('#product_' + data[i]['product_id']);
                    $('.product_' + data[i]['product_id']).val(data[i]['num']);
                }
            }
        });

        bootbox.dialog({
            message: $('#connect_modal').html(),
            title: '关联套餐产品: ' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        $.ajax({
                            url: '/product/admin/connect',
                            type: 'post',
                            data: $('.bootbox form').serialize() + '&id=' + id,
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
    });

    $('#list').delegate('.product-inventory', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.dialog({
            message: $('#inventory_modal').html(),
            title: '库存管理: ' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var operator_func = $('.bootbox select[name="operator_func"]').val();
                        var num = $('.bootbox input[name="num"]').val();
                        var operator = $('.bootbox select[name="operator"]').val();
                        var price = $('.bootbox input[name="price"]').val();

                        $.ajax({
                            url: '/product/admin/inventory',
                            type: 'post',
                            data: {
                                'operator_func': operator_func,
                                'num': num,
                                'operator': operator,
                                'pid': id,
                                'price': price
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
    });


    $('#query').change(function () {
        loadTable();
    });

    $('#search_form select').change(function(){
        loadTable();
    });

    function loadTable() {
        $.load.show('#list');
        var config = {};

        config['columnDefs'] = [{
            sortable: false,
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        }];

        config['columns'] = [
            {data: 'id'},
            {data: 'name'},
            {data: 'seller_id'},
            {data: 'price'},
            {data: 'num'},
            {data: 'unit'},
            {data: 'category'},
            {data: 'desc'},
            {data: 'slogan'},
            {data: 'status'},
            {data: 'booking_status'},
            {data: 'operation'}
        ];
        config['displayLength'] = 10;

        $.grid.createServerTable('/product/admin/table', 
            { 
                query: $('#query').val(),
                status: $('#search_status').val(),
                booking_status: $('#search_booking').val() 
            }, 
            'list', config);

        $.load.hide('#list');
    }
});