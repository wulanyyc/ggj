$(document).ready(function () {
    $('#pointer').click(function() {
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
                $('#pointer').css('transform','rotate(' + rotate + 'deg)');
                $('#pointer').css('-webkit-transform','rotate(' + rotate + 'deg)');
                $('#pointer').css('-moz-transform','rotate(' + rotate + 'deg)');
                $('#pointer').css('-0-transform','rotate(' + rotate + 'deg)');

                $('#pointer').css('animation', 'rotation 1s linear');
                $('#pointer').css('-moz-animation', 'rotation 1s linear');
                $('#pointer').css('-webkit-animation', 'rotation 1s linear');
                $('#pointer').css('-o-animation', 'rotation 1s linear');

                if (data.status == 'fail') {
                    bootbox.confirm({
                        message: data.msg,
                        buttons: {
                            cancel: {
                                label: '放弃领奖'
                            },
                            confirm: {
                                label: '去领奖'
                            }
                        },
                        callback: function(result){
                            if (result) {
                                location.href = "/prize/suc";
                            } else {
                                $('#pointer').css('transform','rotate(-' + rotate + 'deg)');
                                $('#pointer').css('-webkit-transform','rotate(-' + rotate + 'deg)');
                                $('#pointer').css('-moz-transform','rotate(-' + rotate + 'deg)');
                                $('#pointer').css('-0-transform','rotate(-' + rotate + 'deg)');

                                $('#pointer').css('animation', 'rotation 1s linear');
                                $('#pointer').css('-moz-animation', 'rotation 1s linear');
                                $('#pointer').css('-webkit-animation', 'rotation 1s linear');
                                $('#pointer').css('-o-animation', 'rotation 1s linear');

                                location.href = "/prize/fail";
                            }
                        }
                    });
                } else {
                    bootbox.confirm({
                        message: data.msg,
                        buttons: {
                            cancel: {
                                label: '放弃重抽'
                            },
                            confirm: {
                                label: '去领奖'
                            }
                        },
                        callback: function(result){
                            if (result) {
                                location.href = "/prize/suc";
                            } else {
                                $('#pointer').css('transform','rotate(-' + rotate + 'deg)');
                                $('#pointer').css('-webkit-transform','rotate(-' + rotate + 'deg)');
                                $('#pointer').css('-moz-transform','rotate(-' + rotate + 'deg)');
                                $('#pointer').css('-0-transform','rotate(-' + rotate + 'deg)');

                                $('#pointer').css('animation', 'rotation 2s linear');
                                $('#pointer').css('-moz-animation', 'rotation 2s linear');
                                $('#pointer').css('-webkit-animation', 'rotation 2s linear');
                                $('#pointer').css('-o-animation', 'rotation 2s linear');
                            }
                        }
                    });
                }

                $('#pointer').attr('data-valid', 0);
            }
        });

    });
});