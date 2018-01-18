<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '支付失败';

MsaView::registerJsFile($this,'/js/pay/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
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

<div class="card" id="fail">
  <div style="width:300px;margin-top:50px;margin-bottom: 30px;text-align: center;">
    <img src="/img/cry.png" />
    <div style="padding-left:10px;width:70%", 请<a href="/contact">联系客服</a></div>
  </div>

  <a href="/order?type=1" class="btn btn-outline-success btn-sm" style="width:66%;margin-top:10px;">查看订单</a>
</div>

