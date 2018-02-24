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
    /*border-bottom: 1px solid #eee;*/
    /*border-top: 1px solid #eee;*/
    /*margin: 3px 5px;*/
  }

  .product-items {
    margin-top: 5px;
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
    width: 60px;
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

  .zhang_img {
    position: absolute;
    top: 5px;
    right: 0;
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

  .operator {
    /*font-size: 30px;*/
    text-align: right;
    padding-left: 5%;
    /*position: absolute;*/
    /*right: 3px;*/
    /*top: 1px;*/
  }

  .operator-left {
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    display: inline-block;
    color: #ccc;
    font-size: 28px;
    visibility: hidden
  }

  .operator-num {
    text-align: center;
    border: none;
    display: inline-block;
    font-size: 18px;
    color: #000;
    width: 25px;
    visibility: hidden;
    height: 28px;
    line-height: 28px;
    vertical-align: top;
    /*padding-top: 2px;*/
  }

  .operator-right {
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    display: inline-block;
    color: #1ba93b;
    font-size: 28px;
  }

  .operator-right.active {
    color: #1ba93b;
  }
</style>

<?php if (strlen($homeTip) > 0) { ?>
<div class="alert alert-danger" role="alert" style="text-align: center;">
    <?=$homeTip ?>
</div>
<?php } ?>

<div class="card">
    <div class="card-header bg-white">
        <!-- <div class="label-title" style="text-align: center;width: 100%;">新鲜佳果，一样来一点</div> -->
        <div class="btn-group">
          <button type="button" class="btn btn-success">特价</button>
          <button type="button" class="btn btn-danger">新鲜水果</button>
          <button type="button" class="btn btn-success">套餐</button>
          <button type="button" class="btn btn-success">干果</button>
        </div>
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

                <div class="price buy_price" data-link="/buy/booking?id=<?=$product['id'] ?>">
                  <div class="sale-badge" style="border:1px solid #866D8D;color:#866D8D;">预约新鲜</div>
                  <div style="display: inline-block;line-height: 18px;">
                    <span class="money" style="color:#866D8D;">¥</span>
                    <span class="realprice" style="color:#866D8D;">
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
                <div>
                  <span class="operator" data-id=<?=$product['id'] ?>>
                      <span class="operator-left operator-btn">
                        <i class="fa fa-minus-square-o" aria-hidden="true"></i>
                      </span>
                      <span class="operator-num">0</span>
                      <span class="operator-right operator-btn" data-limit="<?=$product['num'] ?>" data-buy-limit="<?=$product['buy_limit'] ?>"><i class="fa fa-plus-square-o" aria-hidden="true"></i>
                      </span>
                  </span>
                </div>
              </div>
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

<a style="font-size: 25px;position: fixed;right:0px;top:50%;background-color: #866D8D;width:50px;height: 50px;border-radius: 25px;text-align: center;color:#fff;line-height: 50px;" href="<?=$cartLink ?>">
    <span id="cart_icon"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></span>
    <div id="cart_num" style="position: absolute;z-index:100;left:-5px; top:-5px;color:#fff;font-size: 12px;background-color: red;height:20px;width:20px;text-align: center;border-radius: 10px;line-height: 20px;">
      <?=$cartNum ?>
    </div>
</a>
