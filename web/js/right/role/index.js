$(document).ready(function () {
    $('#cur_role').select2({
        allowClear: true
    });

    $('.role-del').click(function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        window.bootbox.confirm('确认删除: ' + name + '?', function (result) {
            if (result) {
                $.ajax({
                    url: '/right/role/del',
                    type: 'post',
                    data: {
                        'id': id
                    },
                    dataType: 'html',
                    success: function (data) {
                        if (data !== 'suc') {
                            window.bootbox.alert(data);
                        } else {
                            location.reload();
                        }
                    }
                });
            }
        });
    });

    $('.role-edit').click(function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        window.bootbox.dialog({
            message: $('#add_modal').html(),
            title: '编辑：' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var formData = $('.bootbox form').serialize();
                        var postData = formData + '&id=' + id;

                        $.ajax({
                            url: '/right/role/edit',
                            type: 'post',
                            data: postData,
                            dataType: 'html',
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
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

        var name = $(this).attr('data-val');
        $('.bootbox input[name="name"]').val(name);
    });

    $('#add').click(function () {
        window.bootbox.dialog({
            message: $('#add_modal').html(),
            title: '添加',
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        $.ajax({
                            url: '/right/role/add',
                            type: 'post',
                            dataType: 'html',
                            data: $('.bootbox form').serialize(),
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
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

    $('.role-module').click(function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        window.bootbox.dialog({
            message: $('#mod_modal').html(),
            title: '模块设置：' + name,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var postData = $('.bootbox form').serialize();
                        if (postData.length > 0) {
                            postData += '&id=' + id;
                        } else {
                            postData = 'id=' + id;
                        }
                        $.ajax({
                            url: '/right/role/right',
                            type: 'post',
                            dataType: 'html',
                            data: postData,
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
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

        // 加载历史数据
        $.ajax({
            url: '/right/role/history',
            type: 'POST',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    $('.bootbox input[type="checkbox"]').each(function () {
                        if ($(this).attr('value') === data[i]) {
                            $(this).click();
                        }
                    });
                }
            }
        });
    });

    loadTable();

    $('#cur_role').change(function () {
        loadTable();
    });

    $('#query').change(function () {
        loadTable();
    });

    function loadTable() {
        $.load.show('#user_list');
        var config = {};

        config['columnDefs'] = [{
            sortable: false,
            targets: [0, 1, 2, 3]
        }];

        config['columns'] = [
            {data: 'id'},
            {data: 'username'},
            {data: 'createtime'},
            {data: 'operation'}
        ];
        config['displayLength'] = 10;

        $.grid.createServerTable('/right/role/table',
                {query: $('#query').val(), id: $('#cur_role').val()}, 'user_list', config);
        $.load.hide('#user_list');
    }

    $('#user_list').delegate('.user-del', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        window.bootbox.confirm('确认删除用户角色: ' + name + '?', function (result) {
            if (result) {
                $.ajax({
                    url: '/right/role/deluser',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'html',
                    success: function (data) {
                        if (data !== 'suc') {
                            window.bootbox.alert(data);
                        } else {
                            loadTable();
                        }
                    }
                });
            }
        });
    });

    $('#add_user').click(function () {
        window.bootbox.dialog({
            message: $('#user_modal').html(),
            title: '添加角色用户',
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        $.ajax({
                            url: '/right/role/adduser',
                            type: 'post',
                            dataType: 'html',
                            data: $('.bootbox form').serialize(),
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                } else {
                                    loadTable();
                                    $('.bootbox .close').click();
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

        $('.rid').val($('#cur_role').val());

        var id = $('#cur_role').val();
        $.ajax({
            url: '/right/role/userme',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'html',
            success: function (data) {
                if (data.length > 0) {
                    $('.bootbox select').html(data);
                    $('.bootbox select').select2().trigger('change');
                }
            }
        });
    });
});