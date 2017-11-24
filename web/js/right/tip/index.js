/**
 * @file 页面批注js
 */
$(document).ready(function () {
    init();
    function init() {
        $('#linkSelect').select2({
            allowClear: true,
            minimumResultsForSearch: Infinity
        });

        $('#linkSelect').change(function () {
            loadTable();
        });

        $('#add').click(function () {
            var link = $('#linkSelect').val();
            bootbox.dialog({
                message: $('#data_modal').html(),
                title: '添加批注-' + $('#linkSelect option[value="' + link + '"]').html(),
                className: 'modal-primary',
                buttons: {
                    success: {
                        label: '提交',
                        className: 'btn-success',
                        callback: function () {
                            $.load.show('.bootbox');
                            var data = $('.bootbox form').serialize();
                            data = data + '&link=' + link;
                            $.ajax({
                                url: '/right/tip/edittip',
                                type: 'post',
                                data: data,
                                dataType: 'json',
                                success: function (data) {
                                    $.load.hide('.bootbox');
                                    if (data.code !== 200) {
                                        bootbox.alert(data.message);
                                    }
                                    else {
                                        bootbox.hideAll();
                                        loadTable();
                                    }
                                }
                            });
                            return false;
                        }
                    }
                }
            });

            $('.bootbox select').select2({
                allowClear: true,
                minimumResultsForSearch: Infinity
            });
            $('.bootbox textarea').autosize({append: '\n'});
            $('.bootbox .spinner').spinner();
        });

        loadTable();
    }
    function loadTable() {
        $.load.show('#tipTable');
        var url = '/right/tip/linktip';
        var link = $('#linkSelect').val();
        $.ajax({
            url: url,
            data: {link: link},
            type: 'post',
            dataType: 'html',
            success: function (data) {
                $.load.hide('#tipTable');
                $('#tipTable').html(data);
                $('.toggle').tooltip();
                bindItemEdit();
            }
        });
    }
    function bindItemEdit() {
        $('.mod-del').on('click', function () {
            var id = $(this).attr('data-id');
            bootbox.confirm({
                buttons: {
                    confirm: {
                        label: '确认'
                    },
                    cancel: {
                        label: '取消'
                    }
                },
                message: '确认删除指注--' + id + '？',
                callback: function (result) {
                    if (result) {
                        $.ajax({
                            url: '/right/tip/deltip',
                            data: {id: id},
                            dataType: 'json',
                            type: 'post',
                            success: function (data) {
                                if (data.code === 200) {
                                    loadTable();
                                } else {
                                    bootbox.alert(data.message);
                                }
                            }
                        });
                    } else {
                        console.log('fail');
                    }
                }
            });
        });
        $('.mod-edit').on('click', function () {
            var id         = $(this).attr('data-id');
            var con        = 'tr[content="' + id + '"] ';

            var content    = $(con + 'span[type="content"]').attr('data-original-title');
            var show_type  = $(con + 'td[type="show_type"]').attr('val');
            var show_order = $(con + 'td[type="show_order"]').html();

            var link       = $('#linkSelect').val();
            bootbox.dialog({
                message: $('#data_modal').html(),
                title: '配置批注-' + $('#linkSelect option[value="' + link + '"]').html() + '--' + id,
                className: 'modal-primary',
                buttons: {
                    success: {
                        label: '提交',
                        className: 'btn-success',
                        callback: function () {
                            $.load.show('.bootbox');
                            var data = $('.bootbox form').serialize();
                            data = data + '&link=' + link;
                            $.ajax({
                                url: '/right/tip/edittip',
                                type: 'post',
                                data: data,
                                dataType: 'json',
                                success: function (data) {
                                    $.load.hide('.bootbox');
                                    if (data.code !== 200) {
                                        bootbox.alert(data.message);
                                    }
                                    else {
                                        bootbox.hideAll();
                                        loadTable();
                                    }
                                }
                            });
                            return false;
                        }
                    }
                }
            });
            $('.bootbox textarea[name="content"]').val(content);
            $('.bootbox select[name="show-type"]').val(show_type);
            $('.bootbox input[name="id"').val(id);
            $('.bootbox select').select2({
                allowClear: true,
                minimumResultsForSearch: Infinity
            });
            $('.bootbox textarea').autosize({append: '\n'});
            $('.bootbox .spinner').spinner().spinner('value', show_order);
        });
    }
});