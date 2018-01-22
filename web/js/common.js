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

    console.log(location);
    
    $('#back, #inner_back').click(function(){
        if ($('#we_appid').val() != undefined) {
            var wechat = $('#we_appid').val().length > 0 ? true : false;

            if (wechat) {
                if (/\/customer\/.+/.test(location.pathname)) {
                    location.href = '/customer/index';
                }

                if (/promotion/.test(location.pathname)) {
                    location.href = '/';
                }
            }
        }

        // console.log(location);
        console.log(location.pathname);
        if (/\/buy\/booking/.test(location.pathname)) {
            // var back = $.cookie('booking-history-back');
            // if ($.cookie('booking-history-back') < 0) {
            //     $.cookie('booking-history-back', '', { expires: -1, path: '/' });
            //     window.history.go(back);
            // } else {
            //     window.history.back();
            // }
            location.href = "/";
        } else if (/\/buy/.test(location.pathname)) {
            // var back = $.cookie('buy-history-back');
            // if ($.cookie('buy-history-back') < 0) {
            //     $.cookie('buy-history-back', '', { expires: -1, path: '/'});
            //     window.history.go(back);
            // } else {
            //     window.history.back();
            // }
            location.href = "/";
        } else if (location.pathname == '/cart'){
            $('#edit').click();
            // window.history.back();
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

    $.helper.touchdirection = function(id, func) {
        $("#" + id).on("touchstart", function(e) {
            // 判断默认行为是否可以被禁用
            if (e.cancelable) {
                // 判断默认行为是否已经被禁用
                if (!e.defaultPrevented) {
                    e.preventDefault();
                }
            }   
            startX = e.originalEvent.changedTouches[0].pageX,
            startY = e.originalEvent.changedTouches[0].pageY;
        });

        $("#" + id).on("touchend", function(e) {
            // 判断默认行为是否可以被禁用
            if (e.cancelable) {
                // 判断默认行为是否已经被禁用
                if (!e.defaultPrevented) {
                    e.preventDefault();
                }
            }

            moveEndX = e.originalEvent.changedTouches[0].pageX,
            moveEndY = e.originalEvent.changedTouches[0].pageY,
            X = moveEndX - startX,
            Y = moveEndY - startY;

            //左滑
            if ( X > 0 ) {
                Direction = 'left';
                func();
                // alert(Direction);
            }

            //右滑
            else if ( X < 0 ) {
                Direction = 'right';
                func();
                // alert(Direction);
            }

            //下滑
            else if ( Y > 0) {
                Direction = 'down';
                func();
                // alert(Direction);
            }

            //上滑
            else if ( Y < 0 ) {
                Direction = 'up';
                func();
                // alert(Direction);
            }

            //单击
            else{
                Direction = 'click';
                func();
                // alert(Direction);
            }
        });
    }
});