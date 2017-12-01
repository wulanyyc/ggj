<?php 
use yii\web\View;
use app\components\MsaView;
use app\assets\HashAsset;

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
    padding-left: 3px;
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

  .operator-left {
    width:30px;
    text-align: center;
    border-right: 1px solid #ccc;
    text-decoration: none;
    color: #000;
    font-weight: bold;
    font-size: 18px;
    line-height: 24px;
    cursor: pointer;
  }

  .operator-left.active {
    border-right: 1px solid #53a93f;
    color: #53a93f;
  }

  .operator-num {
    width: 40px;
    text-align: center;
    border: none;
    line-height: 24px;
  }

  .operator-right {
    width: 30px;
    text-align: center;
    border-left: 1px solid #ccc;
    text-decoration: none;
    color: #000;
    font-weight: bold;
    font-size: 18px;
    line-height: 24px;
    cursor: pointer;
  }

  .operator-right.active {
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
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
    z-index: 10;
    border-top: 1px solid #f5f5f5; 
  }

  .order-items {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .product-content {
    display: flex;flex-direction: row;flex-wrap: nowrap;justify-content: space-between;
  }

  #userinfo {
    position: absolute;
    z-index: 100;
    bottom: 0;
    width: 100%;
    border-radius: 0;
    display: none;
  }

</style>

<div style="position: fixed;top: 55px;">
    <div class="card" style="margin: 1%;">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        私人订制
      </div>

      <div id="items" style="margin-top: 10px;display: flex;flex-wrap: nowrap;justify-content: center;">
        <div id="order_list" class="list-group" style="width:20%;">
          <a class="list-group-item list-group-item-action" href="#list-product">新鲜水果</a>
          <a class="list-group-item list-group-item-action" href="#list-package">优惠套餐</a>
          <a class="list-group-item list-group-item-action" href="#list-tool">生活工具</a>
        </div>
        <div id="order_scroll" data-spy="scroll" data-target="#order_list" data-offset="0" class="scrollspy" style="position: relative;overflow-y: scroll;height:550px;width:78%;padding-left: 2%;padding-right: 2%;"">
          <h5 id="list-product">新鲜水果</h5>
          <div class="order-items">
            <?php foreach($fruits as $product) { ?>
            <div class="order-product">
              <div class="product-content">
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
                    <div class="operator-left operator-btn">-</div>
                    <span class="operator-num">0</span>
                    <div class="operator-right operator-btn">+</div>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
          <h5 id="list-package">优惠套餐</h5>
          <div class="order-items">
            <?php foreach($packages as $product) { ?>
            <div class="order-product">
              <div class="product-content">
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
                    <div class="operator-left operator-btn">-</div>
                    <span class="operator-num">0</span>
                    <div class="operator-right operator-btn">+</div>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
          <h5 id="list-tool">生活工具</h5>
          <div class="order-items">
            <?php foreach($tools as $product) { ?>
            <div class="order-product">
              <div class="product-content">
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
                    <div class="operator-left operator-btn">-</div>
                    <span class="operator-num">0</span>
                    <div class="operator-right operator-btn">+</div>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
        </div>
      </div>
    </div>
</div>

<div id="tongji">
  <div id="tips" style="color:#ccc;margin-left: 5px;margin-right: 5px;font-size: 12px;">满59元包邮</div>
  <div id="total" style="margin-left:5px;margin-right: 5px;opacity: 1;">
    <div style="display: inline-block;color:red;width:80px;text-align: left;">
      <span class="money" style="font-size: 14px;font-weight: normal;">¥</span>
      <span class="realprice" style="font-size:22px;font-weight: normal;">0</span>
    </div>
  </div>
  <div class='btn btn-secondary' id='order' style="width:100px;height: 40px;margin-right: 10px;">39元起购</div>
</div>

<div class="card" id="userinfo">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      订单信息<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_userinfo"></i>
  </div>
  <form style="width:94%;margin: 10px auto;" id="userinfo_form">
    <div style="display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;">
      <input type="text" class="form-control" id="username" name="username" placeholder="联系人">
    </div>

    <div style="display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;">
      <input type="number" class="form-control" id="cellphone" name="cellphone" placeholder="手机号码">
    </div>

    <div style="display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;">
      <textarea id="address" name="address" style="width: 100%;border: 1px solid #ced4da;padding: 5px 10px;" placeholder="快递地址"></textarea>
    </div>

    <div style="display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;">
      <input type="text" class="form-control" name="code" id="code" placeholder="6位验证码" style="width:50%;">
      <button type="button" class="btn btn-outline-danger" style="margin-left:5px;">手机验证码</button>
    </div>

    <div style="display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;">
      <textarea id="memo" name="memo" style="width: 100%;border: 1px solid #ced4da;padding: 5px 10px;" placeholder="特殊要求"></textarea>
    </div>

    <div id="express_fee" style="color:red;">快递费：<span id="express_fee_text">6元</span></div>

    <button type="button" class="btn btn-success" id="pay" style="width:50%;margin-left:25%;margin-top:10px;">
      去支付<span id="pay_money"></span>元
    </button>
  </form>
</div>
