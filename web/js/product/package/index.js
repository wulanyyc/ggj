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
                            url: '/product/package/add',
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
                    url: '/product/package/del',
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
            url: '/product/package/info',
            type: 'get',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                $('.name').val(data.name);
                $('.price').val(data.price);
                $('.desc').val(data.desc);
                $('.slogan').val(data.slogan);
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
                            url: '/product/package/edit',
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

    $('#list').delegate('.product-set', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.dialog({
            message: $('#product_modal').html(),
            title: '设置套餐: ' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var pid = $('.bootbox select[name="product"]').val();
                        // alert(pids);
                        $.ajax({
                            url: '/product/package/product',
                            type: 'post',
                            data: {
                                'pid': pid,
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
            url: '/product/package/productme',
            type: 'post',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    $('.bootbox select[name="product"]').val(data);
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
                        var status = $('.bootbox select[name="disabled"]').val();
                        $.ajax({
                            url: '/product/package/status',
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

    $('#query').change(function () {
        loadTable();
    });

    function loadTable() {
        $.load.show('#list');
        var config = {};

        config['columnDefs'] = [{
            sortable: false,
            targets: [0, 1, 2, 3, 4, 5, 6]
        }];

        config['columns'] = [
            {data: 'id'},
            {data: 'name'},
            {data: 'price'},
            {data: 'desc'},
            {data: 'slogan'},
            {data: 'disabled'},
            {data: 'operation'}
        ];
        config['displayLength'] = 10;

        $.grid.createServerTable('/product/package/table', {query: $('#query').val()}, 'list', config);
        $.load.hide('#list');
    }
});