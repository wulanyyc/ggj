<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '购买商品';

MsaView::registerJsFile($this,'/js/buy/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
  .card {
    border-radius: 0;
    border: none;
    border-bottom: 1px solid #eee;
    border-top: 1px solid #eee;
    margin: 5px 0;
  }

  #menu_list a {
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
    width: 33%;
    margin-bottom: 10px;
  }

  .product-content {
    display: table-row;
  }

  .product-img {
    width: 41%;
    display: table-cell;
    vertical-align: middle;
    text-align: center;
  }

  .product-img img {
    width: 80%;
  }

  .product-desc {
    width: 58%;
    display: table-cell;
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
    margin-top: 5px !important;
    color: red;
    font-size: 14px;
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
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-top: 8px;
    height: 30px;
    line-height: 30px;
    font-size: 20px;

  }

  .operator.active {
    border-color: #53a93f;
    color: #53a93f;
  }

  .operator-left {
    width:25%;
    text-align: center;
    border-right: 1px solid #ccc;
    text-decoration: none;
    color: #000;
    font-weight: bold;
    cursor: pointer;
    display: inline-block;
  }

  .operator-left.active {
    border-right: 1px solid #53a93f;
    color: #53a93f;
  }

  .operator-num {
    width: 40%;
    text-align: center;
    border: none;
    display: inline-block;
  }

  .operator-right {
    width: 25%;
    text-align: center;
    border-left: 1px solid #ccc;
    text-decoration: none;
    color: #000;
    font-weight: bold;
    cursor: pointer;
    display: inline-block;
  }

  .operator-right.active {
    border-left: 1px solid #53a93f;
    color: #53a93f;
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

    z-index: 10;
    border-top: 1px solid #f5f5f5; 
  }

  .order-items {

  }

  #login {
    position: absolute;
    z-index: 100;
    bottom: 0;
    width: 100%;
    border-radius: 0;
    display: none;
  }

  a.text-info {
    font-size: 14px;text-decoration: none;position: absolute;right:15px;
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
      <div class="card-header bg-white" style="position:relative;color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        现售商品
        <a href="/buy/booking" class="text-info">
          <i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;去预约
        </a>
      </div>

      <div id="items" style="margin-top: 10px;">
        <div style="display: table-cell;width:12%;" id="menu_list">
          <div class="list-group">
            <?php foreach($categorys as $key => $value) { ?>
              <a class="list-group-item list-group-item-action" href="#list-<?=$key ?>"><?=$value ?></a>
            <?php } ?>
          </div>
        </div>
        <div style="display: table-cell;width:87%" id="order_scroll_container">
          <div id="order_scroll" data-spy="scroll" data-target="#menu_list" data-offset="0" class="scrollspy" style="position: relative;overflow-y: scroll;padding-left: 2%;padding-right: 2%;width: 100%">
            <?php foreach($products as $key => $item) { ?>
            <h5 id="list-<?=$key ?>"><?=$categorys[$key] ?></h5>
            <div class="order-items">
              <?php foreach($item as $product) { ?>
              <div class="order-product" id="pid_<?=$product['id'] ?>" data-id="<?=$product['id'] ?>">
                <div class="product-content">
                  <div class="product-img">
                    <img class="card-img-top" src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" style="width: 80%;"/>
                  </div>

                  <div class="product-desc">
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
                      <div class="operator-num">0</div>
                      <div class="operator-right operator-btn" data-limit="<?=$product['num'] ?>" data-buy-limit="<?=$product['buy_limit'] ?>">+</div>
                    </div>
                    <div class="inventory">
                      库存<?=$product['num'] ?></span><?=$product['unit'] ?>
                      <?php if ($product['buy_limit'] > 0) { ?>
                      &nbsp;&nbsp;<span style="color:red">特价限<?=$product['buy_limit'] ?><?=$product['unit'] ?></span>
                      <?php } ?>
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
</div>

<div id="tongji" style="display: table-cell;vertical-align: middle;text-align: right;">
  <button type="button" class="btn btn-outline-info btn-sm" style="font-size: 14px;margin-right: 8px;text-align: center;padding:0px 5px !important;display: inline-block;" id="filter" data-filter=0>显订购</button>

  <div id="tips" style="color:#aaa;margin-left: 1px;margin-right: 1px;font-size: 14px;display: inline-block;">满<?=$buyGod ?>包邮</div>
  <div id="total" style="margin-left:5px;margin-right: 5px;opacity: 1;display: inline-block;">
    <div style="display: inline-block;color:red;width:76px;text-align: left;">
      <span class="money" style="font-size: 16px;font-weight: normal;">¥</span>
      <span class="realprice" style="font-size:22px;font-weight: normal;">0</span>
    </div>
  </div>
  <div class='btn btn-secondary' id='order' style="margin-right: 8px;width:90px;text-align: center;display: inline-block;"><?=$buyLimit ?>元起购</div>
</div>

<div class="card" id="login">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      用户信息
      <i class="fa fa-times" aria-hidden="true" style="cursor: pointer;font-size: 16px;position: absolute;right:15px;" id="close_login"></i>
  </div>
  <div style="width:94%;margin: 10px auto;" id="login_form">
    <div style="margin-bottom: 10px;">
      <input type="number" class="form-control" id="userphone" name="userphone" placeholder="手机号码" value=""/>
    </div>

    <div style="margin-bottom: 10px;display: table-row;">
      <input type="text" class="form-control" name="code" id="code" placeholder="4位验证码" style="width:50%;display: table-cell;" />
      <button type="button" class="btn btn-outline-danger" style="margin-left:5px;width:100px;display: table-cell;" id="getcode">短信验证码</button>
    </div>

    <button type="button" class="btn btn-success" id="next" style="width:50%;margin-left:25%;margin-top:10px;">
      下一步
    </button>
  </div>
</div>
