$(document).ready(function () {
    $('#pointer').click(function() {
        var valid = $(this).attr('data-valid');
        if (valid > 0) {
            return ;
        } else {
            $(this).attr('data-valid', 1);
            // $('#pointer').css('transform','rotate(0deg)');
            // $('#pointer').css('-webkit-transform','rotate(0deg)');
            // $('#pointer').css('-moz-transform','rotate(0deg)');
            // $('#pointer').css('-0-transform','rotate(0deg)');
        }

        $.ajax({
            url: '/prize/getrotate',
            type: 'post',
            dataType: 'html',
            success: function (data) {
                if (data == 0) {
                    bootbox.confirm({
                        message: '3次机会已用完，本次奖品是...?',
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
                                // location.href = "/prize/fail";
                                $('#pointer').css('transform','rotate(0deg)');
                                $('#pointer').css('-webkit-transform','rotate(0deg)');
                                $('#pointer').css('-moz-transform','rotate(0deg)');
                                $('#pointer').css('-0-transform','rotate(0deg)');

                                $('#pointer').css('animation', 'rotation 1s linear');
                                $('#pointer').css('-moz-animation', 'rotation 1s linear');
                                $('#pointer').css('-webkit-animation', 'rotation 1s linear');
                                $('#pointer').css('-o-animation', 'rotation 1s linear');

                                alert('fail');
                            }
                        }
                    });
                } else {
                    var rotato = data;
                    $('#pointer').css('transform','rotate(' + rotato + 'deg)');
                    $('#pointer').css('-webkit-transform','rotate(' + rotato + 'deg)');
                    $('#pointer').css('-moz-transform','rotate(' + rotato + 'deg)');
                    $('#pointer').css('-0-transform','rotate(' + rotato + 'deg)');

                    $('#pointer').css('animation', 'rotation 1s linear');
                    $('#pointer').css('-moz-animation', 'rotation 1s linear');
                    $('#pointer').css('-webkit-animation', 'rotation 1s linear');
                    $('#pointer').css('-o-animation', 'rotation 1s linear');

                    bootbox.confirm({
                        message: '还剩1次机会，本次奖品是...?',
                        buttons: {
                            cancel: {
                                label: '再来一次'
                            },
                            confirm: {
                                label: '去领奖'
                            }
                        },
                        callback: function(result){
                            if (result) {
                                location.href = "/prize/suc";
                            } else {
                                $('#pointer').css('transform','rotate(0deg)');
                                $('#pointer').css('-webkit-transform','rotate(0deg)');
                                $('#pointer').css('-moz-transform','rotate(0deg)');
                                $('#pointer').css('-0-transform','rotate(0deg)');

                                $('#pointer').css('animation', 'rotation 1s linear');
                                $('#pointer').css('-moz-animation', 'rotation 1s linear');
                                $('#pointer').css('-webkit-animation', 'rotation 1s linear');
                                $('#pointer').css('-o-animation', 'rotation 1s linear');
                                // location.href = "/prize/fail";

                                alert('fail');
                            }
                        }
                    });
                }

                $('#pointer').attr('data-valid', 0);
            }
        });

    });
});