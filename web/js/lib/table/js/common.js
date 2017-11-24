/**
 * @file datatables表格生成函数
 */
$(function () {
    $.grid = {};
    $.grid.zhCn = {
        search: '',
        sLengthMenu: '_MENU_',
        sProcessing: '加载中...',
        sZeroRecords: '没有匹配结果',
        sInfo: '显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项',
        sInfoEmpty: '显示第 0 至 0 项结果，共 0 项',
        sInfoFiltered: '(由 _MAX_ 项结果过滤)',
        sInfoPostFix: '',
        sUrl: '',
        sEmptyTable: '表中数据为空',
        sLoadingRecords: '载入中...',
        sInfoThousands: '',
        oPaginate: {
            sFirst: '首页',
            sPrevious: '上页',
            sNext: '下页',
            sLast: '末页'
        },
        oAria: {
            sSortAscending: '以升序排列此列',
            sSortDescending: '以降序排列此列'
        }
    };


    // http://www.datatables.net/reference/option/
    /**
     * 绘制客户端分页表格
     * @param {string} id 表格id
     * @param {Object} config 配置
     * @param {Array} data 数据
     */
    $.grid.createClientTable = function (id, config, data) {
        var curConfig = {
            displayLength: 10,
            columnDefs: [],
            order: [],
            destroy: true,
            filter: false,
            info: true,
            lengthChange: false,
            autoWidth: false,
            language: $.grid.zhCn,
            searchable: false,
            orderMulti: false,
            paginationType: 'simple_numbers'
        };

        if (config && typeof(config) === 'object') {
            for (var i in config) {
                curConfig[i] = config[i];
            }
        }

        if (data) {
            curConfig['data'] = data;
            $('#' + id).dataTable(curConfig);
        }
        else {
            curConfig['data'] = [];
            $('#' + id).dataTable(curConfig);
        }
    };

    /**
     * 绘制服务端分页表格
     * @param {string} url 服务端链接
     * @param {Object} params post参数
     * @param {string} id 表格id
     * @param {Object} config 配置
     */
    $.grid.createServerTable = function (url, params, id, config) {
        var curConfig = {
            displayLength: 10,
            columnDefs: [],
            order: [],
            destroy: true,
            filter: false,
            info: true,
            lengthChange: false,
            autoWidth: false,
            language: $.grid.zhCn,
            searchable: false,
            orderMulti: false,
            paginationType: 'simple_numbers',
            stateSave: true
        };

        if (config && typeof(config) === 'object') {
            for (var i in config) {
                curConfig[i] = config[i];
            }
        }

        curConfig['serverSide'] = true;
        curConfig['ajax'] = {
            url: url,
            type: 'POST',
            data: function (d) {
                if (d.order) {
                    var order = [];
                    for (var i in d.order) {
                        var name = curConfig['columns'][d.order[i]['column']]['data'];
                        var rule = d.order[i]['dir'];
                        d['order'][i] = {};
                        d['order'][i][name] = rule;
                    }
                }
                for (var i in params) {
                    d[i] = params[i];
                }
                d['columns'] = '';
                d['search'] = '';
            }
        };

        $('#' + id).dataTable(curConfig);
    };
});
