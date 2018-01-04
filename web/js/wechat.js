if (document.getElementById('we_appid')) {
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: document.getElementById('we_appid').value, // 必填，公众号的唯一标识
        timestamp: document.getElementById('we_timestamp').value, // 必填，生成签名的时间戳
        nonceStr: document.getElementById('we_noncestr').value, // 必填，生成签名的随机串
        signature: document.getElementById('we_signature').value,// 必填，签名，见附录1
        jsApiList: ['closeWindow', 'hideAllNonBaseMenuItem'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });

    wx.ready(function(){
        // wx.hideAllNonBaseMenuItem();
    });

    wx.error(function(res){
        console.log(res);
    });
}
