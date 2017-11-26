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
    opacity: 0.8;
    /*margin-left: 5px;*/
    margin-bottom: 1%;
    text-align: center;
  }

  .first-item:hover {
    cursor: hand;
    opacity: 1;
    text-decoration: none;
    color: #fff;
  }

  .first-products {
    flex-wrap: wrap;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    margin-top: 8px;
  }

  .first-product {
    width:16%;
    margin-bottom: 8px;
    /*margin-right: 1%;*/
  }

  .first-active {
    border: 1px solid red;
    background-color: red;
  }

  a.first-product-content {
    text-decoration: none;color:#000;
  }

  .first-product .tip-content {
    display: none;
  }

  .first-product .tip {
    position: absolute;bottom: 0px;background-color: #53a93f;
    padding: 0;margin: 0;width:100%;
    text-align: center;color: #fff;
    opacity: 0.5;
    height:25px;
  }

  .first-product .tip-text {
    position: absolute;
    bottom: 0px;
    padding: 0;
    margin: 0;
    width:100%;
    text-align: center;
    color: #fff;
    line-height: 25px;
    text-overflow: ellipsis;
    display: hidden;
    font-size: 12px;
  }

  p.desc {
    margin-bottom: 0px;
    overflow: hidden;
    font-size: 14px;
    height: 18px;
    line-height: 18px;
    margin-top: 2px;
  }

  p.price {
    margin-bottom: 0px;
    color: red;
    font-size: 14px;
    height: 18px;
    line-height: 18px;
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

  #carouselIndicators, #carouselIndicators img {
    height: 250px;
  }

  #promotion {
    margin-top:1%; display: flex;flex-direction: row;justify-content:space-around;
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
</style>

<div id="promotion">
  <a class="promotion-item" href="#">
    <div class="promotion-item-left prom-shop-left" style="background-color: #53a93f;">
      <div class="promotion-item-left-content">
        <div class="promotion-item-left-content-top">新店特惠</div>
        <div class="promotion-item-left-content-bottom"><?=$newPromotionText ?></div>
      </div>
    </div>

    <div class="promotion-item-right prom-shop-right" style="background-color: #D0E3DC;">

    </div>
  </a>

  <a class="promotion-item" href="/special">
    <div class="promotion-item-left prom-discount-left" style="background-color: #DD182B;">
      <div class="promotion-item-left-content">
        <div class="promotion-item-left-content-top">今日特价</div>
        <div class="promotion-item-left-content-bottom"><?=$dayPromotionText ?></div>
      </div>
    </div>
    <div class="promotion-item-right prom-discount-right" style="background-color: #F3CFD3;">

    </div>
  </a>

  <a class="promotion-item" href="/package">
    <div class="promotion-item-left prom-package-left" style="background-color: #866D8D;">
      <div class="promotion-item-left-content">
        <div class="promotion-item-left-content-top">预约套餐</div>
        <div class="promotion-item-left-content-bottom">订制 高品质 享9折</div>
      </div>
    </div>
    <div class="promotion-item-right prom-package-right" style="background-color: #D5CCDB;">

    </div>
  </a>
</div>

<div class="card" style="margin:1%; border-radius: 0; border-color: #fff;">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        <span class="first-title">天天鲜果</span>
        <div class="first-items">
            <a href="#" class="first-item first-active" data-tag="all">全部</a>
            <?php foreach($tags as $tag) { ?>
              <a href="#" class="first-item" data-tag="<?=$tag['en_name'] ?>"><?=$tag['name'] ?></a>
            <?php } ?>
        </div>
    </div>
    <div class="first-products">
      <?php foreach($products as $product) { ?>
          <div class="first-product <?=$product['tag'] ?>">
            <a class="first-product-content" href="<?=$product['link'] ?>">
              <div style="position: relative;">
                <img class="card-img-top" src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" />
                <div class="tip-content">
                  <p class="tip" style="height:25px;"></p>
                  <p class="tip-text"><?=$product['slogan'] ?></p>
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
            </a>
          </div>
      <?php } ?>
    </div>
</div>

<div class="card" style="margin:1%; border-radius: 0; border-color: #fff;">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        <span class="first-title">DIY果汁</span>
    </div>

    <div id="guozhi">
      <div class="card" style="border: 1px solid #53a93f;">
          <div class="card-header" style="background-color: #53a93f;">
              <span>美容养颜，缓解宿醉</span>
          </div>
          <div>
            牛油果+西柚+水
          </div>
      </div>

      <div class="card" style="border: 1px solid #DD182B;">
          <div class="card-header" style="background-color: #DD182B;">
              <span>皮肤抗氧化，消除细纹</span>
          </div>
          <div>
            牛油果+木瓜+柠檬+水
          </div>
      </div>

      <div class="card" style="border: 1px solid #6E9BBB;">
          <div class="card-header" style="background-color: #6E9BBB;">
              <span>消除疲劳，排毒养颜</span>
          </div>
          <div>
            苹果+香蕉+蜂蜜+梨
          </div>
      </div>

      <div class="card" style="border: 1px solid #866D8D;">
          <div class="card-header" style="background-color: #866D8D;">
              <span>抗衰美容、隆胸养颜</span>
          </div>
          <div>
            木瓜+牛奶+香蕉+橙
          </div>
      </div>
    </div>
</div>
