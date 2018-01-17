$(document).ready(function () {
    $('.carousel').carousel();

    $('.label-item').click(function(e){
        e.preventDefault();
        $('.label-item').each(function(){
            $(this).removeClass('label-active');
            $(e.target).addClass('label-active');
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
});