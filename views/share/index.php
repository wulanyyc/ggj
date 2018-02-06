<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '免费吃水果';

MsaView::registerJsFile($this,'/js/share/index.js', 
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

    footer {
        display: none;
    }
</style>

<div id="background">
    <div style="text-align: center;margin-top: 20%;">
        <img src="/img/logo.png" style="width: 20%;"/>
    </div>

    <div style="text-align:center; font-size: 18px;line-height: 28px;margin-top: 5px;">
        <h5>免费吃水果</h5>
        <div style="color:red;font-size: 16px;">推荐朋友圈或群，好友关注，享<?=$share ?>元返利</div>
        <div style="font-size: 16px;">好友下单后享每单金额的<?=$percent ?>%作为返利</span></div>
    </div>

    <div style="text-align: center;font-size: 16px;line-height: 24px;">
        <div style="margin-top: 10px;font-size: 16px;">推荐您的专属二维码，享以上返利</div>
        <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?=$ticket ?>" style="width:60%;" />
        <div style="margin-top: 10px;font-size: 14px;">长按二维码，下载到手机</div>
    </div>
</div>

