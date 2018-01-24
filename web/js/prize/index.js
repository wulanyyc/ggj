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
                // $('#pan').addClass('Rotation');
                
                var rotate = data.rotate;
                console.log(rotate);
                $('#pointer').css('transform','rotate(' + rotate + 'deg)');
                $('#pointer').css('-webkit-transform','rotate(' + rotate + 'deg)');
                $('#pointer').css('-moz-transform','rotate(' + rotate + 'deg)');
                $('#pointer').css('-0-transform','rotate(' + rotate + 'deg)');

                $('#pointer').css('animation', 'rotation 1s linear');
                $('#pointer').css('-moz-animation', 'rotation 1s linear');
                $('#pointer').css('-webkit-animation', 'rotation 1s linear');
                $('#pointer').css('-o-animation', 'rotation 1s linear');

                if (data.status == 'fail') {
                    $.helper.alert(data.msg);
                } else {
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
                                $('#pointer').css('transform','none');
                                $('#pointer').css('-webkit-transform','none');
                                $('#pointer').css('-moz-transform','none');
                                $('#pointer').css('-0-transform','none');
                            }
                        }
                    });
                }

                $('#zhuanpan').attr('data-valid', 0);
            }
        });

    });
});