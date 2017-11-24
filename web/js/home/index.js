/**
 * @file  首页
 */
var bootbox = bootbox || {};
$(document).ready(function () {
    $('#product_type').select2({
        allowClear: true,
        minimumResultsForSearch: Infinity
    });
    // loadKeyProductFlowChart();
    // loadKeyProductRatioChart();
    // loadKeyProductYearChart();
    // loadProductChart();
    // 加载趋势图
    function loadKeyProductFlowChart() {
        var indexYear = $('#product-flow-form  input[name="pc_index_year"]:checked').val();
        var dayType = $('#product-flow-form  input[name="day_type"]:checked').val();
        $.load.show('#pcFlowChart');
        $.ajax({
            url: '/home/flow',
            type: 'POST',
            dataType: 'json',
            data: {
                indexYear: indexYear,
                dayType: dayType
            },
            success: function (data) {
                if (dayType === 'day') {
                    $.chart.create2yChart('pcFlowChart', data['chart'].x, data['chart'].key, data['chart'].value, true);
                    if (indexYear === 'charge') {
                        $('#source_tips').html('[organic+hao123+union]: 昨日收入: <strong>'
                        + data['day_charge'] + '</strong> &nbsp; QTD: <strong>'
                        + data['quarter_charge'] + '亿</strong> &nbsp; 收入预估值：<strong>58.64亿</strong>');
                    }else if (indexYear === 'cpm1') {
                        $('#source_tips').html('[organic+hao123+union]: 昨日CPM1: <strong>'
                        + data['day_cpm1'] + '</strong> &nbsp; QTD: <strong>'
                        + data['quarter_cpm1'] + '</strong> &nbsp; CPM1预估值：<strong>96.74</strong>');
                    }else {
                        $('#source_tips').html('');
                    }
                } else {
                    $.chart.createColumnChart('pcFlowChart', data['chart'].x, data['chart'].key,
                            data['chart'].value, true);
                    if (indexYear === 'charge') {
                        $('#source_tips').html('[organic+hao123+union]: 昨日收入: <strong>'
                        + data['day_charge'] + '</strong> &nbsp; QTD: <strong>'
                        + data['quarter_charge'] + '亿</strong> &nbsp; 收入预估值：<strong>58.64亿</strong>');
                    }else if (indexYear === 'cpm1') {
                        $('#source_tips').html('[organic+hao123+union]: 昨日CPM1: <strong>'
                        + data['day_cpm1'] + '</strong> &nbsp; QTD: <strong>'
                        + data['quarter_cpm1'] + '</strong> &nbsp; CPM1预估值：<strong>96.74</strong>');
                    }else {
                        $('#source_tips').html('');
                    }
                }
            }
        });
    }
    // 加载趋势图
    function loadKeyProductRatioChart() {
        var dayType = $('#product-ratio-form input[name="day_type"]:checked').val();
        $.load.show('#productRatioChart');
        $.ajax({
            url: '/home/ratio',
            type: 'POST',
            dataType: 'json',
            data: {
                dayType: dayType
            },
            success: function (data) {
                $.chart.create2yChart('productRatioChart', data['chart'].x, data['chart'].key,
                    data['chart'].value, true);
                $('.cpm1_tips').html(data['cpm1_tips']);
                $('.charge_tips').html(data['charge_tips']);
            }
        });
    }
    // 加载趋势图
    function loadKeyProductYearChart() {
        var indexYear = $('#product-year-form  input[name="index_year"]:checked').val();
        var dayType = $('#product-year-form  input[name="day_type"]:checked').val();
        var yearProduct = $('#product-year-form  input[name="year_product[]"]:checked').map(function () {
                return $(this).val();
            }).get().join(',');
        $.load.show('#productYearChart');
        $.ajax({
            url: '/home/year',
            type: 'POST',
            dataType: 'json',
            data: {
                yearProduct: yearProduct,
                indexYear: indexYear,
                dayType: dayType
            },
            success: function (data) {
                if (dayType === 'day') {
                    $.chart.create2yChart('productYearChart', data.x, data.key, data.value, true);
                } else {
                    $.chart.createColumnChart('productYearChart', data.x, data.key, data.value, true);
                }
            }
        });
    }
    // 加载趋势图
    function loadProductChart() {
        var dayType = $('#product-day-form  input[name="day_type"]:checked').val();
        var productType = $('#product_type').val();
        $.load.show('#productDayChart');
        $.ajax({
            url: '/home/product',
            type: 'POST',
            dataType: 'json',
            data: {
                dayType: dayType,
                productType: productType
            },
            success: function (data) {
                $.chart.create2yChart('productDayChart', data['chart'].x, data['chart'].key,
                    data['chart'].value, true, productType);
                $('#tips').html(data['msg']);
            }
        });
    }
    /**
     * 年度对比-指标选择
     */
    $('#product-year-form  input[name="index_year"]').change(function () {
        var index = $(this).val();
        if (index === 'pv') {
            $('#product-year-form  .year-index input[name="year_product[]"]').attr('disabled', true);
            $('#product-year-form  .year-index input[name="year_product[]"]').attr('checked', false);
        } else {
            $('#product-year-form  .year-index input[name="year_product[]"]').attr('disabled', false);
            $('#product-year-form  input[name="year_product[]"]').attr('checked', false);
            $('#product-year-form  input[name="year_product[]"]').attr('checked', true);
            $('#product-year-form  input[name="year_product[]"]').prop('checked', true);
        }
    });
    $('#product-year-form  input[name="year_product[]"]').change(function () {
        var length = 0;
        $('#product-year-form input:checkbox[checked="checked"]').each(function () {
            length++;
        });
        if ($(this).attr('checked') && length >= 2) {
            $(this).removeAttr('checked');
        }else if (length === 1) {
            if ($(this).attr('checked')) {
                $(this).prop('checked', true);
                bootbox.alert('走势公式项至少勾选一个');
            }else {
                $(this).attr('checked', true);
            }
        }else {
            $(this).attr('checked', true);
        }
    });
    /**
     * 流量图表 分天趋势/七天均值 切换
     */
    $('#product-flow-form  input[name="day_type"]').change(function () {
        var label = $(this).parent();
        $(label).parent().find('label').removeClass('btn-primary');
        $(label).addClass('btn-primary');
    });
    /**
     * 占比图表 分天趋势/七天均值 切换
     */
    $('#product-ratio-form  input[name="day_type"]').change(function () {
        var label = $(this).parent();
        $(label).parent().find('label').removeClass('btn-primary');
        $(label).addClass('btn-primary');
    });
    /**
     * 年度对比图表 天、月、季度切换
     */
    $('#product-year-form  input[name="day_type"]').change(function () {
        var label = $(this).parent();
        $(label).parent().find('label').removeClass('btn-primary');
        $(label).addClass('btn-primary');
    });
    /**
     * 分产品线图表 分天趋势/七天均值 切换
     */
    $('#product-day-form  input[name="day_type"]').change(function () {
        var label = $(this).parent();
        $(label).parent().find('label').removeClass('btn-primary');
        $(label).addClass('btn-primary');
    });
    /**
     * PC分流量趋势图
     */
    $('#product-flow-form').change(function () {
        loadKeyProductFlowChart();
    });
    /**
     * 移动端VS.PC端趋势图
     */
    $('#product-ratio-form').change(function () {
        loadKeyProductRatioChart();
    });
    /**
     * 核心指标各年走势图
     */
    $('#product-year-form').change(function () {
        loadKeyProductYearChart();
    });
    /**
     * 核心指标产品线分天趋势图
     */
    $('#product-day-form').change(function () {
        loadProductChart();
    });
});
