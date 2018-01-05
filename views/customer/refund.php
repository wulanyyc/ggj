<?php
use yii\web\View;
use app\components\MsaView;
use app\assets\SiteAsset;

SiteAsset::register($this);

$this->title = '退款赔偿细则';
?>

<style type="text/css">
  p {
    margin-bottom: 2px;
  }

  footer {
    display: none;
  }

  .card-content {
    padding: 5px 15px;
  }

</style>

<div class="card">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      退款赔偿细则
    </div>
    <div style="padding:5%;">
      <p class="text-danger" style="font-size: 18px;">商品损坏或质量差：</p>
      <p style="font-size: 14px;">
      快递运输中出现物理损坏，请联系我们协商赔偿。<br/>
      收货后发现商品质量存在问题，请联系我们协商赔偿<span class="text-info">(水果存在特殊性，赔偿有效期为快递到货后2天内)</span>。
      </p>

      <br/>
      <p class="text-danger" style="font-size: 18px;">订单不能按时发货：</p>
      <p style="font-size: 14px;">
      由于商家缺货或者其他不可控因素导致的无法正常发货，系统按原路自动退款<span class="text-info">（例：微信支付的订单，钱退回微信）</span>。
      </p>

      <br/>
      <p class="text-danger">余额退款：</p>
      <p style="font-size: 14px;"><span class="text-info">只退充值后未消费完的余额</span>，系统赠送或参与活动获得的余额不退还，请联系我们选择具体的退款渠道<span class="text-info">（微信、支付宝）</span>。
    </div>
</div>

<br/>
<button type="button" class="btn btn-success" id="inner_back" style="width:50%;margin-left:25%;margin-bottom: 10px;margin-top:5px;">返回</button>