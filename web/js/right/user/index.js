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
                            url: '/right/user/add',
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

    $('#list').delegate('.user-del', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.confirm('确认删除: ' + name + '?', function (result) {
            if (result) {
                $.ajax({
                    url: '/right/user/del',
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

    // 角色设置
    $('#list').delegate('.user-role', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        bootbox.dialog({
            message: $('#role_modal').html(),
            title: '设置角色: ' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var pid = $('.bootbox select[name="role"]').val();
                        $.ajax({
                            url: '/right/user/role',
                            type: 'post',
                            data: {
                                'role': pid,
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
            url: '/right/user/roleme',
            type: 'post',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    $('.bootbox select[name="role"]').val(data);
                    $('.bootbox select').select2().trigger('change');
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
            targets: [0, 1, 2, 3, 4]
        }];

        config['columns'] = [
            {data: 'id'},
            {data: 'username'},
            {data: 'createtime'},
            {data: 'role'},
            {data: 'operation'}
        ];
        config['displayLength'] = 10;

        $.grid.createServerTable('/right/user/table', {query: $('#query').val()}, 'list', config);
        $.load.hide('#list');
    }
});