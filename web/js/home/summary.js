/**
 * @file
 */
var AmCharts = AmCharts || {};
var AmPieChart = AmPieChart || {};
$(document).ready(function () {
    // init();
    function init() {
        $('#date').datepicker({
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            endDate: new Date(new Date() - 86400000)
        }).on('changeDate', function (e) {
            refreshPage();
        });
        $('#client_top_buttons .type').click(function () {
            $('#client_top_buttons .type').attr('class', 'btn type');
            $('#client_top_buttons .type').addClass('btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass($(this).attr('class-tag') + ' selected');
            loadClienttopInfo();
        });
        $('#client_top_buttons .cmatch').click(function () {
            $('#client_top_buttons .cmatch').attr('class', 'btn cmatch');
            $('#client_top_buttons .cmatch').addClass('btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass($(this).attr('class-tag') + ' selected');
            loadClienttopInfo();
        });
        $('#flow_buttons .cmatch').click(function () {
            $('#flow_buttons .cmatch').attr('class', 'btn cmatch');
            $('#flow_buttons .cmatch').addClass('btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass($(this).attr('class-tag') + ' selected');
            loadPcflowInfo();
        });

        refreshPage();
    }

    function refreshPage() {
        loadPageInfo();
        loadChargeInfo();
        loadCpm1Info();
        loadCompareInfo();
        loadPcflowInfo();
        loadClienttopInfo();
        loadClientBudgetInfo();
        loadMonitor();
    }

    function loadMonitor() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/topic/monitor/table',
            type: 'POST',
            data: {
                date: stdate
            },
            dataType: 'html',
            success: function (data) {
                $('#monitor-table').html(data);
            }
        });
    }

    function loadBusiness() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/topic/monitor/business',
            type: 'POST',
            data: {
                date: stdate
            },
            dataType: 'json',
            success: function (data) {
                $('#pc-business').html(data.pc_webpage);
                $('#wise-business').html(data.wise_webpage);
                /*if (data.pc_webpage) {
                    $('#pc-business').html(data.pc_webpage);
                } else {
                    $('#pc-business').hide();
                }
                if (data.wise_webpage) {
                    $('#wise-business').html(data.wise_webpage);
                } else {
                    $('#wise-business').hide();
                }*/
            }
        });
    }

    function loadConsume() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/topic/monitor/consume',
            type: 'POST',
            data: {
                date: stdate
            },
            dataType: 'html',
            success: function (data) {
                setMonitHtml('consume', data);
            }
        });
    }
    function setMonitHtml(id, data) {
        var setHtml = '';
        var len = 1;
        $('.' + id + '-line').remove();
        if (data === '暂无数据' || data.indexOf('<span') === 0) {
            setHtml = '<td class="' + id + '-line" colspan="2">' + data + '</td>';
            $('#' + id + ' td').attr('rowspan', 1);
            $('#' + id).append(setHtml);
        } else {
            var sp = data.split('，');
            for (var i in sp) {
                var tmpLine = sp[i].replace('<span', '</td><td><span');
                setHtml += '<tr class="' + id + '-line"><td>' + tmpLine + '</td></tr>';
            }
            len = sp.length + 1;
            $('#' + id + ' td').attr('rowspan', len);
            $('#' + id).after(setHtml);
        }
    }
    function loadCpm() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/topic/monitor/cpm',
            type: 'POST',
            data: {
                date: stdate
            },
            dataType: 'html',
            success: function (data) {
                setMonitHtml('cpm', data);
            }
        });
    }
    function loadPv() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/topic/monitor/pv',
            type: 'POST',
            data: {
                date: stdate
            },
            dataType: 'html',
            success: function (data) {
                setMonitHtml('pv', data);
            }
        });
    }
    function loadAcp() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/topic/monitor/acp',
            type: 'POST',
            data: {
                date: stdate
            },
            dataType: 'json',
            success: function (data) {
                var content = data['225'];
                if (data.pc_ppim) {
                    content += '，' + data.pc_ppim;
                }
                if (data.pc_webpage_left) {
                    content += '，' + data.pc_webpage_left;
                }
                setMonitHtml('acp', content);
            }
        });
    }
    function loadTrade() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/topic/monitor/trade',
            type: 'POST',
            data: {
                date: stdate
            },
            dataType: 'json',
            success: function (data) {
                $('#pc-trade').html(data.pc_fc_trade);
                $('#wise-trade').html(data.wise_fc_trade);
                $('#pc-sort').html(data.pc_fc);
                $('#wise-sort').html(data.wise_fc);
            }
        });
    }
    function loadNor() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/topic/monitor/nor',
            type: 'POST',
            data: {
                date: stdate
            },
            dataType: 'json',
            success: function (data) {
                if (data.pc_nor_webpage) {
                    $('#pc-webpage').html(data.pc_nor_webpage);
                } else {
                    $('#pc-webpage').hide();
                }
                if (data.wise_fc_nor_webpage) {
                    $('#wise-webpage').html(data.wise_fc_nor_webpage);
                } else {
                    $('#wise-webpage').hide();
                }
            }
        });
    }

    function loadPcflowInfo() {
        var stdate = $('#date').val();
        var cmatch = $('#flow_buttons .cmatch.selected').attr('type-tag');
        if (cmatch === 'pc_webpage') {
            $('#flow_pc_list').show();
            $('#flow_wise_list').hide();
        } else {
            $('#flow_pc_list').hide();
            $('#flow_wise_list').show();
        }
        $.ajax({
            url: '/home/pcflow',
            dataType: 'json',
            type: 'post',
            data: {
                stdate: stdate,
                cmatch: cmatch
            },
            success: function (data) {
                if (data.all && data.all.length) {
                    $('#flow-widget-body').show();
                    $('#flow-miss-span').hide();
                    makeFlowChart(data.all);
                    if (data.list) {
                        $('#list').show();
                        $('#pcflow_div').css('width', '70%');
                        for (var i in data.list) {
                            var d = data.list[i];
                            $('#' + d.cmatch + '_list').css('height', d.percent + '%');
                            $('#' + d.cmatch + '_list span').html(d.cmatch + '(' + d.percent + '%)');
                        }
                    } else {
                        $('#pcflow_div').css('width', '100%');
                        $('#list').hide();
                    }
                } else {
                    $('#flow-widget-body').hide();
                    $('#flow-miss-span').show();
                    $('#flow-miss-date').html(stdate);
                }
            }
        });
    }
    function loadCompareInfo() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/home/compare',
            dataType: 'json',
            type: 'post',
            data: {
                stdate: stdate
            },
            success: function (data) {
                if (data.length) {
                    $('#compare-miss-span').hide();
                    $('#compare-widget-body').show();
                    makeCompareChart(data);
                } else {
                    $('#compare-miss-span').show();
                    $('#compare-miss-date').html(stdate);
                    $('#compare-widget-body').hide();
                }
            }
        });
    }

    function loadCpm1Info() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/home/cpm1',
            dataType: 'json',
            type: 'post',
            data: {
                stdate: stdate
            },
            success: function (data) {
                $('.cpm1-info').show();
                if (data.chart.length) {
                    $('#cpm1-widget-body').show();
                    $('#cpm1-miss-span').hide();
                    if (data.chart) {
                        makeCpm1Chart(data.chart);
                    }
                    if (data.table) {
                        makeCpm1Table(data.table);
                    }
                } else {
                    $('#cpm1-widget-body').hide();
                    $('#cpm1-miss-span').show();
                    $('#cpm1-miss-date').html(stdate);
                }
            }
        });
    }

    function loadChargeInfo() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/home/charge',
            dataType: 'json',
            type: 'post',
            data: {
                stdate: stdate
            },
            success: function (data) {
                $('.charge-info').show();
                if (data.chart.val) {
                    $('#charge-widget-body').show();
                    $('#charge-miss-span').hide();
                    if (data.chart) {
                        makeChargeChart(data.chart);
                    }
                    if (data.table) {
                        makeChargeTable(data.table);
                    }
                } else {
                    $('#charge-widget-body').hide();
                    $('#charge-miss-span').show();
                    $('#charge-miss-date').html(stdate);
                }
            }
        });
    }

    function makeCpm1Table(data) {
        var config = {
            paging: false,
            info: false
        };
        config['columns'] = [
            {data: 'name', className: 'table-text'},
            {data: 'quarter', className: 'table-number'},
            {data: 'quarterOnQuarter', className: 'table-number'},
            {data: 'year', className: 'table-number'},
            {data: 'value', className: 'table-number'},
            {data: 'weekToWeek', className: 'table-number'}
        ];
        config['columnDefs'] = [{
            sortable: false,
            targets: [0, 1, 2, 3, 4, 5]
        }];
        $.grid.createClientTable('kpi_cpm1_table', config, data);
    }

    function makeChargeTable(data) {
        var config = {
            paging: false,
            info: false
        };
        config['columns'] = [
            {data: 'name', className: 'table-text'},
            {data: 'per', className: 'table-number'},
            {data: 'quarter', className: 'table-number'},
            {data: 'year', className: 'table-number'},
            {data: 'value', className: 'table-number'},
            {data: 'weekToWeek', className: 'table-number'}
        ];
        config['columnDefs'] = [{
            sortable: false,
            targets: [0, 1, 2, 3]
        }];
        $.grid.createClientTable('kpi_charge_table', config, data);
    }

    function loadPageInfo() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/home/info',
            dataType: 'json',
            type: 'post',
            data: {
                stdate: stdate
            },
            success: function (data) {
                if (data.stdate) {
                    $('.pick_date').html(data.stdate);
                }
                if (data.quart) {
                    $('.pick_quarter').html(data.quart);
                }
                if (data.quPass) {
                    $('.pick_timepass').html(data.quPass);
                }
            }
        });
    }

    function loadClienttopInfo() {
        var stdate = $('#date').val();
        var type = $('#client_top_buttons .type.selected').attr('type-tag');
        var cmatch = $('#client_top_buttons .cmatch.selected').attr('type-tag');
        $.ajax({
            url: '/home/clienttop',
            dataType: 'json',
            type: 'post',
            data: {
                stdate: stdate,
                type: type,
                cmatch: cmatch
            },
            success: function (data) {
                if (data.val) {
                    $('#top-widget-body').show();
                    $('#top-miss-span').hide();
                    makeClientTopChart(data);
                } else {
                    $('#top-widget-body').hide();
                    $('#top-miss-date').html(stdate);
                    $('#top-miss-span').show();
                }
            }
        });
    }

    function loadClientBudgetInfo() {
        var stdate = $('#date').val();
        $.ajax({
            url: '/home/budget',
            dataType: 'json',
            type: 'post',
            data: {
                stdate: stdate
            },
            success: function (data) {
                if (data && data.length) {
                    $('#budget-widget-body').show();
                    $('#budget-miss-span').hide();
                    makeClientBudgetChart(data);
                } else {
                    $('#budget-widget-body').hide();
                    $('#budget-miss-span').show();
                    $('#budget-miss-date').html(stdate);
                }
            }
        });
    }

    function makeClientBudgetChart(data) {
        var chart = AmCharts.makeChart('part_clientbudget_chart', {
            'type': 'serial',
            'theme': 'light',
            'dataProvider': data,
            'startDuration': true,
            'gridAboveGraphs': true,
            'graphs': [{
                balloonText: '<div class="ball">客户数占比:[[client_num]]%<br/>当日值:[[budget_value]]%<br/>周同比:'
                    + '[[budget_weekToWeek]]%</div>',
                balloonAlpha: 1,
                fillAlphas: 1,
                lineAlpha: 0.2,
                type: 'column',
                labelText: '[[budget_value]]%',
                valueField: 'budget_value',
                colorField: 'color'
            }],
            'chartCursor': {
                categoryBalloonEnabled: false,
                cursorAlpha: 0,
                zoomable: false
            },
            'valueAexs': [{
                position: 'left',
                axisAlpha: 0,
                gridAlpha: 0
            }],
            'categoryField': 'code',
            'categoryAxis': {
                labelRotation: 30,
                autoGridCount: false,
                gridAlpha: 0,
                axisAlpha: 0
            },
            'export': {
                enabled: true
            }
        });
    }

    function makeClientTopChart(data) {
        var chart = AmCharts.makeChart('part_clienttop_chart', {
            'type': 'serial',
            'theme': 'light',
            'rotate': true,
            'categoryField': 'key',
            'startDuration': 1,
            'categoryAxis': {
                gridPosition: 'start',
                position: 'left',
                axisAlpha: 0,
                gridAlpha: 0
            },
            'valueAxes': [{
                labelsEnabled: false,
                axisAlpha: 0,
                gridAlpha: 0.2
            }],
            'trendLine': [],
            'graphs': [
                {
                    balloonText: '<div class="ball">占比:[[per]]%<br/>当日值:[[show]]<br/>周同比:'
                        + '[[weekToWeek]]%</div>',
                    balloonAlpha: 1,
                    fillAlphas: 1,
                    lineAlpha: 0.2,
                    type: 'column',
                    valueField: data.type.name,
                    labelText: '[[per]]%',
                    labelPosition: 'right',
                    labelOffset: -10,
                    colorField: 'color',
                    lineColor: data.type.color,
                    title: data.type.name
                }
            ],
            'dataProvider': data.val,
            'export': {
                enabled: true
            }
        });
    }

    function genFlowData(data, selected) {
        var chartData = [];
        for (var i = 0; i < data.length; i++) {
            if (i === selected) {
                for (var x = 0; x < data[i].sub.length; x++) {
                    var tmp = data[i].sub[x];
                    tmp['pulled'] = true;
                    tmp['color'] = data[i].color;
                    chartData.push(tmp);
                }
            } else {
                var tmp = data[i];
                tmp['id'] = i;
                chartData.push(tmp);
            }
        }
        return chartData;
    }

    function makeFlowChart(data) {
        var selected;
        var that = this;
        var chart = new AmCharts.AmPieChart();
        chart.dataProvider = genFlowData(data, selected);
        chart.titleField = 'cmatch';
        chart.valueField = 'value';
        chart.balloonText = '[[cmatch]]:[[percent]]%<br/>当日值:[[show]]<br/>'
            + '周同比:[[weekToWeek]]%';
        chart.outlineColor = '#FFFFFF';
        chart.outlineAlpha = 0.8;
        chart.outlineThickness = 2;
        chart.labelRadius = -30;
        chart.colorField = 'color';
        chart.pulledField = 'pulled';
        chart.hideLabelsPercent = 5;

        chart.path = '/js/lib/amcharts_3.14.0/images/';

        // AN EVENT TO HANDLE SLICE CLICKS
        chart.addListener('clickSlice', function (event) {
            if (event.dataItem.dataContext.id !== undefined) {
                that.selected = event.dataItem.dataContext.id;
            } else {
                that.selected = undefined;
            }
            if ((that.selected !== undefined && data[that.selected].sub !== undefined)
                || that.selected === undefined) {
                chart.dataProvider = genFlowData(data, that.selected);
                chart.validateData();
            }
        });

        // WRITE
        chart.write('part_pcflow_chart');
    }

    function makeCpm1Chart(data) {
        var option = {
            'type': 'serial',
            'theme': 'light',
            'dataProvider': data,
            'trendLine': {
                lineThickness: 0.5
            },
            'legend': {
                maxColumns: 3,
                minColumns: 3,
                position: 'top',
                useGraphSettings: false,
                valueText: false,
                spacing: 20,
                markerSize: 14,
                switchType: 'v'
            },
            'graphs': [{
                balloonText: '[[value]]',
                balloonAlpha: 1,
                bullet: false,
                title: 'OHU',
                valueField: 'ohu_value',
                lineThickness: 2,
                lineColor: '#4f81bd',
                bullet: '',
                bulletAlpha: 1
            }, {
                balloonText: '[[value]]',
                balloonAlpha: 1,
                bullet: false,
                title: '无线传统',
                valueField: 'wise_value',
                lineThickness: 2,
                lineColor: '#c0504d',
                bullet: '',
                bulletAlpha: 1
            }],
            'chartCursor': {
                cursorAlpha: 0,
                zoomable: false
            },
            'categoryField': 'stdate',
            'chartCursor': {
            },
            'export': {
                enabled: true,
                postion: 'bottom-right'
            }

        };
        var chart = AmCharts.makeChart('kpi_cpm1_chart', option);
    }

    function makeCompareChart(data) {
        var chart = AmCharts.makeChart('part_wisepc_chart', {
            'type': 'serial',
            'theme': 'light',
            'dataProvider': data,
            'trendLine': {
                lineThickness: 0.5
            },
            'legend': {
                maxColumns: 3,
                minColumns: 3,
                position: 'bottom',
                useGraphSettings: false,
                valueText: false,
                spacing: 20,
                markerSize: 14,
                switchType: 'v'
            },
            'graphs': [{
                balloonText: 'PV占比(%):[[value]]',
                bullet: '',
                bulletSize: 1,
                title: 'PV',
                valueField: 'pv',
                fillAlphas: 0,
                lineThickness: 2,
                balloonAlpha: 1,
                lineColor: '#4f81bd'
            }, {
                balloonText: '收入占比(%):[[value]]',
                bullet: '',
                bulletSize: 1,
                title: 'Charge',
                valueField: 'charge',
                fillAlphas: 0,
                lineThickness: 2,
                balloonAlpha: 1,
                lineColor: '#c0504d'
            }, {
                balloonText: 'CPM1占比(%):[[value]]',
                bullet: '',
                bulletSize: 1,
                title: 'CPM1',
                valueField: 'cpm1',
                fillAlphas: 0,
                lineThickness: 2,
                balloonAlpha: 1,
                lineColor: '#9bbb59'
            }],
            'chartCursor': {
                cursorAlpha: 0,
                zoomable: false
            },
            'categoryField': 'stdate',
            'chartCursor': {
            },
            'export': {
                enabled: true,
                postion: 'bottom-right'
            }
        });
    }

    function makeChargeChart(data) {
        var chart = AmCharts.makeChart('kpi_charge_chart', {
            'type': 'serial',
            'theme': 'light',
            'dataProvider': data.val,
            'trendLine': {
                lineThickness: 0.5
            },
            'legend': {
                maxColumns: 3,
                minColumns: 3,
                position: 'top',
                useGraphSettings: false,
                valueText: false,
                spacing: 20,
                markerSize: 14,
                switchType: 'v'
            },
            'graphs': [{
                balloonText: '[[value]]',
                bullet: '',
                bulletSize: 1,
                title: '凤巢cash',
                valueField: 'fc_cash',
                fillAlphas: 0,
                lineThickness: 2,
                balloonAlpha: 1,
                lineColor: '#4f81bd'
            }, {
                balloonText: '[[value]]',
                bullet: '',
                bulletSize: 1,
                title: 'PC网页charge',
                valueField: 'pc_charge',
                fillAlphas: 0,
                lineThickness: 2,
                balloonAlpha: 1,
                lineColor: '#c0504d'
            }, {
                balloonText: '[[value]]',
                bullet: '',
                bulletSize: 1,
                title: '无线网页charge',
                valueField: 'wise_charge',
                fillAlphas: 0,
                lineThickness: 2,
                balloonAlpha: 1,
                lineColor: '#9bbb59'
            }],
            'chartCursor': {
                cursorAlpha: 0,
                zoomable: false
            },
            'categoryField': 'stdate',
            'chartCursor': {
            },
            'export': {
                enabled: true,
                postion: 'bottom-right'
            }
        });
    }
});
