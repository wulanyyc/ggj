$(document).ready(function () {
    $('#qrcontent').css('height', $(window).height() - 55);

    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text : $("#wechat_url").val(),
        width : 300,
        height : 300,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
});