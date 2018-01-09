<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '支付详情';

MsaView::registerJsFile($this,'/js/pay/qr.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\QrcodeAsset',
        ]
    ]
);

?>

<style type="text/css">
  footer {
    display: none;
  }

  .card {
      border-radius: 0;
      border: none;
      width: 100%;
  }

</style>

<input type="hidden" value="<?=$url; ?>" id="wechat_url"></input>
<div class="card" id="qrcontent" style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
    <p style="text-align: center;">微信扫码支付</p>
    <div id="qrcode" style="width:250px; height:250px;"></div>
    <a class="btn btn-danger btn-sm" style="margin-top: 20px;" href="/pay/?out_trade_no=<?=$out_trade_no ?>">支付成功后，点击查看订单状态</a>
</div>

