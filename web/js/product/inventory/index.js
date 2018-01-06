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
                            url: '/product/inventory/add',
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

    $('#query').change(function () {
        loadTable();
    });

    $('#product_id').change(function () {
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
            {data: 'num'},
            {data: 'current_num'},
            {data: 'price'},
            {data: 'current_price'},
            {data: 'operator'},
            {data: 'operator_id'},
            {data: 'memo'}
        ];
        config['displayLength'] = 10;

        $.grid.createServerTable('/product/inventory/table', 
            { 
                query: $('#query').val(),
                id: $('#product_id').val()
            }, 
            'list', config);

        $.load.hide('#list');
    }
});