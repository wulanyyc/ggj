<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '好友详情';

MsaView::registerJsFile($this,'/js/friend/index.js', 
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
      border-bottom: 1px solid #eee;
      border-top: 1px solid #eee;
  }

  .show {
    font-size: 15px;
    margin-bottom: 3px;
    letter-spacing: 1px;
  }

</style>

<div class="card" id="suc">
  <div class="card-header bg-white" style="text-align:center;position:relative;color: black;border-radius: 0;border-bottom: 1px solid #f5f5f5;">
        好友购物详情
  </div>
  <div style="display: -webkit-flex;flex-direction: -webkit-column;justify-content: -webkit-center;align-items: -webkit-center;display: flex;flex-direction: column;justify-content: center;align-items: center;padding: 10px;margin-top:30px;">
      <img src='/img/gift.png' style="width:90px;height:90px;margin-bottom: 10px;"/>
      <div class="show">好&nbsp;&nbsp;友:&nbsp;&nbsp;<?=$data['userphone'] ?></div>
      <div class="show">使用您的手机号作为优惠码</div>
      <div class="show">购买了新鲜水果</div>
      <div class="show">获得了<span class="text-danger"><?=$data['discount_fee'] ?></span>元折扣</div>
      <div class="show">特奖励<span class="text-danger"><?=round($data['discount_fee'] * 0.5, 1) ?></span>元到你的钱包</div>
      <a href="/customer" class="btn btn-danger btn-sm" id="order" style="width:66%;max-width:300px;margin-top:20px;">查看我的钱包</a>
  </div>
  <br/>
</div>


