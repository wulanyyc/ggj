$(document).ready(function () {
    $('#zhuanpan').click(function() {
        var valid = $(this).attr('data-valid');
        if (valid > 0) {
            return ;
        } else {
            $(this).attr('data-valid', 1);
        }

        $.ajax({
            url: '/prize/getrotate',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                var rotate = data.rotate;
                // console.log(rotate);

                if (data.status == 'fail') {
                    $.helper.alert(data.msg);
                } else {
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

                    $('#pointer').css('animation', 'rotation 2s ease 0s 1 alternate forwards');
                    $('#pointer').css('-moz-animation', 'rotation 2s ease 0s 1 alternate forwards');
                    $('#pointer').css('-webkit-animation', 'rotation 2s ease 0s 1 alternate forwards');
                    $('#pointer').css('-o-animation', 'rotation 2s ease 0s 1 alternate forwards');

                    setTimeout(function(){
                            bootbox.confirm({
                                message: data.msg,
                                buttons: {
                                    cancel: {
                                        label: '重抽'
                                    },
                                    confirm: {
                                        label: '去领奖'
                                    }
                                },
                                callback: function(result){
                                    if (result) {
                                        location.href = "/prize/suc";
                                    } else {
                                        // $('#pointer').css('transform','none');
                                        // $('#pointer').css('-webkit-transform','none');
                                        // $('#pointer').css('-moz-transform','none');
                                        // $('#pointer').css('-0-transform','none');
                                        // location.reload();
                                        location.href = "/prize?v=" + Math.random();
                                    }
                                }
                            });
                        },
                        2500
                    );
                }

                $('#zhuanpan').attr('data-valid', 0);
            }
        });
    });
});