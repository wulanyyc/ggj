$(document).ready(function () {
    $('#cur_menu').select2({
        allowClear: true
    });

    $('#list_menu .mod-order').click(function () {
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-val');
        window.bootbox.dialog({
            message: $('#menu_order_modal').html(),
            title: '主菜单显示顺序: ' + text,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var order = $('.bootbox select[name="menu_order"]').val();
                        $.ajax({
                            url: '/right/mod/editmenu',
                            type: 'post',
                            data: {menu_order: order, id: id},
                            dataType: 'html',
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                }
                                else {
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
            url: '/right/mod/menuorderme',
            type: 'post',
            data: {id: id},
            dataType: 'html',
            success: function (data) {
                if (data > 0) {
                    $('.bootbox select[name="menu_order"]').val(data);
                    $('.bootbox select').select2().trigger('change');
                }
            }
        });
    });

    $('#list .mod-order').click(function () {
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-val');
        window.bootbox.dialog({
            message: $('#order_modal').html(),
            title: '子菜单显示顺序: ' + text,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var order = $('.bootbox select[name="menu_order"]').val();
                        $.ajax({
                            url: '/right/mod/edit',
                            type: 'post',
                            data: {'menu_order': order, 'id': id},
                            dataType: 'html',
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                }
                                else {
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
            url: '/right/mod/orderme',
            type: 'post',
            data: {'id': id},
            dataType: 'html',
            success: function (data) {
                if (data > 0) {
                    $('.bootbox select[name="menu_order"]').val(data);
                    $('.bootbox select').select2().trigger('change');
                }
            }
        });
    });

    // 二级子菜单
    $('#list .mod-sub-order').click(function () {
        var id = $(this).attr('data-id');
        var parentId = $(this).attr('data-sub-menu');
        var text = $(this).attr('data-val');

        if (parentId < 1) {
            window.bootbox.alert('请先设置父节点');
            return;
        }

        $.ajax({
            url: '/right/mod/suborderhtml',
            type: 'post',
            data: {id: id, parentId: parentId},
            dataType: 'html',
            success: function (data) {
                $('#order_sub_modal select').html(data);

                window.bootbox.dialog({
                    message: $('#order_sub_modal').html(),
                    title: '二级子菜单显示顺序: ' + text,
                    className: 'modal-primary',
                    buttons: {
                        success: {
                            label: '提交',
                            className: 'btn-success',
                            callback: function () {
                                var order = $('.bootbox select[name="menu_sub_order"]').val();
                                $.ajax({
                                    url: '/right/mod/edit',
                                    type: 'post',
                                    data: {
                                        menuOrder: order,
                                        id: id
                                    },
                                    dataType: 'html',
                                    success: function (data) {
                                        if (data !== 'suc') {
                                            window.bootbox.alert(data);
                                        }
                                        else {
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
                    url: '/right/mod/orderme',
                    type: 'post',
                    data: {id: id},
                    dataType: 'html',
                    success: function (data) {
                        if (data.length > 0) {
                            $('.bootbox select[name="menu_sub_order"]').val(data);
                            $('.bootbox select').select2().trigger('change');
                        }
                    }
                });
            }
        });
    });

    // 角色设置
    $('.mod-role').click(function () {
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-val');
        window.bootbox.dialog({
            message: $('#role_modal').html(),
            title: '设置角色: ' + text,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var pid = $('.bootbox select[name="role"]').val();
                        $.ajax({
                            url: '/right/mod/role',
                            type: 'post',
                            data: {'role': pid, 'id': id},
                            dataType: 'html',
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                }
                                else {
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
            url: '/right/mod/roleme',
            type: 'post',
            data: {'id': id},
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    $('.bootbox select[name="role"]').val(data);
                    $('.bootbox select').select2().trigger('change');
                }
            }
        });
    });

    // 父节点设置
    $('.mod-parent').click(function () {
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-val');
        window.bootbox.dialog({
            message: $('#parent_modal').html(),
            title: '设置父节点: ' + text,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var pid = $('.bootbox select[name="group_parent_id"]').val();
                        $.ajax({
                            url: '/right/mod/edit',
                            type: 'post',
                            data: {'group_parent_id': pid, 'id': id},
                            dataType: 'html',
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                }
                                else {
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
            url: '/right/mod/parent',
            type: 'post',
            data: {'id': id},
            dataType: 'html',
            success: function (data) {
                if (data > 0) {
                    $('.bootbox select[name="group_parent_id"]').val(data);
                    $('.bootbox select').select2().trigger('change');
                }
            }
        });
    });

    // 删除
    $('#list .mod-del').click(function () {
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-val');
        window.bootbox.confirm('确认删除: ' + text + '?', function (result) {
            if (result) {
                $.ajax({
                    url: '/right/mod/del',
                    type: 'post',
                    data: {'id': id},
                    dataType: 'html',
                    success: function (data) {
                        if (data !== 'suc') {
                            window.bootbox.alert(data);
                        }
                        else {
                            location.reload();
                        }
                    }
                });
            }
        });
    });

    // 删除
    $('#list_menu .mod-del').click(function () {
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-val');
        window.bootbox.confirm('确认删除主菜单: ' + text + '?', function (result) {
            if (result) {
                $.ajax({
                    url: '/right/mod/delmenu',
                    type: 'post',
                    data: {id: id},
                    dataType: 'html',
                    success: function (data) {
                        if (data !== 'suc') {
                            window.bootbox.alert(data);
                        }
                        else {
                            location.reload();
                        }
                    }
                });
            }
        });
    });

    // 编辑
    $('#list .mod-edit').click(function () {
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-val');
        window.bootbox.dialog({
            message: $('#data_modal').html(),
            title: '编辑: ' + text,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var formData = $('.bootbox form').serialize();
                        var postData = formData + '&id=' + id;

                        $.ajax({
                            url: '/right/mod/edit',
                            type: 'post',
                            data: postData,
                            dataType: 'html',
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                }
                                else {
                                    location.href = '/right/mod/index?menu_id='
                                        + $('.bootbox select[name="menu_id"]').val();
                                }
                            }
                        });

                        return false;
                    }
                }
            }
        });

        bootInit();

        // 加载历史数据
        $.ajax({
            url: '/right/mod/info?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('.bootbox input[name="text"]').val(data.text);
                $('.bootbox input[name="link"]').val(data.link);
                $('.bootbox input[name="css"]').val(data.css);
                $('.bootbox input[name="module"]').val(data.module);
                $('.bootbox input[name="controller"]').val(data.controller);
                $('.bootbox select[name="type"]').val(data.type);
                $('.bootbox select[name="menu_id"]').val(data.menu_id);
                $('.bootbox select').select2().trigger('change');
            }
        });
    });

    // 编辑
    $('#list_menu .mod-edit').click(function () {
        var id = $(this).attr('data-id');
        var text = $(this).attr('data-val');
        window.bootbox.dialog({
            message: $('#menu_data_modal').html(),
            title: '编辑主菜单: ' + text,
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        var formData = $('.bootbox form').serialize();
                        var postData = formData + '&id=' + id;
                        $.ajax({
                            url: '/right/mod/editmenu',
                            type: 'post',
                            data: postData,
                            dataType: 'html',
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                }
                                else {
                                    location.reload();
                                }
                            }
                        });
                        return false;
                    }
                }
            }
        });
        $('.bootbox input[name="text"]').val(text);
    });

    // 添加
    $('#add').click(function () {
        window.bootbox.dialog({
            message: $('#data_modal').html(),
            title: '添加',
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        $.ajax({
                            url: '/right/mod/add',
                            type: 'post',
                            dataType: 'html',
                            data: $('.bootbox form').serialize(),
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                }
                                else {
                                    location.href = '/right/mod/index?menu_id='
                                        + $('.bootbox select[name="menu_id"]').val();
                                }
                            }
                        });
                        return false;
                    }
                }
            }
        });

        bootInit();
    });

    // 表单初始化逻辑
    function bootInit() {
        $('.bootbox select').select2({
            allowClear: true
        });

        if ($('.bootbox select[name="type"]').val() == 'node') {
            $('.bootbox input').each(function () {
                $(this).css('background-color', '#fff');
            });
        }

        $('.bootbox select[name="type"]').on('change', function () {
            var val = $(this).val();
            if (val === 'node') {
                $('.bootbox input').each(function () {
                    $(this).removeAttr('disabled');
                    $(this).css('background-color', '#fff');
                });
            }

            if (val === 'group_parent') {
                $('.bootbox input').each(function () {
                    $(this).removeAttr('disabled');
                    if ($(this).attr('name') === 'module' || $(this).attr('name') === 'controller' 
                        || $(this).attr('name') === 'link') {
                        $(this).attr('disabled', 'disabled');
                        $(this).val('');

                        $(this).css('background-color', '#fbfbfb');
                    }
                    else {
                        $(this).css('background-color', '#fff');
                    }
                });
            }

            if (val === 'group_node') {
                $('.bootbox input').each(function () {
                    $(this).removeAttr('disabled');
                    if ($(this).attr('name') === 'css') {
                        $(this).attr('disabled', 'disabled');
                        $(this).val('');
                        $(this).css('background-color', '#fbfbfb');
                    }
                    else {
                        $(this).css('background-color', '#fff');
                    }
                });
            }

            if (val === 'other') {
                $('.bootbox input').each(function () {
                    $(this).attr('disabled', 'disabled');
                    if ($(this).attr('name') === 'text') {
                        $(this).removeAttr('disabled');
                        $(this).css('background-color', '#fff');
                    }
                    else {
                        $(this).val('');
                        $(this).css('background-color', '#fbfbfb');
                    }
                });
            }
        });
    }

    // 添加
    $('#add_menu').click(function () {
        window.bootbox.dialog({
            message: $('#menu_data_modal').html(),
            title: '添加主菜单',
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        $.ajax({
                            url: '/right/mod/addmenu',
                            type: 'post',
                            dataType: 'html',
                            data: $('.bootbox form').serialize(),
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                }
                                else {
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

    $('#cur_menu').change(function () {
        location.href = '/right/mod/index?menu_id=' + $(this).val();
    });
});