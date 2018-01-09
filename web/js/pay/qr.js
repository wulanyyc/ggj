$(document).ready(function () {
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text : $("#wechat_url").val(),
        width : 100,
        height : 100,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
});