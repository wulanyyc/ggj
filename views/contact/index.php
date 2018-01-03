<?php 
use yii\web\View;
// use app\components\MsaView;
use app\assets\SiteAsset;

$this->title = '联系我们';

SiteAsset::register($this);
?>

<style type="text/css">
  .card {
    border-radius: 0;
    border: none;
    border-bottom: 1px solid #eee;
    border-top: 1px solid #eee;
    margin: 5px 5px;
  }

  .item {
    padding-left: 20px;
    margin-bottom: 1%;
    display: flex;
    flex-direction: row;
  }

  .label-text {
    display: block;
    min-width: 60px;
  }
</style>

<div class="card">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
    联系我们
  </div>

  <div class="item" style="margin-top: 1%;">
      <div class="label-text">客服电话：</div>
      <div>18980457783 (同微信号)</div>
  </div>

<!--       <div class="item">
      <div class="label-text">淘宝店：</div>
      <div>
        <a href="https://shop393108277.taobao.com" target="_blank" style="color:#337ab7">
        成都果果佳&nbsp;<i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
      </div>
  </div> -->

  <div class="item">
    <div class="label">微信公众号：</div>
    <div>
      <img src="/img/qrcode.jpeg" style="width: 180px;border:1px solid #dedede;"></img>
    </div>
  </div>

<!--       <div class="item">
    <div class="label-text">地址：</div>
    <div>成都市青羊区光华南三路88号万科金色领域1栋816室</div>
  </div> -->
</div>

<div class="card">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
    公司介绍
  </div>

  <div style="margin-top: 10px;margin-bottom: 10px;padding-left: 20px;padding-right: 20px;">
      <p>成都果果佳科技有限公司，成立于2017年底，致力于为广大客户提供优质且价格合理的商品。</p>
      <p>公司目前主营水果、干果等相关商品，采用预约制、零售的方式服务于普通和公司客户。以诚为本，全心全意为大家服务。</p>
      <p>公司通过官方网站和微信公众号提供商品销售、咨询服务，欢迎广大用户在平台上反馈宝贵意见。</p>
  </div>
</div>
