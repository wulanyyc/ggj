<?php
use yii\web\View;
use app\components\MsaView;
use app\assets\SiteAsset;

SiteAsset::register($this);

$this->title = '优惠券管理';
?>

<style type="text/css">
  .item {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    margin-bottom: 5px;
    font-size: 14px;
  }

  .item .label {
    width: 70px;
  }

  p {
    margin: 0;
  }

  footer {
    display: none;
  }

  .card-content {
    padding: 5px 15px;
  }

  #coupon_items {
    width: 100%;padding:2%;
  }

  .coupon_item {
    display: flex;flex-direction: row;justify-content: space-around;
    align-items: center;border: 1px dashed #ccc;padding: 2%;width:100%;
    margin-bottom: 2%;
  }

  .coupon_item_label {
    background-color: #53a93f;border-radius: 5px;font-size: 14px;width:56px;text-align: center;color:white;padding:5px 8px;
  }

  .coupon_item_text {
    margin-left: 2%;
  }

  .coupon_item_money {
    text-align: center;font-size: 28px;
  }

  .coupon_check {
    width: 40px;
    font-size: 22px;
    text-align: center;
  }

</style>

<div class="card">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      使用规则
    </div>
    <div style="padding:5%;">
      通用券：购买时可直接使用。<br/>
      任务券：需要完成具体任务后系统赠送。
    </div>
</div>

<div class="card" style="margin-top: 8px;">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      通用券
    </div>
    <div style="padding:2%;">
      <?=$html ?>
    </div>
</div>

<div class="card" style="margin-top: 8px;">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      任务券
    </div>
    <div style="padding:2%;">
      <?=$jobHtml ?>
    </div>
</div>

<br/>
<button type="button" class="btn btn-success" id="inner_back" style="width:50%;margin-left:25%;margin-bottom: 10px;margin-top:5px;">返回</button>