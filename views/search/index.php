<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '产品列表';

MsaView::registerJsFile($this,'/js/search/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);
?>

<style type="text/css">
  .order-items {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: flex-start;
  }

  .product-content {
    display: flex;flex-direction: row;flex-wrap: nowrap;justify-content:space-around;align-items: center;
  }

  .order-product {
    width: 31%;
    margin: 1%;
  }

  a.product-content {
    text-decoration: none;
    color:#000;
  }


  .product-desc p {
    margin: 0;
  }

  .title {
    font-size: 16px;
    line-height: 22px;
  }

  p.slogan {
    font-size: 14px;
    line-height: 20px;
    color: #aaa;
    margin-bottom: 6px;
  }

  .money {
    font-size: 16px;
    /*-webkit-transform:scale(0.8);*/
  }

  .price {
    margin-bottom: 8px;
    color: red;
    font-size: 16px;
    height: 18px;
    line-height: 18px;
    display: flex;flex-direction: row;align-items: center;justify-content: flex-start;
    flex-wrap: wrap;
  }

  .realprice {
    font-weight: bold;
    font-size: 15px;
  }

  .orignal_price {
    padding-left: 3px;
    color: #ccc;
    text-decoration: line-through;
    color: #999;
    font-size: 16px;
    font-weight: normal;
  }

  .search {
      width: 90%;
      margin: 10px auto;
  }

  footer {
    display: none;
  }

  .sale-badge {
    font-weight:bold;
    border-radius: 5px;
    font-size: 14px;
    padding: 0px 4px;
    margin-right: 5px;
    line-height: 20px;
  }
</style>

<input type="hidden" id="kv" value="<?=$kv ?>" />

<div class="card">
    <div class="d-inline search" style="position: relative;">
        <input class="form-control mr-sm-2" type="text" id="search_product" placeholder="美丽健康，好吃不贵" aria-label="美丽健康，好吃不贵" style="padding-left: 30px;font-size: 18px;" />
        <div style="position: absolute;left:8px;top:5px;"><i class="fa fa-search" aria-hidden="true"></i></div>
    </div>

    <?php if ($num == 0) { ?>
    <div style="text-align: center;margin-top: 20px;">未找到与"<span style="color:red;"><?=$kv ?></span>"相关的商品, 请重新查询！</div>
    <br/>
    <?php } else { ?>
    <div class="order-items">
    <?php foreach($products as $product) { ?>
      <div class="order-product">
        <a class="product-content" href="/buy?id=<?=$product['id'] ?>">
          <div class="product-img" style="width: 20%;">
            <img class="card-img-top" src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" />
          </div>

          <div class="product-desc" style="width: 68%;">
            <p class="title"><?=$product['name'] ?> <?=$product['desc'] ?></p>
            <p class="slogan"><?=$product['slogan'] ?></p>
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
              <div style="margin-left:5px;font-size: 14px;">100%</div>
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
              <div style="margin-left:5px;font-size: 14px;"><?=$product['fresh_percent'] ?>%</div>
            </div>
            <?php } ?>
          </div>
        </a>
      </div>
    <?php } ?>
    </div>
    <div style="text-align: center;font-size: 16px;padding:10px;color:#ccc;">没有更多商品了</div>
    <?php } ?>
</div>
