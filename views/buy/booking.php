<?php
use Yii;
use yii\web\View;
use app\components\MsaView;

$this->title = '预约商品';

MsaView::registerJsFile($this,'/js/buy/booking.js', 
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
    margin: 5px 0;
  }

  #menu_list a {
    font-size: 16px;
  }

  .list-group-item {
    padding: .75rem .75rem;
  }

  .list-group-item.active {
    background-color: #866D8D;
    border-color: #866D8D;
  }

  .order-product {
    width: 33%;
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

  .operator {
    display: flex;
    flex-direction: row;
    border: 1px solid #ccc;
    border-radius: 8px;
    width: 100px;
    margin-top: 8px;
  }

  .operator.active {
    border-color: #866D8D;
    color: #866D8D;
  }

  .operator-left {
    width:60px;
    text-align: center;
    border-right: 1px solid #ccc;
    text-decoration: none;
    color: #000;
    font-weight: bold;
    font-size: 24px;
    line-height: 30px;
    cursor: pointer;
  }

  .operator-left.active {
    border-right: 1px solid #866D8D;
    color: #866D8D;
  }

  .operator-num {
    width: 80px;
    text-align: center;
    border: none;
    line-height: 30px;
    font-size: 20px;
  }

  .operator-right {
    width: 60px;
    text-align: center;
    border-left: 1px solid #ccc;
    text-decoration: none;
    color: #000;
    font-weight: bold;
    font-size: 24px;
    line-height: 30px;
    cursor: pointer;
  }

  .operator-right.active {
    border-left: 1px solid #866D8D;
    color: #866D8D;
  }

  .inventory {
    font-size: 14px;
    line-height: 24px;
    color: #aaa;
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
    justify-content: flex-start;
  }

  .product-content {
    display: flex;flex-direction: row;flex-wrap: nowrap;justify-content: space-around;
    align-items: center;
  }

  #login {
    position: absolute;
    z-index: 100;
    bottom: 0;
    width: 100%;
    border-radius: 0;
    display: none;
  }
</style>

<input type="hidden" value="<?=$id ?>" id="scroll_id" />
<input type="hidden" value="<?=$buyGod ?>" id="buyGod" />
<input type="hidden" value="<?=$buyLimit ?>" id="buyLimit" />
<input type="hidden" value="<?=$expressFee ?>" id="expressFee" />
<input type="hidden" value='<?=$cart ?>' id="buyCart" />
<input type="hidden" value='<?=$cid ?>' id="order_id" />
<input type="hidden" value="<?=$orderType ?>" id="order_type" />

<div style="position: fixed;top: 55px;width:100%;">
    <div class="card">
      <div class="card-header bg-white" style="color: #866D8D;position:relative;border-radius: 0;border-bottom: 2px solid #866D8D;display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
        预约商品列表
        <a href="/buy/" class="text-info" style="font-size: 16px;text-decoration: none;"><i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;买现货享<?=Yii::$app->params['buyDiscount'] * 10 ?>折</a>
      </div>

      <div id="items" style="margin-top: 10px;display: flex;flex-wrap: nowrap;justify-content: space-around;">
        <div id="menu_list" class="list-group" style="width:12%;">
          <?php foreach($categorys as $key => $value) { ?>
            <a class="list-group-item list-group-item-action" href="#list-<?=$key ?>"><?=$value ?></a>
          <?php } ?>
        </div>
        <div id="order_scroll" data-spy="scroll" data-target="#menu_list" data-offset="0" class="scrollspy" style="position: relative;overflow-y: scroll;width:86%;padding-left: 2%;padding-right: 2%;"">
          <?php foreach($products as $key => $item) { ?>
          <h5 id="list-<?=$key ?>"><?=$categorys[$key] ?></h5>
          <div class="order-items">
            <?php foreach($item as $product) { ?>
            <div class="order-product" id="pid_<?=$product['id'] ?>" data-id="<?=$product['id'] ?>">
              <div class="product-content">
                <div class="product-img" style="width: 40%;">
                  <img class="card-img-top" src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" />
                </div>

                <div class="product-desc" style="width: 55%;">
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
          <?php } ?>
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
  <button type="button" class="btn btn-outline-info btn-sm" style="font-size: 14px;margin-right: 3px;width:85px;text-align: center;" id="filter" data-filter=0>仅显示订购</button>
  <div id="tips" style="color:#aaa;margin-left: 5px;margin-right: 3px;font-size: 14px;">满<?=$buyGod ?>元包邮</div>
  <div id="total" style="margin-left:5px;margin-right: 5px;opacity: 1;">
    <div style="display: inline-block;color:red;width:80px;text-align: left;">
      <span class="money" style="font-size: 16px;font-weight: normal;">¥</span>
      <span class="realprice" style="font-size:22px;font-weight: normal;">0</span>
    </div>
  </div>
  <div class='btn btn-secondary' id='order' style="width:100px;height: 40px;margin-right: 10px;"><?=$buyLimit ?>元起购</div>
</div>

<div class="card" id="login">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      用户信息<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_login"></i>
  </div>
  <div style="width:94%;margin: 10px auto;" id="login_form">
    <div style="margin-bottom: 10px;">
      <input type="number" class="form-control" id="userphone" name="userphone" placeholder="手机号码" value="" />
    </div>

    <div style="display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;">
      <input type="text" class="form-control" name="code" id="code" placeholder="4位验证码" style="width:50%;" />
      <button type="button" class="btn btn-outline-danger" style="margin-left:5px;width:100px;" id="getcode">短信验证码</button>
    </div>

    <button type="button" class="btn btn-success" id="next" style="width:50%;margin-left:25%;margin-top:10px;">
      下一步
    </button>
  </div>
</div>
