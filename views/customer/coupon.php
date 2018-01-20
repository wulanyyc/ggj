<?php
use yii\web\View;
use app\components\MsaView;
use app\assets\SiteAsset;

MsaView::registerJsFile($this,'/js/customer/coupon.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

$this->title = '优惠券管理';
?>

<style type="text/css">
  .item {
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

/*  #coupon_items {
    width: 100%;
    padding:2%;
    height: 80%;
    overflow-y: scroll;
  }*/

  .coupon_item {
    margin-bottom: 2%;
    display: table;
    width: 100%;
    color: #000;
  }

  .coupon_item_content {
    display: table-cell;
    position: relative;
    width: 100%;
    vertical-align: middle;
  }

  .coupon_item_content_img {
    width: 100%;
    z-index: 1;
  }

  .coupon_item_label {
    font-size: 24px;
    line-height: 28px;
    width: 60%;
    position: absolute;
    left: 20%;
    top: 50%;
    overflow-x: hidden;
    text-align: center;
    color: #1ba93b;
  }

  .coupon_item_text {
    position: absolute;
    width: 100%;
    height: 100%;
    text-align: center;
    z-index: 2;
    top: 0;
  }

  .coupon_item_money {
    text-align: center;
    font-size: 24px;
    position: absolute;
    top: 20%;
    left: 44%;
    color: #1ba93b;
  }

  .coupon_check {
    width: 10%;
    font-size: 22px;
    text-align: center;
    display: table-cell;
    vertical-align: middle;
  }

  .coupon_item_date {
    position: absolute;
    text-align: center;
    width: 100%;
    bottom: 5%;
    font-size: 12px;
    letter-spacing: 1px;
    color: #89b0da;
  }

</style>

<div class="card" style="margin-top: 8px;">
    <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      可用券
    </div>
    <div style="padding:2%;">
      <?=$html ?>
    </div>
</div>

<div class="card" style="margin-top: 8px;">
    <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      活动券
    </div>
    <div style="padding:2%;" id="job_coupon">
      <?=$jobHtml ?>
    </div>
</div>

<br/>
<button type="button" class="btn btn-success btn-sm" id="inner_back" style="width:50%;margin-left:25%;margin-bottom: 10px;margin-top:5px;">返回</button>