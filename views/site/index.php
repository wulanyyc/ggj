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

  #promotion {
    width: 100%;
    margin: 0 auto;
    display: table;
  }

  a.promotion-item {
    display: table-cell;
    width: 48%;
    padding: 5px;

    border-radius: 3px;
    text-decoration: none;
  }

  .promotion-item-left {
    display: table-cell;
    vertical-align: middle;
    width: 50%;
    opacity: 0.9;
    text-align: center;
  }

  .promotion-item-left-content {
    width:85%;margin: auto;
  }

  .promotion-item-left-content-top {
    text-align: center;
    font-size: 18px;
    color: #fff;
    height: 44px;
    line-height: 44px;
    border-bottom: 2px solid #fff;
    font-weight: 400;
    width: 100%;
  }

  .promotion-item-left-content-bottom {
    color: #fff;
    font-size: 13px;
    line-height: 26px;
    padding: 3px;
    text-align: center;
  }

  .promotion-item-right {
    display: table-cell;
    vertical-align: middle;
    width: 50%;
    text-align: center;
  }

  .promotion-item-right img {
    height: 140px;
  }

  .card {
    border-radius: 0;
    border: none;
    border-bottom: 1px solid #eee;
    border-top: 1px solid #eee;
    margin: 3px 5px;
  }

  .label-rec {
    color: #1ba93b;
    border-radius: 0;
    border-bottom: 2px solid #92BC2C;
  }

  .label-items {
    float: right;
  }

  .label-item {
    border: 1px solid #1ba93b;
    padding: 3px 4px;
    color: #fff;
    background-color: #1ba93b;
    text-align: center;
    font-size: 14px;
    transform: scale(0.9);
  }

  .label-item:hover {
    cursor: hand;
    opacity: 0.9;
    text-decoration: none;
    color: #fff;
  }

  .label-active {
    border: 1px solid red;
    background-color: red;
  }

  .product-items {
    /*margin-top: 5px;*/
    width: 100%;
  }

  .product-item {
    display: inline-table;
    width: 33%;
    /*border: 1px solid #f5f5f5;*/
    /*margin-bottom: 5px;*/
  }

  a.product-item-content {
    text-decoration: none;
    color: #000;
    display: table;
  }

  .product-card-img {
    display: table-cell;
    width:40%;
    vertical-align: middle;
    position: relative;
  }

  .product-card-img img {
    height: 140px;
  }

  .product-card-content {
    display: table-cell;
    width: 58%;
    vertical-align: middle;
  }

  .product-item .tip-content {
    background-color: #1ba93b;
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
    /*height: 18px;*/
    line-height: 18px;
    padding-left: 10px;
  }

  .desc {
    margin-bottom: 4px;
    font-size: 14px;
    /*height: 16px;*/
    line-height: 16px;
    padding-left: 10px;
  }

  .price {
    margin-bottom: 6px;
    color: red;
    font-size: 15px;
    /*height: 20px;*/
    padding-left: 10px;
  }

  .sale-badge {
    font-weight: bold;
    border-radius: 3px;
    font-size: 12px;
    padding: 0px 4px;
    margin-right: 3px;
    line-height: 18px;
    width: 40px;
    text-align: center;
    display: inline-block;
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
    font-size: 15px;
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

  .discount {
    font-size: 12px;
    padding-left: 5px;
    padding-top: 5px;
    display: inline-block;
  }

  #package {
    margin-top: 5px;
    width: 100%;
    display: table-row;
  }

  #package .package-content {
    width: 33%;
    display: table-cell;
    text-decoration: none;color:black;
    position: relative;
  }

  .package-content-header {
    color: #fff;
    border-radius: 0;
    padding: 3%;
    font-size: 16px;
  }


  .package-item {
    height: auto;
  }

  .package-item-products{
    color:black;
    text-decoration: none;
    display: table;
  }

  .package-item-product{
    font-size: 14px;
    display: table-row;
    vertical-align: middle;
    margin-top: 5px;
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
    border: 1px solid #1ba93b;
  }

  .header_1 {
    background-color: #1ba93b;
  }

  .border_1 {
    border-top: 1px solid #1ba93b;
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
  }

  .package-price {
    width:100%;
    height:40px;
    border-top: 1px solid #f5f5f5;
    text-align: right;
    line-height: 40px;
    margin-top: 5px;
    position: absolute;bottom: 0px;
  }

  .zhang_img {
    position: absolute;
    top: 5px;
    right: 0;
    /*left:38%;*/
    width: 40px;
    height: 40px !important;
  }

  .rec_img {
    position: absolute;
    top: 8px;
    right: 5px;
    width: 60px;
    /*height: 40px !important;*/
  }
</style>

<?php if (strlen($homeTip) > 0) { ?>
<div class="alert alert-danger" role="alert" style="text-align: center;">
    <?=$homeTip ?>
</div>
<?php } ?>

<div id="promotion">
  <a class="promotion-item promotion-item-down" href="<?=$dayPromotion['link'] ?>">
    <div style="display: table;width:100%;">
      <div class="promotion-item-left" style="background-color: #1ba93b;">
        <div class="promotion-item-left-content">
          <div class="promotion-item-left-content-top">今日特价</div>
          <div class="promotion-item-left-content-bottom"><?=$dayPromotion['text'] ?> ¥<?=$dayPromotion['price'] ?></div>
        </div>
      </div>

      <div class="promotion-item-right" style="background-color: #D0E3DC;position: relative;">
        <img src="<?=$dayPromotion['img'] ?>"></img>
      </div>

    </div>
  </a>

  <a class="promotion-item" href="/prize">
    <div style="display: table;width:100%;">
      <div class="promotion-item-left" style="background-color: #DD182B;">
        <div class="promotion-item-left-content">
          <div class="promotion-item-left-content-top">抽奖100%</div>
          <div class="promotion-item-left-content-bottom">隔<?=$prizeLimit ?>天抽一次</div>
        </div>
      </div>
      <div class="promotion-item-right" style="background-color: #F3CFD3;">
        <img src="http://img.guoguojia.vip/img/prize/pan_3.png"></img>
      </div>
    </div>
  </a>
</div>

<div class="card">
    <div class="card-header bg-white label-rec">
        <div class="label-title" style="text-align: center;width: 100%;">新鲜佳果，一样来一点</div>
    </div>

    <div class="product-items">
      <?php foreach($fruits as $product) { ?>
          <div class="product-item" style="position: relative;">
            <a class="product-item-content" href="<?=$product['link'] ?>">
              <div class="product-card-img">
                <img src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" class="prodcut_img" />
                <img src="http://img.guoguojia.vip/img/xiandu.png" class="zhang_img" />
              </div>

              <div class="product-card-content">
                <p class="title"><?=$product['name'] ?></p>
                <p class="desc"><?=$product['desc'] ?></p>

                <div class="price buy_price" data-link="/buy/?id=<?=$product['id'] ?>">
                  <!-- <div class="sale-badge" style="border:1px solid red;">现售</div> -->
                  <div style="display: inline-block;">
                    <span class="money">¥</span>
                    <span class="realprice">
                      <?=$product['buy_price'] ?>
                      <span class="money">元/<?=$product['unit'] ?></span>
                    </span>
                  </div>

                  <div class="discount">
                    <?php if ($product['buy_price'] < $product['price']) { ?>
                    <!-- <i class="fa fa-shopping-cart" aria-hidden="true"></i> -->
                    <?=round($product['buy_price']/$product['price'], 2) * 10 ?>折
                    <?php } ?>
                  </div>
                </div>
              </div>
              <!-- </div> -->
            </a>

            <div class="tip-content">
                <?=$product['slogan'] ?>
            </div>

            <?php if ($product['recflag'] == 1) { ?> 
            <img src="/img/icon/rec.png" class="rec_img" />
            <?php } ?>

          </div>
      <?php } ?>
    </div>
</div>

<?php if ($cartNum > 0) { ?>
<a style="font-size: 25px;position: fixed;right:0px;top:50%;background-color: #866D8D;width:50px;height: 50px;border-radius: 25px;text-align: center;color:#fff;line-height: 50px;" href="<?=$cartLink ?>">
    <span id="cart_icon"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></span>
    <div id="cart_num" style="position: absolute;z-index:100;left:-5px; top:-5px;color:#fff;font-size: 12px;background-color: red;height:20px;width:20px;text-align: center;border-radius: 10px;line-height: 20px;">
      <?=$cartNum ?>
    </div>
</a>
<?php } ?>
