$(document).ready(function () {
    $('#type_content').css('height', $(window).height() - 55);
    $('.choose_type').click(function() {
        var type = $(this).attr('data-type');
        var url = $(this).attr('data-href');

        $.cookie('order_type', type, { path: '/', expires: 365 });
        location.href = url;
    });
});

