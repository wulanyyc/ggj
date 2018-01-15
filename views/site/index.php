<?php
use Yii;
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
 
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
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
    text-align: center;
    font-size: 14px;
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

    margin-top: 5px;
  }

  .product-item {
    width: 33%;
    border: 1px solid #f5f5f5;
    margin-bottom: 5px;
  }

  a.product-item-content {
    text-decoration: none;color:#000;

    display: flex;
    justify-content: space-between;
    /*align-items: stretch;*/
    flex-direction: column;
  }

  .product-item .tip-content {
    background-color: #53a93f;
    width: 100%;
    padding: 5px;
    text-align: center;
    color: #fff;
    line-height: 20px;
    height: 30px;
    font-size: 16px;
  }

  .title {
    margin-bottom: 4px;
    font-size: 16px;
    height: 18px;
    line-height: 18px;
    padding-left: 10px;
  }

  .desc {
    margin-bottom: 4px;
    font-size: 14px;
    height: 16px;
    line-height: 16px;
    padding-left: 10px;
  }

  .price {
    margin-bottom: 6px;
    color: red;
    font-size: 15px;
    height: 20px;
    padding-left: 10px;

    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: flex-start;
    flex-wrap: wrap;
  }

  .sale-badge {
    font-weight:bold;
    border-radius: 3px;
    font-size: 12px;
    padding: 0px 4px;
    margin-right: 5px;
    line-height: 18px;
  }

  .slogan {
    font-size: 16px;
    padding-left: 10px;
  }

  .money {
    font-size: 12px;
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
    font-size: 14px;
    font-weight: normal;
  }

  .unit {
    font-size: 14px;
    -webkit-transform:scale(0.8);
  }

  #promotion {
    margin-top:5px;

    display: flex;
    flex-direction: row;
    justify-content:space-around;
  }

  a.promotion-item {
    width:49%;height:150px;
    border-radius: 3px;
    text-decoration: none;

    display: flex;
    flex-direction: row;
  }

  .promotion-item-left {
    width:50%;height:100%;opacity: 0.8;

    display: flex;
    justify-content:center;
    align-items:center;
  }

  .promotion-item-left-content {
    height:50%;width:70%;
  }

  .promotion-item-left-content-top {
    text-align: center;font-size: 20px;color:#fff;
    height: 44px;line-height: 44px;
    border-bottom: 2px solid #fff;font-weight: 400;width:100%;
  }

  .promotion-item-left-content-bottom {
    color:#fff;font-size:14px;line-height: 26px;padding:3px;text-align: center;
  }

  .promotion-item-right {
    width:50%;height:100%;

    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
  }

  #package {
    margin-top: 5px;

    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: stretch;
    flex-wrap: wrap;
  }

  #package .card {
    border-radius: 0;width: 32%;margin-bottom: 2%;
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
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    flex-wrap: wrap;

    color:black;
    text-decoration: none;
  }

  .package-item-product{
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
    flex-wrap: nowrap;

    font-size:14px;
    width: 48%;
  }

  .promotion-item-right img {
    height: 80%;
  }

  .label {
    position: absolute;
    right:0px;
    bottom: 0px;
    font-size:14px;
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
    justify-content: flex-end;
    align-items: center;

    height:40px;
    line-height: 40px;
    border-top: 1px solid #f5f5f5;
  }

  .product-item-content-inner {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: row;
    flex-wrap: nowrap;

    padding:3%;height:90%;
  }

  .discount {
    font-size: 12px;padding-left:5px;padding-top: 2px;
  }

  .product-card-img {
    width: 40%;
  }

  .product-card-content {
    width: 58%;
  }
</style>

<?php if (strlen($homeTip) > 0) { ?>
<div class="alert alert-danger" role="alert" style="text-align: center;">
    <?=$homeTip ?>
</div>
<?php } ?>

<div id="promotion">
  <a class="promotion-item" href="<?=$newPromotion['link'] ?>">
    <div class="promotion-item-left" style="background-color: #53a93f;">
      <div class="promotion-item-left-content">
        <div class="promotion-item-left-content-top">果果特惠</div>
        <div class="promotion-item-left-content-bottom"><?=$newPromotion['text'] ?></div>
      </div>
    </div>

    <!--D0E3DC-->
    <div class="promotion-item-right" style="background-color: #D0E3DC;">
      <img src="<?=$newPromotion['img'] ?>"></img>
    </div>
  </a>

  <a class="promotion-item" href="<?=$dayPromotion['link'] ?>">
    <div class="promotion-item-left" style="background-color: #DD182B;">
      <div class="promotion-item-left-content">
        <div class="promotion-item-left-content-top">今日特价</div>
        <div class="promotion-item-left-content-bottom"><?=$dayPromotion['text'] ?></div>
      </div>
    </div>
    <div class="promotion-item-right" style="background-color: #F3CFD3;">
      <img src="<?=$dayPromotion['img'] ?>"></img>
    </div>
  </a>

<!--   <a class="promotion-item" href="/vip/">
    <div class="promotion-item-left" style="background-color: #866D8D;">
      <div class="promotion-item-left-content">
        <div class="promotion-item-left-content-top">无忧套餐</div>
        <div class="promotion-item-left-content-bottom">私人定制 果品优选</div>
      </div>
    </div>
    <div class="promotion-item-right" style="background-color: #D5CCDB;">
      <img src="/img/booking.jpeg"></img>
    </div>
  </a> -->
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
            <a class="product-item-content" href="<?=$product['link'] ?>">
              <div class="product-item-content-inner">
                <img class="product-card-img" src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" />
                <div class="product-card-content">
                  <p class="title"><?=$product['name'] ?></p>
                  <p class="desc"><?=$product['desc'] ?></p>

                  <?php if (isset($product['booking_price'])) { ?>
                  <div class="price">
                    <div class="sale-badge" style="border:1px solid #866D8D;color:#866D8D;">预约</div>
                    <div>
                      <span class="money" style="color:#866D8D;">¥</span>
                      <span class="realprice" style="color:#866D8D;">
                        <?=$product['booking_price'] ?>
                        <span class="money">元/<?=$product['unit'] ?></span>
                      </span>
                    </div>

                    <div class="discount">
                      <?php if ($product['booking_price'] < $product['price']) { ?>
                      <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                      <?=round($product['booking_price']/$product['price'], 2) * 10 ?>折
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>

                  <?php if (isset($product['buy_price'])) { ?>
                  <div class="price">
                    <div class="sale-badge" style="border:1px solid red;">现售</div>
                    <div>
                      <span class="money">¥</span>
                      <span class="realprice">
                        <?=$product['buy_price'] ?>
                        <span class="money">元/<?=$product['unit'] ?></span>
                      </span>
                    </div>
                    <div class="discount">
                      <?php if ($product['buy_price'] < $product['price']) { ?>
                      <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                      <?=round($product['buy_price']/$product['price'], 2) * 10 ?>折
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div>

              <div class="tip-content">
                  <?=$product['slogan'] ?>
              </div>

              <img src="http://img.guoguojia.vip/img/xx2.png" style="position: absolute;top:0;right:0;width:80px;" />
              <img src="http://img.guoguojia.vip/img/xiandu.png" style="position: absolute;top:0;left:0;width:60px;" />
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
      <a class="card style_<?=$item['index'] ?>" href="<?=$item['link'] ?>" style="display: block;text-decoration: none;color:black;">
          <div class="card-header header_<?=$item['index'] ?>">
              <span><?=$item['name'] ?></span>
              <span><?=$item['slogan'] ?></span>
          </div>
          <div class="package-item">
            <div class="package-item-products">
              <?php foreach($item['list'] as $product) { ?>
              <div class="package-item-product">
                <img src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" style="height:60px;"/>
                <span style="display: inline-block;width: 70px;overflow-x: hidden;"><?=$product['name'] ?></span>
                <span style="padding-left: 5px;"><?=$product['num'] ?><?=$product['unit'] ?></span>
              </div>
              <?php } ?>
            </div>
            <br/>
            <br/>
            <div class="package-price border_<?=$item['index'] ?>">
                <span style="font-size: 14px;padding-right: 16px;"><?=$item['desc'] ?></span>
                <?php if (isset($item['booking_price'])) { ?>
                <div class="sale-badge" style="border:1px solid #866D8D;color:#866D8D;line-height: 20px;height: 20px;">预约</div>
                <span style="font-size: 16px;font-weight: bold;color:#866D8D;"><?=$item['booking_price'] ?></span>
                <span style="font-size: 14px;">&nbsp;元&nbsp;&nbsp;</span>
                <?php } ?>

                <?php if (isset($item['buy_price'])) { ?>
                <div class="sale-badge" style="border:1px solid red;color: red; padding: 0px 3px;line-height: 20px;height: 20px;">现售</div>
                <span style="font-size: 16px;font-weight: bold;color:red"><?=$item['buy_price'] ?></span>
                <span style="font-size: 14px;">&nbsp;元&nbsp;&nbsp;</span>
                <?php } ?>
            </div>
          </div>
      </a>
      <?php } ?>
    </div>
</div>
<?php } ?>
