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
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
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
</style>

<input type='hidden' id="isWechat" value=<?=$isWechat ?> />

<div class="card" style="height:90%;">
  <div class="item" style="justify-content: flex-end;">
    <div class="label" style="line-height: 26px;">订单金额：</div>
    <div><span class="text-danger" id="order_money"><?=$data['pay_money'] ?></span>元</div>
  </div>
  <div class="item" style="justify-content: flex-end;padding: 0px 10px;margin-bottom: 5px;">
    <div class="label" style="line-height: 26px;">钱包：</div>
    <div><span class="text-danger" id="wallet"><?=$money ?></span>元</div>
  </div>
</div>

<div class="card" style="height:90%;">
  <div class="card-header bg-white" style="position:relative;color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        支付方式
  </div>
  <?php if ($money >= $data['pay_money']) { ?>
  <div class="item" style="padding: .75rem 1.25rem;justify-content: space-between;border-bottom: 1px solid #f5f5f5" id="wallet">
    <div style="display: flex;flex-direction: row;align-items: center;">
      <img src="/img/wallet.png" style="width:32px;height:32px;"/>
      <div class="label" style="line-height: 32px;margin-left:8px;">钱包</div>
    </div>
    <div style="font-size: 22px" class="text-danger"><i class="fa fa-check-square-o" aria-hidden="true"></i></div>
  </div>
  <?php } ?>

  <?php if ($money < $data['pay_money']) { ?>
    <div class="item pay_tool" style="border-bottom: 1px solid #f5f5f5;padding: .5rem 1.25rem;justify-content: space-between;" id="wechat" data-id='wx'>
      <div style="display: flex;flex-direction: row;align-items: center;">
        <img src="/img/wechat@2x.png" style="width:32px;height:32px;"/>
        <div class="label" style="line-height: 32px;margin-left:8px;">微信</div>
      </div>
      <div style="font-size: 22px" class="text-danger status"><i class="fa fa-circle-o" aria-hidden="true"></i></div>
    </div>

    <?php if ($isWechat == 0) { ?>
    <div class="item pay_tool" style="padding: .5rem 1.25rem;justify-content: space-between;" id="alipay" data-id="ali">
      <div style="display: flex;flex-direction: row;align-items: center;">
        <img src="/img/alipay.png" style="width:32px;height:32px;"/>
        <div class="label" style="line-height: 32px;margin-left:8px;">支付宝</div>
      </div>
      <div style="font-size: 22px" class="text-danger status"><i class="fa fa-circle-o" aria-hidden="true"></i></div>
    </div>
    <?php } ?>
  <?php } ?>
</div>

<div id="pay" style="letter-spacing: 1px;font-size: 16px;" data-id=<?=$data['id'] ?> data-pay="0">
  <span id="pay_text">需支付</span><span id="pay_price">0</span>元
</div>
