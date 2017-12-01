$(document).ready(function () {
    $('.detail').click(function() {
        $('#detail').show();
        var id = $(this).attr('data-id');

        $.ajax({
            url: '/order/product',
            type: 'get',
            data: {
                id: id
            },
            dataType: 'html',
            success: function (data) {
                $('#table-content').html(data);
            }
        });
    });

    $('#close_detail').click(function(){
        $('#detail').hide();
    });
});