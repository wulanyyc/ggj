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
  }

</style>

<input type="hidden" value="<?=$url; ?>" id="wechat_url"></input>
<div class="card">
    <div id="qrcode" style="width:100px; height:100px; margin-top:15px;"></div>
</div>

