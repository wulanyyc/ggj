<?php 
use yii\web\View;
use app\components\MsaView;
use app\assets\HashAsset;

$this->title = '个人中心';

MsaView::registerJsFile($this,'/js/customer/index.js', 
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
      border-top: 1px solid #eee;
    }

    #order, #feedback, #info, #contactus {
      margin-top: .3rem;
      border-top: 1px solid #eee;
    }

    a.show-item {
      padding: 10px 15px;
      display: table;
      width: 100%;
/*      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;*/
      text-decoration: none;
      color: black;
    }

    .show-item-arrow {
      float: right;
    }

    .show-item-text {
      width: 80%;
      display: inline-block;
    }

    footer {
      display: none;
    }
</style>

<div class="card" id="userinfo" style="padding: 5px 15px;display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
  <?php if (empty($info['headimgurl'])) { ?>
  <div style="font-size: 50px;">
    <a href="/customer/info" style="color: black;"><i class="fa fa-user-circle-o" aria-hidden="true"></i></a>
  </div>
  <?php } else { ?>
  <a href="/customer/info"><img src=<?=$info['headimgurl'] ?> style="height:60px;border-radius: 5px;"/></a>
  <?php } ?>
  <div style="width:80%;">
    <?php if (!empty($info['nick'])) { ?>
    <div><?=$info['nick'] ?></div>
    <?php } else { ?>
    <div><?=$info['phone'] ?></div>
    <?php } ?>
    <div>
      余额：<span class="text-danger"><?=$info['money'] ?></span>&nbsp;
      积分：<span class="text-success"><?=$info['score'] ?></span>
    </div>
  </div>
  <!-- <div><button type="button" class="btn btn-outline-info btn-sm" id="charge">充值享优惠</button></div> -->
</div>

<div class="card" id="order" style="padding: 5px 15px;display: flex;flex-direction: row;justify-content: space-around;align-items: center;">
  <?php if ($cartid > 0) { ?>
  <a href="/cart?id=<?=$cartid ?>" style="color:black;text-decoration: none;">
    <div style="font-size: 25px;text-align: center;"><i class="fa fa-shopping-cart" aria-hidden="true"></i></div>
    <div style="font-size: 13px;text-align: center;">购物车</div>
  </a>
  <?php } else { ?>
  <a href="/buy" style="color:black;text-decoration: none;">
    <div style="font-size: 25px;text-align: center;"><i class="fa fa-shopping-cart" aria-hidden="true"></i></div>
    <div style="font-size: 13px;text-align: center;">购物车</div>
  </a>
  <?php } ?>

  <a href="/order?type=1" style="color:black;text-decoration: none;">
    <div style="font-size: 25px;text-align: center;"><i class="fa fa-credit-card" aria-hidden="true"></i></div>
    <div style="font-size: 13px;text-align: center;">待付款</div>
  </a>

  <a href="/order?type=2" style="color:black;text-decoration: none;">
    <div style="font-size: 25px;text-align: center;"><i class="fa fa-truck" aria-hidden="true"></i></div>
    <div style="font-size: 13px;text-align: center;">待收货</div>
  </a>

  <a href="/order" style="color:black;text-decoration: none;">
    <div style="font-size: 25px;text-align: center;"><i class="fa fa-shopping-bag" aria-hidden="true"></i></div>
    <div style="font-size: 13px;text-align: center;">我的订单</div>
  </a>
</div>

<a href="/customer/feedback" class="card show-item" id="feedback">
  <div class="show-item-text">售后建议</div>
  <div class="show-item-arrow"><i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
</a>

<a href="/customer/coupon" class="card show-item" id="coupon">
  <div class="show-item-text">优惠券</div>
  <div class="show-item-arrow">
    <?php if ($couponNum > 0) { ?>
    <span class="badge badge-danger"><?=$couponNum ?></span>
    <?php } ?>
    <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
  </div>
</a>

<a href="/customer/gift" class="card show-item" id="coupon">
  <div class="show-item-text">礼品券</div>
  <div class="show-item-arrow">
    <?php if ($couponNum > 0) { ?>
    <span class="badge badge-danger"><?=$couponNum ?></span>
    <?php } ?>
    <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
  </div>
</a>

<a href="/customer/score" class="card show-item" id="score">
  <div class="show-item-text">积分商场</div>
  <div class="show-item-arrow"><i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
</a>

<a href="/buy/type" class="card show-item" id="score">
  <div class="show-item-text">购买须知</div>
  <div class="show-item-arrow"><i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
</a>


<a href="/customer/info" class="card show-item" id="info">
  <div class="show-item-text">个人信息</div>
  <div class="show-item-arrow"><i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
</a>

<a href="/address" class="card show-item" id="address">
  <div class="show-item-text">地址管理</div>
  <div class="show-item-arrow"><i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
</a>

<!-- <a href="/customer/refund" class="card show-item" id="address">
  <div style="width:90%;">理赔细则</div>
  <div><i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
</a> -->

<a href="/contact" class="card show-item" id="contactus">
  <div class="show-item-text">联系我们</div>
  <div class="show-item-arrow"><i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
</a>

<?php if (!$isWechat) { ?>
<button type="button" class="btn btn-danger" id="quit" style="width:80%;margin-left: 10%;margin-top: 10px;margin-bottom: 5px;">退出</button>
<?php } else { ?>
<a class="btn btn-danger" href="/site" style="width:80%;margin-left: 10%;margin-top: 10px;margin-bottom: 5px;text-decoration: none;">逛商城</a>
<?php } ?>

