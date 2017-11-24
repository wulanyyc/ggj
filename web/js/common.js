$(document).ready(function () {
    $('#back').click(function(){
        window.history.back();
    });

    $.cookie('width', $(window).width());
    $(window).resize(function(){
        location.reload();
    });
});