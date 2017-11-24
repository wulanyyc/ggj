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
                            url: '/product/tag/add',
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
    $('#list').delegate('.tag-del', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.confirm('确认删除: ' + name + '?', function (result) {
            if (result) {
                $.ajax({
                    url: '/product/tag/del',
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
    $('#list').delegate('.tag-edit', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        $.ajax({
            url: '/product/tag/info',
            type: 'get',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                $('.name').val(data.name);
                $('.en_name').val(data.en_name);
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
                            url: '/product/tag/edit',
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
            targets: [0, 1, 2, 3,4]
        }];

        config['columns'] = [
            {data: 'id'},
            {data: 'name'},
            {data: 'en_name'},
            {data: 'create_time'},
            {data: 'operation'}
        ];
        config['displayLength'] = 10;

        $.grid.createServerTable('/product/tag/table', {query: $('#query').val()}, 'list', config);
        $.load.hide('#list');
    }
});