$(document).ready(function () {
    $('#back').click(function(){
        if (location.pathname == '/package/order') {
            // console.log("init:" + $.cookie('order-history-back'));
            if ($.cookie('order-history-back') < 0) {
                // console.log('sssss');
                window.history.go($.cookie('order-history-back'));
                $.cookie('order-history-back', null);
            } else {
                // console.log('yrdy');
                window.history.back();
            }
        } else {
            // console.log('test');
            window.history.back();
        }
    });

    $.cookie('width', $(window).width());
    $(window).resize(function(){
        location.reload();
    });
});