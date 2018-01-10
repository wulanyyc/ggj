$(document).ready(function () {
    $('#all_address_info').css('height', $(window).height() - 56);

    $('#close_address, #close_address_bottom').click(function(){
        $('#address_info').hide();
        $('body').removeClass('forbid');
        $('#cover').hide();
    });

    $('#label_add').click(function(){
        $(this).hide();
        $('#label_add_input').show();
    });

    $('#label_add_input_ok').click(function(){
        if ($('#label_add_text').val().length > 0) {
            $('.label_choose').removeClass('active');
            $('#label_add').before('<div class="label_choose active">' + $('#label_add_text').val() + '</div>');
            $('#label_add_input').hide();
            $('#label_add_text').val('');
            $('#label_add').show();
        }
    });

    $('#label_add_group').delegate('.label_choose', 'click', function() {
        $('.label_choose').each(function() {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
    });

    function initEditAddress(id) {
        $.ajax({
            url: '/address/info',
            type: 'get',
            dataType: 'json',
            data: "id=" + id,
            success: function (ret) {
                if (ret.status != 'ok') {
                    $.heler.alert(ret.msg);
                    return ;
                }

                var data = ret.data;
                document.getElementById("address_form").reset();
                $('#rec_name').val(data.rec_name);
                $('#phone').val(data.rec_phone);
                $('#rec_city').val(data.rec_city);
                $('#rec_district').val(data.rec_district);
                $('#rec_detail').val(data.rec_detail);
                if (data.label.length > 0) {
                    $('.label_choose').removeClass('active');
                    if (data.label != '家' && data.label != '公司' && data.label != '学校') {
                        $('#label_add').before('<div class="label_choose active">' + data.label + '</div>');
                    } else {
                        $('.label_choose').each(function(){
                            if ($(this).html() == data.label) {
                                $(this).addClass('active');
                            }
                        });
                    }
                }
            }
        });
    }

    function reloadAddress() {
        $.ajax({
            url: '/address/html',
            type: 'get',
            dataType: 'json',
            success: function (data) {
                $('#all_address_items').html(data.data);
            }
        });
    }

    $('#save_address').click(function(){
        var label = '';
        if ($('.label_choose.active').html() != undefined) {
            label = $('.label_choose.active').html();
        }

        if (!$.helper.validatePhone($('#phone').val())){
            $.helper.alert('收件人手机号码格式不正确');
            return ;
        }

        $.ajax({
            url: '/address/add',
            type: 'post',
            dataType: 'json',
            data: $('#address_form').serialize()+"&label=" + label,
            success: function (data) {
                if (data.status == 'ok') {
                    $('#edit_address_id').val('');
                    $('#close_address').click();
                    reloadAddress();
                } else {
                    $.helper.alert(data.msg);
                    $('#cover').hide();
                }
            }
        });
    });

    $('#inner_add_address').click(function(){
        $('#address_info').show();
        document.getElementById("address_form").reset();
        $('#cover').show();
        $('body').addClass('forbid');
        $('#edit_address_id').val('');
    });

    $('#all_address_items').delegate('.edit_address_item', 'click', function(){
        var id = $(this).attr('data-id');
        $('#edit_address_id').val(id);
        initEditAddress(id);

        $('#address_info').show();
        $('#cover').show();
        $('body').addClass('forbid');
    });

    $('#all_address_items').delegate('.del_address_item', 'click', function(){
        var id = $(this).attr("data-id");
        $.helper.confirm('确认删除改地址?', function (result) {
            if (result) {
                $.ajax({
                    url: '/address/del',
                    type: 'post',
                    dataType: 'json',
                    data: "id=" + id,
                    success: function (data) {
                        if (data.status == 'ok') {
                            reloadAddress();
                        } else {
                            $.helper.alert(data.msg);
                        }
                    }
                });
            }
        });
    });
});