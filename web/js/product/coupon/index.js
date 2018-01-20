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
                            url: '/product/coupon/add',
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
    $('#list').delegate('.coupon-del', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.confirm('确认删除: ' + name + '?', function (result) {
            if (result) {
                $.ajax({
                    url: '/product/coupon/del',
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

    // 赠送
    $('#list').delegate('.coupon-give', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.dialog({
            message: $('#give_modal').html(),
            title: '赠送：' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        $.ajax({
                            url: '/product/coupon/give',
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

        $('.bootbox select').select2({
            allowClear: true
        });
    });

    // 编辑
    $('#list').delegate('.coupon-edit', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        $.ajax({
            url: '/product/coupon/info',
            type: 'get',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                $('.name').val(data.name);
                $('.money').val(data.money);
                $('.money_limit').val(data.money_limit);
                $('.day').val(data.day);
                $('.type').val(data.type);
                $('.desc').val(data.desc);
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
                            url: '/product/coupon/edit',
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

    $('#query').change(function () {
        loadTable();
    });

    function loadTable() {
        $.load.show('#list');
        var config = {};

        config['columnDefs'] = [{
            sortable: false,
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
        }];

        config['columns'] = [
            {data: 'id'},
            {data: 'name'},
            {data: 'type'},
            {data: 'money'},
            {data: 'day'},
            {data: 'desc'},
            {data: 'start_date'},
            {data: 'end_date'},
            {data: 'operation'}
        ];
        config['displayLength'] = 10;

        $.grid.createServerTable('/product/coupon/table', {query: $('#query').val()}, 'list', config);
        $.load.hide('#list');
    }
});