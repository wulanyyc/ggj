$(document).ready(function () {
    var system ={};
    var p = navigator.platform;
    system.win = p.indexOf("Win") == 0;
    system.mac = p.indexOf("Mac") == 0;
    system.x11 = (p == "X11") || (p.indexOf("Linux") == 0);
    if(system.win||system.mac||system.xll){
        var width = $(window).width();
        if (width <= 767) {
            $.cookie('terminal', 'wap', {path: '/'});
        } else {
            $.cookie('terminal', 'pc', {path: '/'});
        }
    }else{  
        $.cookie('terminal', 'wap', {path: '/'});
    }

    $('#back, #inner_back').click(function(){
        var wechat = (strlen($('#we_appid').val()) > 0) ? true : false;

        alert(wechat);
        if (wechat) {
            var reg = new RegExp("/\/customer\/.+/");
            if (reg.test(location.pathname)) {
                location.href = '/customer';
            }
        }

        if (location.pathname == '/buy/booking') {
            var back = $.cookie('booking-history-back');
            if ($.cookie('booking-history-back') < 0) {
                $.cookie('booking-history-back', '', { expires: -1, path: '/' });
                window.history.go(back);
            } else {
                window.history.back();
            }
        } else if (location.pathname == '/buy') {
            var back = $.cookie('buy-history-back');
            if ($.cookie('buy-history-back') < 0) {
                $.cookie('buy-history-back', '', { expires: -1, path: '/'});
                window.history.go(back);
            } else {
                window.history.back();
            }
        } else if (location.pathname == '/cart'){
            window.history.back();
        } else if ( location.pathname == '/order/pay'){
            window.history.back();
        } else {
            window.history.back();
        }
    });

    $.cookie('width', null, { path: '/' }); 
    $.cookie('width', $(window).width(), { path: '/' });

    // $(window).resize(function(){
    //     $.cookie('width', null, { path: '/' }); 
    //     $.cookie('width', $(window).width(), { path: '/' });
    //     // location.reload();
    // });

    $('#search_product').bind('keyup', function(event) {
        if (event.keyCode == 13) {
            location.href = '/search?keyword=' + $('#search_product').val();
        }
    });

    $.helper = {};
    $.helper.validatePhone = function(phone) {
        var pattern = /^1\d{10}$/;
        if (pattern.test(phone)) {
            return true;
        }  
        console.log('check mobile phone ' + phone + ' failed.');  
        return false;
    };

    $.helper.chiperPhone = function(phone) {
        var mphone = phone.substr(0, 3) + '****' + phone.substr(7);  
        return mphone;
    }

    $.helper.alert = function(message){
        bootbox.alert({
            message: message,
            buttons: {
                ok: {
                    label: '确定'
                }
            }
        });
    };

    $.helper.confirm = function(message, func){
        bootbox.confirm({
            message: message,
            buttons: {
                cancel: {
                    label: '取消'
                },
                confirm: {
                    label: '确定'
                }
            },
            callback: func
        });
    };

    $.helper.round = function(value, precise){
        var num = new Number(value);
        return num.toFixed(precise);
    };

    $.helper.copy = function(id) {
        var obj = document.getElementById(id);
        obj.select();
        document.execCommand("Copy")
    }

    $.helper.payCheck = function() {
        $('#cover').show();
        bootbox.confirm({
            message: '支付状态确认?',
            buttons: {
                cancel: {
                    label: '取消'
                },
                confirm: {
                    label: '已支付成功'
                }
            },
            callback: function(result){
                if (result) {
                    $('#cover').hide();
                    location.href = "/order?type=2";
                } else {
                    $('#pay').attr('data-process', 0);
                    $('#cover').hide();
                }
            }
        });
    }
});