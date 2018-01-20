<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '支付';

MsaView::registerJsFile($this,'/js/order/pay.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
    .card {
      border-radius: 0;
      border: none;
      border-bottom: 1px solid #eee;
      border-top: 1px solid #eee;
      margin: 1% 0%;
    }

    .item {
      font-size: 16px;
      padding: 5px 10px;
    }

    footer {
      display: none;
    }

    #pay {
        position: fixed;
        height: 50px;
        line-height: 50px;
        width: 100%;
        bottom: 0px;
        z-index: 10;
        border-top: 1px solid #f5f5f5;
        color: #fff;
        text-align: center;
        background-color: #e93b3d;
    }

    .pay_tool {
      padding: .75rem 1.25rem;border-bottom: 1px solid #f5f5f5;display: table;width: 100%;
    }
</style>

<input type='hidden' id="isWechat" value=<?=$isWechat ?> />

<div class="card" style="height:90%;">
  <div class="item" style="text-align: right;">
    <span class="label" style="line-height: 26px;">订单金额：</span><span class="text-danger" id="order_money"><?=$data['pay_money'] ?></span>元
  </div>
  <div class="item" style="text-align: right;">
    <span class="label" style="line-height: 26px;">钱包：</span><span class="text-danger" id="wallet"><?=$money ?></span>元
  </div>
</div>

<div class="card" style="height:90%;">
  <div class="card-header bg-white" style="position:relative;color: #1ba93b;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        支付方式
  </div>
  <?php if ($money >= $data['pay_money']) { ?>
  <div class="item pay_tool" id="wallet">
    <div style="display: table-cell;vertical-align: middle;">
      <img src="/img/wallet.png" style="width:32px;height:32px;display: inline-block;"/>
      <div class="label" style="line-height: 32px;margin-left:8px;display: inline-block;">钱包</div>
    </div>
    <div style="font-size: 22px;display: table-cell;text-align: right;" class="text-danger"><i class="fa fa-check-square-o" aria-hidden="true"></i></div>
  </div>
  <?php } ?>

  <?php if ($money < $data['pay_money']) { ?>
    <div class="item pay_tool" id="wechat" data-id='wx'>
      <div style="display: table-cell;vertical-align: middle;">
        <img src="/img/wechat@2x.png" style="width:32px;height:32px;display: inline-block;"/>
        <div class="label" style="line-height: 32px;margin-left:8px;display: inline-block;">微信</div>
      </div>
      <div style="font-size: 22px;display: table-cell;text-align: right;" class="text-danger status"><i class="fa fa-circle-o" aria-hidden="true"></i></div>
    </div>

    <?php if ($isWechat == 0) { ?>
    <div class="item pay_tool" id="alipay" data-id="ali">
      <div style="display: table-cell;vertical-align: middle;">
        <img src="/img/alipay.png" style="width:32px;height:32px;display: inline-block;"/>
        <div class="label" style="line-height: 32px;margin-left:8px;display: inline-block;">支付宝</div>
      </div>
      <div style="font-size: 22px;display: table-cell;text-align: right;" class="text-danger status"><i class="fa fa-circle-o" aria-hidden="true"></i></div>
    </div>
    <?php } ?>
  <?php } ?>
</div>

<div id="pay" style="letter-spacing: 1px;font-size: 16px;" data-id=<?=$data['id'] ?> data-pay="0">
  <span id="pay_text">需支付</span><span id="pay_price">0</span>元
</div>
