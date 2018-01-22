if (document.getElementById('we_appid')) {
    wx.config({
        // debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: document.getElementById('we_appid').value, // 必填，公众号的唯一标识
        timestamp: document.getElementById('we_timestamp').value, // 必填，生成签名的时间戳
        nonceStr: document.getElementById('we_noncestr').value, // 必填，生成签名的随机串
        signature: document.getElementById('we_signature').value,// 必填，签名，见附录1
        jsApiList: ['closeWindow', 'chooseWXPay', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'hideMenuItems'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });

    wx.ready(function(){
        // wx.hideMenuItems({
        //     menuList: ['onMenuShareQQ', 'onMenuShareQZone']
        // });
        // wx.hideAllNonBaseMenuItem();
        // alert('ok');
        // setCookie('wechat', 1, 1);
        wx.onMenuShareAppMessage({
            title: '成都果果佳--开业钜惠',
            desc: '全网最低价，新鲜水果、干果，保质保量。顺丰配送，当日或隔日达', // 分享描述
            link: 'http://guoguojia.vip/prize/?share_id=' + $.cookie('openid'), // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://img.guoguojia.vip/img/ggj.jpg', // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            // dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
                alert('ok');
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
                alert('fail');
            }
        });
    });

    wx.error(function(res){
        console.log(res);
    });

    // function setCookie(cname, cvalue, exdays) {
    //     var d = new Date();
    //     d.setTime(d.getTime()+(exdays*24*60*60*1000));
    //     var expires = "expires="+d.toGMTString();
    //     document.cookie = cname + "=" + cvalue + "; " + expires;
    // }
}
