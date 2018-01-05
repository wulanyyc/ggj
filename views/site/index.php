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
    margin: 3px 5px;
  }

  .first-rec {
    color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;
    display: flex;flex-direction: row;justify-content: space-between;align-items: center;
  }

  .first-items {
    width:60%;
    flex-wrap: wrap;
    display: inline-flex;
    flex-direction: row;
    justify-content: flex-end;
  }

  .first-item {
    border: 1px solid #53a93f;
    padding: 3px 5px;
    color: #fff;
    background-color: #53a93f;
    /*opacity: 1;*/
    /*margin-left: 1%;*/
    text-align: center;
    font-size: 12px;
    transform: scale(0.9);
  }

  .first-item:hover {
    cursor: hand;
    opacity: 0.9;
    text-decoration: none;
    color: #fff;
  }

  .first-active {
    border: 1px solid red;
    background-color: red;
  }

  .product-items {
    flex-wrap: wrap;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: stretch;
    margin-top: 5px;
  }

  .product-item {
    width: 24%;
    border: 1px solid #f5f5f5;
    margin-bottom: 5px;
  }

  a.product-item-content {
    text-decoration: none;color:#000;
  }

  .product-item .tip-content {
    background-color: #53a93f;
    width: 100%;
    padding: 5px;
    text-align: center;
    color: #fff;
    line-height: 20px;
    height: 30px;
    font-size: 12px;
  }

  p.desc {
    margin-bottom: 0px;
    font-size: 14px;
    height: 18px;
    line-height: 18px;
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

  .slogan {
    font-size: 14px;
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

  #package {
    margin-top: 5px;display: flex;flex-direction: row;
    justify-content: space-between;
    align-items: stretch;
    flex-wrap: wrap;
  }

  #package .card {
    border-radius: 0;width: 32%;margin-bottom: 2%;cursor: pointer;
  }

  #package .card-header {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    color: #fff;border-radius: 0;
  }

  .package-item {
    height: auto;
  }

  .package-item-products{
    display: flex;flex-direction: row;
    justify-content: space-between;
    flex-wrap: wrap;
    color:black;
    text-decoration: none;
  }

  .package-item-product{
    width: 48%;
  }

  .promotion-item-right img {
    height: 100%;
  }

  .label {
    position: absolute;
    right:0px;
    bottom: 0px;
    font-size:13px;
    padding: 1px 8px;
    color: #fff;
    z-index: 10;
    letter-spacing: 1px;
  }

  .label a {
    color: #fff;
  }

  .style_1 {
    border: 1px solid #53a93f;
  }

  .header_1 {
    background-color: #53a93f;
  }

  .border_1 {
    border-top: 1px solid #53a93f;
  }

  .style_2 {
    border: 1px solid #DD182B;
  }

  .header_2 {
    background-color: #DD182B;
  }

  .border_2 {
    border-top: 1px solid #DD182B;
    /*background-color: #DD182B;*/
  }

  .style_3 {
    border: 1px solid #866D8D;
  }

  .header_3 {
    background-color: #866D8D;
  }

  .border_3 {
    border-top: 1px solid #866D8D;
    /*background-color: #866D8D;*/
  }

  .package-price {
    position: absolute;bottom:0px;
    width:100%;
    display: flex;flex-direction: row;
    justify-content: space-between;
    align-items: baseline;
    height:40px;
    line-height: 40px;
  }
</style>

<?php if (!empty(Yii::$app->params['hometip'])) { ?>
<div class="alert alert-danger" role="alert" style="text-align: center;">
    <?=Yii::$app->params['hometip'] ?>
</div>
<?php } ?>

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

  <a class="promotion-item" href="/booking" style="display: none">
    <div class="promotion-item-left prom-package-left" style="background-color: #866D8D;">
      <div class="promotion-item-left-content">
        <div class="promotion-item-left-content-top">优质预约</div>
        <div class="promotion-item-left-content-bottom">绝对新鲜 全场享<?=$bookingDiscount ?>折</div>
      </div>
    </div>
    <div class="promotion-item-right prom-package-right" style="background-color: #D5CCDB;">

    </div>
  </a>
</div>

<div class="card">
    <div class="card-header bg-white first-rec">
        <span class="first-title">天天鲜果</span>
        <div class="first-items">
            <a href="#" class="first-item first-active" data-tag="all">全部</a>
            <?php foreach($tags as $tag) { ?>
              <a href="#" class="first-item" data-tag="<?=$tag['en_name'] ?>"><?=$tag['name'] ?></a>
            <?php } ?>
        </div>
    </div>
    <div class="product-items">
      <?php foreach($fruits as $product) { ?>
          <div class="product-item <?=$product['tag'] ?>" style="position: relative;">
            <?php if ($product['num'] > 0) { ?>
            <a class="product-item-content" style="display: flex;justify-content: space-between;align-items: stretch;flex-direction: column;" href="/buy?id=<?=$product['id'] ?>">
            <?php } else { ?>
            <a class="product-item-content" style="display: flex;justify-content: space-between;align-items: stretch;flex-direction: column;" href="/buy/booking?id=<?=$product['id'] ?>">
            <?php } ?>
              <div style="display: flex;justify-content: center;align-items: center;flex-direction: row;padding:5%;">
                <img class="card-img" style="width:40%;" src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" />
                <div style="width:45%;">
                  <p class="desc"><?=$product['name'] ?></p>
                  <p class="desc"><?=$product['desc'] ?></p>
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
                </div>
              </div>
              <br/>

              <div class="tip-content" style="position: absolute;bottom: 0px;">
                  <?=$product['slogan'] ?>
              </div>
            </a>
          </div>
      <?php } ?>
    </div>
</div>

<?php if (count($packages) > 0){ ?>
<div class="card">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        <span class="first-title">特惠套餐</span>
    </div>

    <div id="package">
      <?php foreach($packages as $item) { ?>
      <div class="card style_<?=$item['index'] ?>" data-link="/buy?id=<?=$item['id'] ?>" style="display: block;">
          <div class="card-header header_<?=$item['index'] ?>">
              <span><?=$item['name'] ?></span>
              <span><?=$item['slogan'] ?></span>
          </div>
          <div class="package-item">
            <div class="package-item-products">
              <?php foreach($item['list'] as $product) { ?>
              <div class="package-item-product" style="display: flex;flex-direction: row;justify-content: space-around;align-items: center;font-size:14px;">
                <img src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" style="height:60px;"/>
                <span style="display: inline-block;width:90px;"><?=$product['name'] ?></span>
                <span><?=$product['num'] ?><?=$product['unit'] ?></span>
              </div>
              <?php } ?>
            </div>
            <br/>
            <br/>
            <div class="package-price border_<?=$item['index'] ?>" style="border-top: 1px solid #f5f5f5;">
              <a href="/buy/booking?id=<?=$item['id'] ?>" style="font-size: 13px;color:#53a93f;text-decoration: none;">&nbsp;&nbsp;<i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;预约享<?=$bookingDiscount ?>折</a>
              <div>
                <span style="font-size: 13px;"><?=$item['desc'] ?></span>
                <span style="font-size: 13px;padding-right: 8px;">
                  <span style="font-size: 18px;padding-left: 10px;font-weight: bold;color:red"><?=$item['price'] ?></span>&nbsp;元
                </span>
              </div>
            </div>
          </div>
      </div>
      <?php } ?>
    </div>
</div>
<?php } ?>
