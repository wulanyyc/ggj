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

  .slogan {
    font-size: 14px;
    line-height: 18px;
    color: #aaa;
  }

  .money {
    font-size: 14px;
    -webkit-transform:scale(0.8);
  }

  .price {
    margin-bottom: 0px;
    color: red;
    font-size: 14px;
    height: 18px;
    line-height: 18px;
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
    font-size: 14px;
    font-weight: normal;
  }

  .search {
      width: 90%;
      margin: 10px auto;
  }
</style>

<input type="hidden" id="kv" value="<?=$kv ?>" />

<div class="card">
    <div class="d-inline search" style="position: relative;">
        <input class="form-control mr-sm-2" type="text" id="search_product" placeholder="美丽健康，新鲜好吃" aria-label="美丽健康，新鲜不贵" style="padding-left: 30px;font-size: 16px;" />
        <div style="position: absolute;left:8px;top:5px;"><i class="fa fa-search" aria-hidden="true"></i></div>
    </div>

    <?php if ($num == 0) { ?>
    <div style="text-align: center;margin-top: 20px;">未找到与<span style="color:red;"><?=$kv ?></span>相关的商品, 请重新查询！</div>
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
        </a>
      </div>
    <?php } ?>
    </div>
    <div style="text-align: center;font-size: 14px;padding:10px;color:#ccc;">没有更多商品了</div>
    <?php } ?>
</div>
