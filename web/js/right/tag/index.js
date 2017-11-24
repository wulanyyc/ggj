$(document).ready(function () {
    $('#cur_tag').select2({
        allowClear: true
    });

    $('.tag-del').click(function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        window.bootbox.confirm('确认删除: ' + name + '?', function (result) {
            if (result) {
                $.ajax({
                    url: '/right/tag/del',
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

    $('.tag-edit').click(function () {
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
                            url: '/right/tag/edit',
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
                            url: '/right/tag/add',
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

    loadTable();

    $('#cur_tag').change(function () {
        loadTable();
    });

    $('#query').change(function () {
        loadTable();
    });

    function loadTable(level) {
        $.load.show('#tag_list');
        var config = {};

        config['columnDefs'] = [{
            sortable: false,
            targets: [0, 1, 2, 3]
        }];
        config['columns'] = [
            {data: 'id'},
            {data: 'name'},
            {data: 'tag_match'},
            {data: 'create_time'},
            {data: 'operation'}
        ];
        
        config['displayLength'] = 10;

        $.grid.createServerTable('/right/tag/table',
                {query: $('#query').val(), id: $('#cur_tag').val()}, 'tag_list', config);
        $.load.hide('#tag_list');
    }

    $('#add_tag').click(function () {
        window.bootbox.dialog({
            message: $('#add_second_modal').html(),
            title: '添加',
            className: 'modal-primary',
            buttons: {
                success: {
                    label: '提交',
                    className: 'btn-success',
                    callback: function () {
                        $.ajax({
                            url: '/right/tag/add?id=' + $('#cur_tag').val(),
                            type: 'post',
                            dataType: 'html',
                            data: $('.bootbox form').serialize(),
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                } else {
                                    $('.bootbox .close').click();
                                    loadTable();
                                }
                            }
                        });
                        return false;
                    }
                }
            }
        });
    });

    $('#tag_list').delegate('.tag-del', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        window.bootbox.confirm('确认删除二级标签: ' + name + '?', function (result) {
            if (result) {
                $.ajax({
                    url: '/right/tag/del',
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

    $('#tag_list').delegate('.second-tag-edit', 'click', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-val');
        var tagMatch = $(this).attr('data-match');
        window.bootbox.dialog({
            message: $('#add_second_modal').html(),
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
                            url: '/right/tag/edit',
                            type: 'post',
                            data: postData,
                            dataType: 'html',
                            success: function (data) {
                                if (data !== 'suc') {
                                    window.bootbox.alert(data);
                                } else {
                                    $('.bootbox .close').click();
                                    loadTable();
                                }
                            }
                        });

                        return false;
                    }
                }
            }
        });

        var name = $(this).attr('data-val');
        var tagMatch = $(this).attr('data-match');
        $('.bootbox input[name="name"]').val(name);
        $('.bootbox input[name="tag_match"]').val(tagMatch);
    });
});