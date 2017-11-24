;(function ($, undefined) {
    $.load = {};
    $.load.show = function (element) {
        $(element).plainOverlay({
            opacity: 0.1,
            duration: 0,
            progress: function () {
                return $('<img src="/js/lib/overlay/loading-big.gif" alt="加载中..."/>');
//                return $('<img src="/js/lib/overlay/loading-bars.svg" alt="加载中..."/>');
            }
        });
        
        $(element).plainOverlay('show');
    };

    $.load.hide = function (element) {
        $(element).plainOverlay('hide');
    };
})(jQuery);