/*Sets Themed Colors Based on Themes*/

var themeprimary = getThemeColorFromCss('themeprimary');
var themesecondary = getThemeColorFromCss('themesecondary');
var themethirdcolor = getThemeColorFromCss('themethirdcolor');
var themefourthcolor = getThemeColorFromCss('themefourthcolor');
var themefifthcolor = getThemeColorFromCss('themefifthcolor');

// Gets Theme Colors From Selected Skin To Use For Drawing Charts
function getThemeColorFromCss(style) {
    var $span = $('<span></span>').hide().appendTo('body');
    $span.addClass(style);
    var color = $span.css('color');
    $span.remove();
    return color;
}

// Handle RTL SUpport for Changer CheckBox
$('#skin-changer li a').click(function () {
    $.ajax({
        url: '/home/config',
        data: {
            'current-skin': $(this).attr('rel')
        },
        type: 'POST',
        dataType: 'json',
        success: function (retval) {
            window.location.reload();
        }
    });
});

/*Loading*/
/*$(window)
    .load(function () {
        setTimeout(function () {
            $('.loading-container')
                .addClass('loading-inactive');
        }, 1000);
    });*/

/*Toggle FullScreen*/
$('#fullscreen-toggler')
    .on('click', function (e) {
        var element = document.documentElement;
        if (!$('body')
            .hasClass('full-screen')) {

            $('body')
                .addClass('full-screen');
            $('#fullscreen-toggler')
                .addClass('active');
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }

        } else {

            $('body')
                .removeClass('full-screen');
            $('#fullscreen-toggler')
                .removeClass('active');

            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }

        }
    });

/*Handles Popovers*/
var popovers = $('[data-toggle=popover]');
$.each(popovers, function () {
    $(this)
        .popover({
            html: true,
            template: '<div class="popover ' + $(this)
                .data('class') +
                '"><div class="arrow"></div><h3 class="popover-title ' +
                $(this)
                .data('titleclass') + '">Popover right</h3><div class="popover-content"></div></div>'
        });
});

var hoverpopovers = $('[data-toggle=popover-hover]');
$.each(hoverpopovers, function () {
    $(this)
        .popover({
            html: true,
            template: '<div class="popover ' + $(this)
                .data('class') +
                '"><div class="arrow"></div><h3 class="popover-title ' +
                $(this)
                .data('titleclass') + '">Popover right</h3><div class="popover-content"></div></div>',
            trigger: 'hover'
        });
});
/*Handles ToolTips*/
/*
$('[data-toggle=tooltip]').tooltip({
    html: true
});
*/

InitiateSideMenu();
InitiateWidgets();
InitiateSettings();

function InitiateSideMenu() {
    // Sidebar Toggler
    $('.sidebar-toggler').on('click', function () {
        $('#sidebar').toggleClass('hide');
        $('.sidebar-toggler').toggleClass('active');
        return false;
    });
    // End Sidebar Toggler

    // Sidebar Collapse
    var b = $('#sidebar').hasClass('menu-compact');
    $('#sidebar-collapse').on('click', function () {
        if (!$('#sidebar').is(':visible'))
            $('#sidebar').toggleClass('hide');
        $('#sidebar').toggleClass('menu-compact');
        $('.sidebar-collapse').toggleClass('active');
        b = $('#sidebar').hasClass('menu-compact');

        if ($('.sidebar-menu').closest('div').hasClass('slimScrollDiv')) {
            $('.sidebar-menu').slimScroll({destroy: true});
            $('.sidebar-menu').attr('style', '');
        }
        if (b) {
            $('.open > .submenu')
                .removeClass('open');
        } else {
            // forbid for do not use temporarily
            if ($('.page-sidebar').hasClass('sidebar-fixed')) {
                var position = 'left';
                $('.sidebar-menu').slimscroll({
                    height: 'auto',
                    position: position,
                    size: '3px',
                    color: themeprimary
                });
            }
            $('.sidebar-menu').css('overflow', '');
        }
    });
    // End Sidebar Collapse
    // Sidebar Menu Handle
    $('.sidebar-menu').on('click', function (e) {
        var menuLink = $(e.target).closest('a');
        if (!menuLink || menuLink.length == 0)
            return;
        if (!menuLink.hasClass('menu-dropdown')) {
            if (b && menuLink.get(0).parentNode.parentNode == this) {
                var menuText = menuLink.find('.menu-text').get(0);
                if (e.target != menuText && !$.contains(menuText, e.target)) {
                    return false;
                }
            }
            return;
        }
        var submenu = menuLink.next().get(0);
        if (!$(submenu).is(':visible')) {
            var c = $(submenu.parentNode).closest('ul');
            if (b && c.hasClass('sidebar-menu')) {
                return;
            }
            c.find('> .open > .submenu')
                .each(function () {
                    if (this !== submenu && !$(this.parentNode).hasClass('active')) {
                        $(this).slideUp(200).parent().removeClass('open');
                    }
                });
        }
        if (b && $(submenu.parentNode.parentNode).hasClass('sidebar-menu')) {
            return false;
        }
        $(submenu).slideToggle(200).parent().toggleClass('open');
        return false;
    });
    // End Sidebar Menu Handle
}

function InitiateWidgets() {
    $('.widget-buttons *[data-toggle="maximize"]').on('click', function (event) {
        event.preventDefault();
        var widget = $(this).parents('.widget').eq(0);
        var button = $(this).find('i').eq(0);
        var compress = 'fa-compress';
        var expand = 'fa-expand';
        if (widget.hasClass('maximized')) {
            if (button) {
                button.addClass(expand).removeClass(compress);
            }
            widget.removeClass('maximized');
            widget.find('.widget-body').css('height', 'auto');
        } else {
            if (button) {
                button.addClass(compress).removeClass(expand);
            }
            widget.addClass('maximized');
            maximize(widget);
        }
    });

    $('.widget-buttons *[data-toggle="collapse"]').on('click', function (event) {
        event.preventDefault();
        var widget = $(this).parents('.widget').eq(0);
        var body = widget.find('.widget-body');
        var button = $(this).find('i');
        var down = 'fa-plus';
        var up = 'fa-minus';
        var slidedowninterval = 300;
        var slideupinterval = 200;
        if (widget.hasClass('collapsed')) {
            if (button) {
                button.addClass(up).removeClass(down);
            }
            widget.removeClass('collapsed');
            body.slideUp(0, function () {
                body.slideDown(slidedowninterval);
            });
        } else {
            if (button) {
                button.addClass(down)
                    .removeClass(up);
            }
            body.slideUp(slideupinterval, function () {
                widget.addClass('collapsed');
            });
        }
    });

    $('.widget-buttons *[data-toggle="dispose"]').on('click', function (event) {
        event.preventDefault();
        var toolbarLink = $(this);
        var widget = toolbarLink.parents('.widget').eq(0);
        var disposeinterval = 300;
        widget.hide(disposeinterval, function () {
            widget.remove();
        });
    });
}

// Fullscreen Widget
function maximize(widgetbox) {
    if (widgetbox) {
        var windowHeight = $(window).height();
        var headerHeight = widgetbox.find('.widget-header').height();
        var setHeight = windowHeight - headerHeight;
        var curHeight = widgetbox.find('.widget-body').height();
        if (setHeight > curHeight) {
            widgetbox.find('.widget-body').height(setHeight);
        }
    }
}

/* Scroll To */
function scrollTo(el, offeset) {
    var pos = (el && el.size() > 0) ? el.offset().top : 0;
    jQuery('html,body').animate({ scrollTop: pos + (offeset ? offeset : 0) }, 'slow');
}
/*#region handle Settings*/
function InitiateSettings() {
    $('.navbar').addClass('navbar-fixed-top');
    $('.page-sidebar').addClass('sidebar-fixed');
//    $('.page-header').addClass('page-header-fixed');
    // forbid for do not use temporarily
    // Slim Scrolling for Sidebar Menu in fix state
    if (!$('.page-sidebar').hasClass('menu-compact')) {
        var position = 'left';
        $('.sidebar-menu').slimscroll({
            height: 'auto',
            position: position,
            size: '3px',
            color: themeprimary
        });
        $('.sidebar-menu').css('overflow', '');
    }

}
/*#endregion handle Settings*/

/*#region Get Colors*/

// Get colors from a string base on theme colors
function getcolor(colorString) {
    switch (colorString) {
        case ('themeprimary'):
            return themeprimary;
        case ('themesecondary'):
            return themesecondary;
        case ('themethirdcolor'):
            return themethirdcolor;
        case ('themefourthcolor'):
            return themefourthcolor;
        case ('themefifthcolor'):
            return themefifthcolor;
        default:
            return colorString;
    }
}
/*#endregion Get Colors*/
// Switch Classes Function
function switchClasses(firstClass, secondClass) {

    var firstclasses = document.getElementsByClassName(firstClass);

    for (i = firstclasses.length - 1; i >= 0; i--) {
        if (!hasClass(firstclasses[i], 'dropdown-menu')) {
            addClass(firstclasses[i], firstClass + '-temp');
            removeClass(firstclasses[i], firstClass);
        }
    }

    var secondclasses = document.getElementsByClassName(secondClass);

    for (i = secondclasses.length - 1; i >= 0; i--) {
        if (!hasClass(secondclasses[i], 'dropdown-menu')) {
            addClass(secondclasses[i], firstClass);
            removeClass(secondclasses[i], secondClass);
        }
    }

    tempClasses = document.getElementsByClassName(firstClass + '-temp');

    for (i = tempClasses.length - 1; i >= 0; i--) {
        if (!hasClass(tempClasses[i], 'dropdown-menu')) {
            addClass(tempClasses[i], secondClass);
            removeClass(tempClasses[i], firstClass + '-temp');
        }
    }
}
// Add Classes Function
function addClass(elem, cls) {
    var oldCls = elem.className;
    if (oldCls) {
        oldCls += ' ';
    }
    elem.className = oldCls + cls;
}

// Remove Classes Function
function removeClass(elem, cls) {
    var str = ' ' + elem.className + ' ';
    elem.className = str.replace(' ' + cls, '').replace(/^\s+/g, '').replace(/\s+$/g, '');
}

// Has Classes Function
function hasClass(elem, cls) {
    var str = ' ' + elem.className + ' ';
    var testCls = ' ' + cls + ' ';
    return (str.indexOf(testCls) != -1);
}

$('.fa-calendar').parent().parent().click(function () {
    $(this).children('input').focus();
});

function fagInit() {
    var screenHeight = $(window).height();
    var startTop = $(window).height() - 130;
    var endTop = screenHeight - 37;
    var initImgUrl = '/img/faq1.png';
    var hiddenImgUrl = '/img/faq4.png';
    var status = $.cookie('hime');
    if (status === 'hide') {
        $('.faqbanner-init').css('top', endTop + 'px');
        $('.faqbanner-init img').attr('src', hiddenImgUrl);
    }
    else {
        $('.faqbanner-init').css('top', startTop + 'px');
        $('.faqbanner-init img').attr('src', initImgUrl);
    }
    $('.faqbanner-init').show();
}

setTimeout(fagInit, 2000);

function openConnection(hi, hiId, webAddress) {
    function validataOS() {
        if (navigator.userAgent.indexOf('Window') > 0) {
            return 'Windows';
        } else if (navigator.userAgent.indexOf('Mac OS X') > 0) {
            return 'Mac';
        } else if (navigator.userAgent.indexOf('Linux') > 0) {
            return 'Linux';
        } else {
            return 'NUll';
        }
    }
    if (validataOS() === 'Windows') {
        var f = document.createElement('form');
        document.body.appendChild(f);
        f.setAttribute('action', 'baidu://message');
        var input = document.createElement('input');
        input.setAttribute('name', 'appid');
        input.setAttribute('value', hiId);
        input.setAttribute('type', 'hidden');
        f.appendChild(input);
        f.submit();
        document.body.removeChild(f);
    } else {
        if (typeof(webAddress) === 'undefined'
                || webAddress === null
                || webAddress === '') {
            alert('该服务号暂未支持mac');
        } else {
            window.open(webAddress);
        }
    }
    return false;
}
$('.faqbanner-init').click(function (e) {
    var screenHeight = $(window).height();
    var startTop = $(window).height() - 130;
    var endTop = screenHeight - 37;
    var initImgUrl = '/img/faq1.png';
    var scrollImgUrl = '/img/faq3.png';
    var hiddenImgUrl = '/img/faq4.png';
    var currentTop = parseInt($(this).css('top'), 10);
    if (currentTop === startTop) {
        var offset = $(this).offset();
//        var relativeX = (e.pageX - offset.left);
        var relativeY = (e.pageY - offset.top);
        if (relativeY <= 15) {
            $('.faqbanner-init img').attr('src', scrollImgUrl);
            $(this).animate({top: endTop + 'px'}, 'slow', function () {
                $('.faqbanner-init img').attr('src', hiddenImgUrl);
                $.cookie('hime', 'hide', {expires: 30, path: '/'});
            });
        }
        else {
            openConnection('MSA客服号', 'gRNM8TNa2fSi2ChT9EbW4Q',
                'http://m1-iit-webfront05.m1.baidu.com:8345/sdk/msa/chat.html');
        }
    }
    else {
        $('.faqbanner-init img').attr('src', scrollImgUrl);
        $(this).animate({top: startTop + 'px'}, 'slow', function () {
            $('.faqbanner-init img').attr('src', initImgUrl);
            $.cookie('hime', 'open', {expires: 30, path: '/'});
        });
    }
});
