$(document).ready(function () {
    $('.carousel').carousel();

    $('.first-item').click(function(e){
        e.preventDefault();
        $('.first-item').each(function(){
            $(this).removeClass('first-active');
            $(e.target).addClass('first-active');
        });

        var tag = $(this).attr('data-tag');
        $('.product-item').each(function(){
            if (tag == 'all') {
                $(this).show();
                return ;
            }

            if ($(this).hasClass(tag)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#package .card').click(function(){
        location.href= $(this).attr('data-link');
    });

    // $('.product-item-content').mouseover(function(){
    //     $(this).find('.tip-content').show();
    // });

    // $('.product-item-content').mouseout(function(){
    //     $(this).find('.tip-content').hide();
    // });
});