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

$this->title = '礼品券';
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

  .gift_item {
    padding: 1% 5%;
    font-size: 18px;
  }

  .gift_item_content {
    display: inline-block;
    text-align: left;
    /*width: 80%;*/
  }

</style>

<div class="card" style="margin-top: 8px;">
    <div class="card-header bg-white" style="color: red;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      已领券&nbsp;<span style="font-size: 14px;">(购物中直接使用)</span>
    </div>
    <div style="padding:2%;">
      <?=$html ?>
    </div>
</div>

<br/>
<button type="button" class="btn btn-success btn-sm" id="inner_back" style="width:50%;margin-left:25%;margin-bottom: 10px;margin-top:5px;">返回</button>