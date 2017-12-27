<?php 
use yii\web\View;
use app\components\MsaView;
use app\assets\HashAsset;

$this->title = '用户中心';

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
      border-bottom: 1px solid #eee;
      border-top: 1px solid #eee;
    }

    #order, #address {
      margin-top: .4rem;
      border-top: 1px solid #eee;
    }

    footer {
      display: none;
    }
</style>

<div class="card" id="userinfo" style="padding: 5px 15px;display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
  <div style="font-size: 60px;">
    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
  </div>
  <div style="width:60%;">
    <div><?=$info['phone'] ?></div>
    <div>
      余额：<span class="text-danger"><?=$info['money'] ?></span>&nbsp;&nbsp;
      积分：<span class="text-success"><?=$info['score'] ?></span>
    </div>
  </div>
  <div><button type="button" class="btn btn-outline-danger btn-sm" id="quit">退出</button></div>
</div>

<div class="card" id="order" style="padding: 5px 15px;display: flex;flex-direction: row;justify-content: space-around;align-items: center;">
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
  <a href="/customer/feedback" style="color:black;text-decoration: none;">
    <div style="font-size: 25px;text-align: center;"><i class="fa fa-balance-scale" aria-hidden="true"></i></div>
    <div style="font-size: 13px;text-align: center;">售后/建议</div>
  </a>
</div>

<a href="/address" class="card" id="address" style="padding: 10px 15px;display: flex;flex-direction: row;justify-content: space-between;align-items: center;text-decoration: none;color: black;">
  <div style="width:90%;">地址管理</div>
  <div><i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
</a>

<a href="/customer/coupon" class="card" id="coupon" style="padding: 10px 15px;display: flex;flex-direction: row;justify-content: space-between;align-items: center;text-decoration: none;color: black;">
  <div style="width:90%;">优惠券</div>
  <div><i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
</a>

<a href="/contact" class="card" id="coupon" style="padding: 10px 15px;display: flex;flex-direction: row;justify-content: space-between;align-items: center;color:black;text-decoration: none;">
  <div style="width:90%;">联系我们</div>
  <div><i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
</a>

<br/>
<br/>
<br/>
