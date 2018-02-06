<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '抽奖成功';

MsaView::registerJsFile($this,'/js/prize/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
    body {
        padding-top: 0 !important;
    }

    #background {
        width:100%;
        height: 100%;
        background-image: url('/img/prize/suc2.jpeg');
        background-size: 100% 100%;
        position: fixed;
        z-index: 1;
    }
</style>

<div id="background">
    <div style="text-align: center;margin-top: 20%;">
        <img src="/img/logo.png" style="width: 20%;"/>
    </div>

    <div style="text-align:center; font-size: 18px;line-height: 28px;margin-top: 5px;">
        <div>恭喜您获得</div>
        <div style="color:red;font-size: 16px;"><?=$text ?></div>
        <div style="font-size: 14px;color:red;">【长按】或【扫描】二维码，领取到账户</div>
    </div>

    <div style="text-align: center;font-size: 16px;line-height: 24px;">
        <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?=$ticket ?>" style="width:45%;" />
        <div style="font-size: 16px;"><?=$day ?>天后可继续抽奖</span></div>
        <div>领取奖品后分享有惊喜</div>
    </div>
</div>

