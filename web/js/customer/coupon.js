$(document).ready(function () {
    $('#job_coupon .coupon_item').click(function(){
        var type = $(this).attr('data-type');
        if (type == 1) {
            $.helper.alert('系统券不可领取，需完成活动赠送');
        } else {
            var id = $(this).attr('data-id');
            $.ajax({
                url: '/customer/getcoupon',
                type: 'post',
                data: "cid=" + id,
                dataType: 'json',
                success: function (data) {
                    if (data.status == 'ok') {
                        // $.helper.alert('领取成功，可刷新');
                        location.reload();
                    } else {
                        $.helper.alert(data.msg);
                    }
                }
            });
        }
    });
});