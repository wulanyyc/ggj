<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '购买类型';

MsaView::registerJsFile($this,'/js/buy/type.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);
?>

<style type="text/css">
  #type_content {
    display: table;
    /*vertical-align: middle;*/
    background-color: #fff;
    width: 100%;
  }

  .buy-type {
    border-radius: 0;
    width: 98%;
    margin: 1% auto;
  }

  .label {
    display: inline-block;
    width: 110px;
    color: #1ba93b;
  }

  .item {
    padding-left: 20px;
    margin-bottom: 1%;
  }

  #type_content li {
    padding-left: 15px;
    padding-top: 8px;
    list-style: none;
    width: 100%;
  }

  .label {
    width:150px;
  }

  .card {
    border:none;
  }

  footer {
    display: none;
  }
</style>

<div id="type_content">
    <div style="display: table;width: 100%;margin-top: 15%">
      <div style="display: table-row;width: 100%">
        <div style="width: 35%;display: table-cell;vertical-align: middle;text-align: right;padding-right: 10px;">
          <img src="http://img.guoguojia.vip/img/booking.png" style="width: 80px;"/>
        </div>
        <ul style="width: 65%;display: table-cell;vertical-align: middle;">
          <li>
            <div class="label" style="color: #866D8D;font-size: 16px;">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;新鲜优质：
            </div>
            <div>
              国产水果当日采购<br/>进口水果供应商到货即采购<br/>精选佳果，保证新鲜度和质量
            </div>
          </li>

          <li>
            <div class="label" style="color: #866D8D;font-size: 16px;">
              <i class="fa fa-truck" aria-hidden="true"></i>&nbsp;顺丰发货：
            </div>
            <div>
            限成都，每周<span style="color:red;font-weight: bold;"><?=$bookingSender ?></span>发货
            </div>
          </li>
        </ul>
      </div>

      <div style="display: table-row;">
          <div style="width: 35%; display: table-cell;vertical-align: middle;text-align: right;padding-right: 10px;">
            <img src="http://img.guoguojia.vip/img/sale.png" style="width: 90px;" /> 
          </div>
          <ul style="width: 65%;display: table-cell;vertical-align: middle;">
            <li>
              <div class="label" style="font-size: 16px;">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;新鲜实惠：
              </div>
              <div>
                新鲜实惠, 低于市场价
              </div>
            </li>

            <li>
              <div class="label"  style="font-size: 16px;">
                <i class="fa fa-truck" aria-hidden="true"></i>&nbsp;顺丰发货：
              </div>
              <div>
              限成都，24小时内发货
              </div>
            </li>
          </ul>
      </div>
    </div>

    <br/>
    <div class="btn btn-success choose_type" data-type="2" data-href="/buy/booking?id=<?=$id ?>" style="width: 50%;margin-left:25%;margin-top:20px;">去预约</div>
    <br/>
    <div class="btn btn-danger choose_type" data-type="1" data-href="/buy/?id=<?=$id ?>" style="width: 50%;margin-left:25%;margin-top:20px;">买现货</div>
</div>
