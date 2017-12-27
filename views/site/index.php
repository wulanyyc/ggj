<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '首页';

MsaView::registerJsFile($this,'/js/site/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
  .alert {
    margin-bottom: 0;
  }

  .card {
    border-radius: 0;
    border: none;
    border-bottom: 1px solid #eee;
    border-top: 1px solid #eee;
    margin: 5px 5px;
  }

  .first-items {
    width:60%;
    flex-wrap: wrap;
    display: inline-flex;
    flex-direction: row;
    justify-content: flex-end;
    position: absolute;
    right: 5px;
    top: 10px;
  }

  .first-item {
    border: 1px solid #53a93f;
    padding: 5px 10px 5px 10px;
    color: #fff;
    background-color: #53a93f;
    font-size: 10px;
    -webkit-transform: scale(0.9);
    opacity: 1;
    margin-bottom: 1%;
    text-align: center;
  }

  .first-item:hover {
    cursor: hand;
    opacity: 0.9;
    text-decoration: none;
    color: #fff;
  }

  .product-items {
    flex-wrap: wrap;
    display: flex;
    flex-direction: row;
    justify-content: center;
    margin-top: 2px;
  }

  .product-item {
    width:16%;
    /*border-right: 1px solid #f5f5f5;*/
    border-bottom: 1px solid #f5f5f5;
  }

  .end {
    border-right: none;
  }

  .card-img {
    width:70%;
  }

  .first-active {
    border: 1px solid red;
    background-color: red;
  }

  a.product-item-content {
    text-decoration: none;color:#000;
  }

  .product-item .tip-content {
    display: none;
    position: absolute;bottom: 5px;background-color: #53a93f;
    width: 90%;
  }

  .product-item .tip {
    padding: 0;margin: 0;width:100%;
    text-align: center;color: #fff;
    opacity: 0.9;
    line-height: 20px;
    z-index: 100;
  }

  .product-item .tip-text {
    width:100%;
    text-align: center;
    color: #fff;
    font-size: 12px;
  }

  p.desc {
    margin-bottom: 0px;
    overflow: hidden;
    font-size: 14px;
    height: 18px;
    line-height: 18px;
    /*margin-top: 5px;*/
    padding-left: 10px;
  }

  p.price {
    margin-bottom: 0px;
    color: red;
    font-size: 14px;
    height: 18px;
    line-height: 18px;
    padding-left: 10px;
  }

  .money {
    font-size: 12px;
    -webkit-transform:scale(0.8);
  }

  .realprice {
    font-weight: bold;
    font-size: 16px;
  }

  .orignal_price {
    padding-left: 5px;
    color: #ccc;
    text-decoration: line-through;
    color: #999;
    font-size: 12px;
    font-weight: normal;
  }

  .unit {
    font-size: 12px;
    -webkit-transform:scale(0.8);
  }

  #promotion {
    margin-top:5px; display: flex;flex-direction: row;justify-content:space-around;
  }

  a.promotion-item {
    width:33%;height:150px;border-radius: 3px;display: flex;flex-direction: row;text-decoration: none;
  }

  .promotion-item-left {
    width:50%;height:100%;opacity: 0.8;display: flex;justify-content:center;align-items:center;
  }

  .promotion-item-left-content {
    height:50%;width:70%;
  }

  .promotion-item-left-content-top {
    text-align: center;font-size: 20px;color:#fff;height: 44px;line-height: 44px;border-top: 2px solid #fff;border-bottom: 2px solid #fff;font-weight: 400;width:100%;
  }

  .promotion-item-left-content-bottom {
    color:#fff;font-size:12px;line-height: 26px;padding:3px;text-align: center;
  }

  .promotion-item-right {
    width:50%;height:100%;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
  }

  #guozhi {
    margin-top: 1%;display: flex;flex-direction: row;justify-content:space-around;
  }

  #guozhi .card {
    border-radius: 0; border-color: #fff;width: 24%;margin-bottom: 2%;height:200px;
  }

  #guozhi .card .card-header {
    color: #fff;border-radius: 0;
  }

  .package-item {
    text-align: center;
  }

  .promotion-item-right img {
    height: 100%;
  }

  .label {
    position: absolute;
    right:0px;
    bottom: 0px;
    /*background-color: red;*/
    font-size:13px;
    padding: 1px 8px;
    color: #fff;
    /*opacity: 0.8;*/
    z-index: 10;
    letter-spacing: 1px;
  }

  .label a {
    /*text-decoration: none;*/
    color: #fff;
  }
</style>

<div class="alert alert-danger" role="alert" style="text-align: center;">
  试运营阶段，仅限朋友购买！
</div>
<div id="promotion">
  <a class="promotion-item" href="/buy?id=1">
    <div class="promotion-item-left prom-shop-left" style="background-color: #53a93f;">
      <div class="promotion-item-left-content">
        <div class="promotion-item-left-content-top">新店特惠</div>
        <div class="promotion-item-left-content-bottom"><?=$newPromotion['text'] ?></div>
      </div>
    </div>

    <div class="promotion-item-right prom-shop-right" style="background-color: #D0E3DC;">
      <img src="<?=$newPromotion['img'] ?>"></img>
    </div>
  </a>

  <a class="promotion-item" href="/buy?id=<?=$dayPromotion['id'] ?>">
    <div class="promotion-item-left prom-discount-left" style="background-color: #DD182B;">
      <div class="promotion-item-left-content">
        <div class="promotion-item-left-content-top">今日特价</div>
        <div class="promotion-item-left-content-bottom"><?=$dayPromotion['text'] ?></div>
      </div>
    </div>
    <div class="promotion-item-right prom-discount-right" style="background-color: #F3CFD3;">
      <img src="<?=$dayPromotion['img'] ?>"></img>
    </div>
  </a>

  <a class="promotion-item" href="/booking">
    <div class="promotion-item-left prom-package-left" style="background-color: #866D8D;">
      <div class="promotion-item-left-content">
        <div class="promotion-item-left-content-top">预约套餐</div>
        <div class="promotion-item-left-content-bottom">高品质 全场享<?=$bookingDiscount ?>折</div>
      </div>
    </div>
    <div class="promotion-item-right prom-package-right" style="background-color: #D5CCDB;">

    </div>
  </a>
</div>

<div class="card">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        <span class="first-title">天天鲜果</span>
        <div class="first-items">
            <a href="#" class="first-item first-active" data-tag="all">全部</a>
            <?php foreach($tags as $tag) { ?>
              <a href="#" class="first-item" data-tag="<?=$tag['en_name'] ?>"><?=$tag['name'] ?></a>
            <?php } ?>
        </div>
    </div>
    <div class="product-items">
      <?php foreach($products as $product) { ?>
          <div class="product-item <?=$product['border_css'] ?> <?=$product['tag'] ?>" style="position: relative;">
            <a class="product-item-content" href="/buy?id=<?=$product['id'] ?>">
              <div style="display: flex;justify-content: center;align-items: center;position: relative;flex-direction: row;">
                <img class="card-img" src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" />
                <div class="tip-content">
                  <p class="tip"><span class="tip-text"><?=$product['slogan'] ?></span></p>
                </div>
              </div>
              <p class="desc"><?=$product['name'] ?> <?=$product['desc'] ?></p>
              <p class="price">
                <span class="money">¥</span>
                <?php if ($product['price'] == $product['promotion_price']) { ?>
                  <span class="realprice">
                    <?=$product['price'] ?>
                    <span class="money">元/<?=$product['unit'] ?></span>
                  </span>
                <?php } else { ?>
                  <span class="realprice">
                    <?=$product['promotion_price'] ?>
                    <span class="money">元/<?=$product['unit'] ?></span>
                    <span class="orignal_price">¥ <?=$product['price'] ?></span>
                  </span>
                <?php } ?>
              </p>
              <br/>
              <?php if ($product['booking_status'] == 1) { ?>
              <div class="label bg-success">
                <a href="/buy/booking?id=<?=$product['id'] ?>">预约享<?=$bookingDiscount ?>折&nbsp;<i class="fa fa-hand-o-right" aria-hidden="true"></i></a>
              </div>
              <?php } ?>
            </a>
          </div>
      <?php } ?>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        <span class="first-title">特惠套餐</span>
    </div>

    <div id="guozhi">
      <div class="card" style="border: 1px solid #53a93f;">
          <div class="card-header" style="background-color: #53a93f;">
              <span>美容养颜，缓解宿醉</span>
          </div>
          <div class="package-item">
            牛油果+西柚+水
          </div>
      </div>

      <div class="card" style="border: 1px solid #DD182B;">
          <div class="card-header" style="background-color: #DD182B;">
              <span>皮肤抗氧化，消除细纹</span>
          </div>
          <div class="package-item">
            牛油果+木瓜+柠檬+水
          </div>
      </div>

      <div class="card" style="border: 1px solid #6E9BBB;">
          <div class="card-header" style="background-color: #6E9BBB;">
              <span>消除疲劳，排毒养颜</span>
          </div>
          <div class="package-item">
            苹果+香蕉+蜂蜜+梨
          </div>
      </div>

      <div class="card" style="border: 1px solid #866D8D;">
          <div class="card-header" style="background-color: #866D8D;">
              <span>抗衰美容、隆胸养颜</span>
          </div>
          <div class="package-item">
            木瓜+牛奶+香蕉+橙
          </div>
      </div>
    </div>
</div>
