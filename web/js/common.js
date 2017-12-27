$(document).ready(function () {
    $('#back').click(function(){
        console.log(location.pathname);
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
            // $("#edit").click();
            window.history.back();
        } else if ( location.pathname == '/order/pay'){
            window.history.back();
            // location.href = '/cart?id=' + $('#cid').val() + '&oid=' + $('#oid').val();
        } else {
            window.history.back();
        }
    });

    $.cookie('width', null, { path: '/' }); 
    $.cookie('width', $(window).width(), { path: '/' });

    $(window).resize(function(){
        $.cookie('width', null, { path: '/' }); 
        $.cookie('width', $(window).width(), { path: '/' });
        // location.reload();
    });

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
        return Math.round(value * 10 * precise) / (10 * precise);
    };

    $.helper.copy = function(id) {
        var obj = document.getElementById(id);
        obj.select();
        document.execCommand("Copy")
    }
});