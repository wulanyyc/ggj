<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '订制套餐';

MsaView::registerJsFile($this,'/js/package/order.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);
?>

<style type="text/css">
  #order_list a {
    font-size: 14px;
  }

  .list-group-item {
    padding: .75rem .75rem;
  }

  .list-group-item.active {
    background-color: #53a93f;
    border-color: #53a93f;
  }

  .order-product {
    width: 49%;
    margin-bottom: 10px;
  }

  a.product-content {
    text-decoration: none;
    color:#000;
  }

  .product-img {
    width: 30%;
  }

  .product-desc {
    width: 67%;
  }

  .product-desc p {
    margin: 0;
  }

  .title {
    font-size: 14px;
    line-height: 22px;
  }

  .slogan {
    font-size: 12px;
    line-height: 18px;
    color: #aaa;
  }

  .money {
    font-size: 12px;
    -webkit-transform:scale(0.8);
  }

  .price {
    margin-bottom: 0px;
    color: red;
    font-size: 12px;
    height: 18px;
    line-height: 18px;
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
    font-size: 12px;
    font-weight: normal;
  }

  .operator {
    display: flex;
    flex-direction: row;
    border: 1px solid #ccc;
    border-radius: 8px;
    width: 100px;
    margin-top: 8px;
  }

  .operator.active {
    border-color: #53a93f;
  }

  a.operator-left {
    width:30px;
    text-align: center;
    border-right: 1px solid #ccc;
    text-decoration: none;
    color: #000;
    font-weight: bold;
    font-size: 18px;
    line-height: 24px;
  }

  a.operator-left.active {
    border-right: 1px solid #53a93f;
    color: #53a93f;
  }

  .operator-num {
    width: 40px;
    text-align: center;
    border: none;
    line-height: 24px;
  }

  a.operator-right {
    width: 30px;
    text-align: center;
    border-left: 1px solid #ccc;
    text-decoration: none;
    color: #000;
    font-weight: bold;
    font-size: 18px;
    line-height: 24px;
  }

  a.operator-right.active {
    border-left: 1px solid #53a93f;
    color: #53a93f;
  }

  footer {
    display: none;
  }

  #tongji {
    position: fixed;
    height: 55px;
    line-height: 55px;
    width: 100%;
    bottom: 0px;
    background-color: #fff;
    /*opacity: 0.9;*/
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
    z-index: 10;
    border-top: 1px solid #f5f5f5; 
  }

</style>

<div style="position: fixed;top: 55px;">
    <div class="card" style="margin: 1%;">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        私人订制
      </div>

      <div id="items" style="margin-top: 1%;display: flex;flex-wrap: nowrap;justify-content: center;">
        <div id="order_list" class="list-group" style="width:20%;">
          <a class="list-group-item list-group-item-action" href="#list-product">新鲜水果</a>
          <a class="list-group-item list-group-item-action" href="#list-package">果汁套餐</a>
          <a class="list-group-item list-group-item-action" href="#list-tool">生活工具</a>
        </div>
        <div id="order_scroll" data-spy="scroll" data-target="#order_list" data-offset="0" class="scrollspy" style="position: relative;overflow-y: scroll;height:550px;width:78%;padding-left: 2%;padding-right: 2%;"">
          <h5 id="list-product">新鲜水果</h5>
          <div style="display: flex;flex-direction: row;flex-wrap: wrap;justify-content: space-around;">
            <?php foreach($fruits as $product) { ?>
            <div class="order-product">
              <div class="product-content" style="display: flex;flex-direction: row;flex-wrap: nowrap;justify-content: space-between;">
                <div class="product-img" style="width: 40%;">
                  <img class="card-img-top" src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" />
                </div>

                <div class="product-desc" style="width: 58%;">
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
                  <div class="operator" data-id=<?=$product['id'] ?> data-price=<?=$product['promotion_price'] ?>>
                    <a class="operator-left operator-btn" href="#">-</a>
                    <span class="operator-num">0</span>
                    <a class="operator-right operator-btn" href="#">+</a>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
          <h5 id="list-package">果汁套餐</h5>
          <p>...</p>
          <h5 id="list-tool">生活工具</h5>
          <p>...</p>
        </div>
      </div>
    </div>
</div>

<div id="tongji">
  <div id="tips" style="color:#ccc;margin-left: 5px;margin-right: 5px;font-size: 12px;">大于39元,满69元包邮</div>
  <div id="total" style="margin-left:5px;margin-right: 5px;opacity: 1;">
    <div style="display: inline-block;color:red;width:80px;text-align: left;">
      <span class="money" style="font-size: 14px;font-weight: normal;">¥</span>
      <span class="realprice" style="font-size:22px;font-weight: normal;">0</span>
    </div>
  </div>
  <div class='btn btn-secondary' id='order' style="width:80px;height: 40px;margin-right: 10px;">选好了</div>
</div>
