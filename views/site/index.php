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
  .card {
    border-radius: 0;
    border: none;
    border-bottom: 1px solid #eee;
    border-top: 1px solid #eee;
    margin: 5px 0;
  }

  #items {
    width: 100%;
    display: table;
  }

  #menu_list a {
    font-size: 13px;
  }

  .list-group-item {
    padding: .5rem .5rem;
  }

  .list-group-item.active {
    background-color: #1ba93b;
    border-color: #1ba93b;
  }

  div.list-group-item {
    font-size: 12px;
  }

  .order-product {
    width: 33%;
    margin-bottom: 10px;
    padding: 1%;
    display: inline-block;
  }

  .order-product.active {
    background-color: #f5f5f5;
  }

  .product-content {
    display: table-row;
  }

  .product-img {
    width: 44%;
    display: table-cell;
    vertical-align: middle;
    text-align: center;
    position: relative;
  }

  .product-img img {
    width: 80%;
  }

  .product-desc {
    width: 55%;
    display: table-cell;
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
    font-size: 14px;
    -webkit-transform:scale(0.8);
  }

  .price {
    margin-top: 5px !important;
    color: red;
    font-size: 14px;
    line-height: 18px;
    position: relative;
  }

  .realprice {
    font-size: 14px;
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
    /*font-size: 30px;*/
    text-align: right;
    padding-left: 5%;
    position: absolute;
    right: 3px;
    top: -3px;
  }

  .operator-left {
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    display: inline-block;
    color: #ccc;
    font-size: 26px;
    visibility: hidden
  }

  .operator-num {
    text-align: center;
    border: none;
    display: inline-block;
    font-size: 18px;
    color: #000;
    width: 30px;
    visibility: hidden;
    height: 26px;
    line-height: 26px;
    vertical-align: top;
    /*padding-top: 2px;*/
  }

  .operator-right {
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    display: inline-block;
    color: #1ba93b;
    font-size: 26px;
  }

  .operator-right.active {
    color: #1ba93b;
  }

  .inventory {
    font-size: 12px;
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
    display: table-cell;
    vertical-align: middle;
    text-align: right;
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

  .list-group-item {
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
  }

  #imgs_alert {
    position: fixed;
    top:20%;
    height: 60%;
    z-index: 999;
    margin-left:10%;
    width:80%;
    background-color: #fff;
    border-radius: 5px;
    text-align: center;
  }

  #close_imgs {
    position: absolute;
    bottom: 2px;
    width: 100%;
    text-align: center;
  }

  #close_imgs img {
    width: 30px;
  }

  .carousel-indicators {
    bottom: -25px;
  }

  .carousel-indicators li.active {
    background-color: green !important;
  }

  .carousel-indicators li {
    background-color: #ccc !important;
  }

  .nav-item.nav-link.active {
      background-color: #1ba93b;
      border-color: #1ba93b;
  }

  .nav-pills .nav-link {
    padding: .2rem .2rem !important;
  }

  .carousel-item img {
    width: 80%;
    height: 80%;
  }

</style>

<input type="hidden" value="<?=$id ?>" id="scroll_id" />
<input type="hidden" value="<?=$buyGod ?>" id="buyGod" />
<input type="hidden" value="<?=$buyLimit ?>" id="buyLimit" />
<input type="hidden" value='<?=$cart ?>' id="buyCart" />
<input type="hidden" value='<?=$cartId ?>' id="cart_id" />

<div style="position: fixed;top: 60px;width:100%;">
    <?php if (strlen($homeTip) > 0) { ?>
    <div class="alert alert-danger" role="alert" style="text-align: center;">
        <?=$homeTip ?>
    </div>
    <?php } ?>
    <nav class="nav nav-pills nav-justified" style="width: 98%;margin: 0 auto;vertical-align: middle;">
      <span class="nav-item nav-link active" id="self" seller-id=0>全部</span>
      <span class="nav-item nav-link" id="guoyuan" seller-id=2>汇农果园</span>
      <span class="nav-item nav-link" id="pzh" seller-id=3>攀枝花直销</span>
      <span class="nav-item nav-link" id="self" seller-id=1>自营</span>
    </nav>
    <div class="card">
      <div id="items" style="margin-top: 10px;">
        <div style="display: table-cell;width:100%;vertical-align: top;" id="order_scroll_container">
          <div id="order_scroll" data-spy="scroll" data-target="#menu_list" data-offset="0" class="scrollspy" style="position: relative;overflow-y: scroll;padding-left: 2%;padding-right: 2%;width: 100%;">
            <?php foreach($products as $key => $item) { ?>
            <div class="order-items">
              <?php foreach($item as $product) { ?>
              <div class="order-product" id="pid_<?=$product['id'] ?>" data-id="<?=$product['id'] ?>" data-seller-id=<?=$product['seller_id'] ?>>
                <div class="product-content">
                  <div class="product-img" data-id="<?=$product['id'] ?>" data-desc='<?=$product['slogan'] ?>'>
                    <img class="card-img-top" src="<?=$product['img'] ?>" alt="<?=$product['name'] ?>" style="width: 80%;"/>
                    <div style="position: absolute;right: 8px;bottom: 0px;font-size: 12px;color:#ccc;">
                      <i class="fa fa-search-plus" aria-hidden="true"></i>
                    </div>
                  </div>

                  <div class="product-desc">
                    <p class="title" data-id="<?=$product['id'] ?>" data-desc='<?=$product['slogan'] ?>'>
                      <?=$product['name'] ?> <?=$product['desc'] ?>
                    </p>
                    <p class="slogan" data-id="<?=$product['id'] ?>" data-desc='<?=$product['slogan'] ?>'>
                      <?=$product['slogan'] ?>
                    </p>
                    <p class="price">
                      <span class="money">¥</span>

                      <?php if ($product['price'] == $product['promotion_price']) { ?>
                      <span class="realprice">
                        <?=$product['price'] ?>
                      </span>
                      <?php } else { ?>
                      <span class="realprice">
                        <?=$product['promotion_price'] ?>
                        <span class="orignal_price">¥ <?=$product['price'] ?></span>
                      </span>
                      <?php } ?>

                      <span class="operator" data-id=<?=$product['id'] ?> data-orignal-price="<?=$product['price'] ?>" data-price=<?=$product['promotion_price'] ?>>
                          <span class="operator-left operator-btn">
                            <i class="fa fa-minus-square-o" aria-hidden="true"></i>
                          </span>
                          <span class="operator-num">0</span>
                          <span class="operator-right operator-btn" data-buy-limit="<?=$product['buy_limit'] ?>"><i class="fa fa-plus-square-o" aria-hidden="true"></i>
                          </span>
                      </span>
                    </p>
                    <div class="inventory" style="color: #1ba93b;">
                      <?php if ($product['seller_id'] == 1) { ?>
                      自营：满<?=$buyGod ?>元包邮，2天内发货
                      <?php } else { ?>
                      包邮，2天内发货
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
          </div>
        </div>
      </div>
    </div>
</div>

<div id="tongji">
  <div style="display: inline-block;font-size: 25px;position: absolute;left:15px;bottom:2px;background-color: #1ba93b;width:46px;height: 46px;border-radius: 23px;text-align: center;color:#fff;line-height: 46px;" id="filter" data-filter=0>
    <span id="cart_icon"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></span>
    <div id="cart_num" style="position: absolute;z-index:100;right:-5px; top:-2px;color:#fff;font-size: 12px;background-color: red;height:20px;width:20px;text-align: center;border-radius: 10px;line-height: 20px;">
      0
    </div>
  </div>
  <div id="total" style="margin-left:5px;margin-right: 5px;opacity: 1;display: inline-block;">
    <div style="display: inline-block;color:red;width:100px;text-align: left;">
      <span class="money" style="font-size: 14px;font-weight: normal;">¥</span>
      <span class="realprice" style="font-size:18px;font-weight: normal;">0</span>
    </div>
  </div>
  <div class='btn btn-secondary' id='order' style="margin-right: 8px;width:100px;text-align: center;display: inline-block;"><?=$buyLimit ?>元起购</div>
</div>

<div class="card" id="login">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      用户信息
      <i class="fa fa-times" aria-hidden="true" style="cursor: pointer;font-size: 14px;position: absolute;right:15px;" id="close_login"></i>
  </div>
  <div style="width:94%;margin: 10px auto;" id="login_form">
    <div style="margin-bottom: 10px;">
      <input type="number" class="form-control" id="userphone" name="userphone" placeholder="手机号码" value=""/>
    </div>

    <div style="margin-bottom: 10px;display: table-row;">
      <input type="text" class="form-control" name="code" id="code" placeholder="4位验证码" style="width:50%;display: table-cell;" />
      <button type="button" class="btn btn-outline-danger" style="margin-left:5px;width:100px;display: table-cell;" id="getcode">获取验证码</button>
    </div>

    <button type="button" class="btn btn-success" id="next" style="width:50%;margin-left:25%;margin-top:10px;">
      下一步
    </button>
  </div>
</div>

<div id="imgs_alert" style="display: none;">
  <div style="display: table;width: 100%;height: 100%;">
    <div style="width: 100%; display: table-row;height: 80%;position: relative;">
      <div style="display: table-cell;vertical-align: middle;text-align: center;" id="carouselContainer">
      </div>
    </div>
    <div style="width: 100%; display: table-row;height: 10%;">
      <div style="display: table-cell;vertical-align: middle;text-align: center;" class="img_slogan">
      </div>
    </div>
    <div id="close_imgs">
      <img src="http://img.guoguojia.vip/img/close.png"/>
    </div>
  </div>
</div>
