$(document).ready(function () {
    $('#zhuanpan').click(function() {
        var valid = $(this).attr('data-valid');
        if (valid > 0) {
            return ;
        } else {
            $(this).attr('data-valid', 1);
        }

        $('#loading').show();
        $('#cover').show();

        $.ajax({
            url: '/prize/getrotate',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                $('#loading').hide();
                $('#cover').hide();

                var rotate = data.rotate;

                if (data.status == 'fail') {
                    $.helper.alert(data.msg);
                } else {
                    if (rotate == 0) {
                        $('#zhuanpan').attr('data-valid', 0);
                        bootbox.confirm({
                            message: data.msg,
                            buttons: {
                                cancel: {
                                    label: '朕要重抽'
                                },
                                confirm: {
                                    label: '朕要去领奖'
                                }
                            },
                            callback: function(result){
                                if (result) {
                                    location.href = "/prize/suc";
                                }
                                // else {
                                //     location.href = "/prize?v=" + Math.random();
                                // }
                            }
                        });
                        return ;
                    }

                    var style = document.createElement('style');
                    style.type = 'text/css';
                    var keyFrames = '\
                    @keyframes rotation{\
                        from {\
                            transform: rotate(0deg);\
                        }\
                        to {\
                            transform: rotate(' + rotate +'deg)\
                        }\
                    }\
                    @-webkit-keyframes rotation{\
                        from {\
                            -webkit-transform: rotate(0deg);\
                        }\
                        to {\
                            -webkit-transform: rotate(' + rotate +'deg)\
                        }\
                    }';

                    style.innerHTML = keyFrames;
                    document.getElementsByTagName('head')[0].appendChild(style);

                    $('#pan').css('animation', 'rotation 4s ease 0s 1 alternate forwards');
                    $('#pan').css('-webkit-animation', 'rotation 4s ease 0s 1 alternate forwards');
                    // $('#pan').css('-moz-animation', 'rotation 4s ease 0s 1 alternate forwards');
                    // $('#pan').css('-o-animation', 'rotation 4s ease 0s 1 alternate forwards');

                    $('#alert-content').html(data.msg);

                    $('#loading').hide();
                    $('#cover').delay(4500).fadeIn();
                    $('#alert').delay(4500).fadeIn();
                }

                $('#zhuanpan').attr('data-valid', 0);
            }
        });
    });

    // $('#alert-repeat').click(function(){
    //     $('#cover').hide();
    //     $('#alert').hide();
    //     location.href = "/prize?v=" + Math.random();
    // });

    $('#alert-ok').click(function(){
        location.href = "/prize/suc";
    });
});