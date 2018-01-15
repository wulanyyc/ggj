<?php
use Yii;
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
  .buy-type {
    border-radius: 0;
    width: 98%;
    margin: 1% auto;
  }

  .label {
    display: inline-block;
    width: 110px;
    color: #53a93f;
  }

  .item {
    padding-left: 20px;
    margin-bottom: 1%;
    display: flex;
    flex-direction: row;

    -webkit-display: flex;
    -webkit-flex-direction: row;
  }

  #rule li {
    padding-left: 15px;
    padding-top: 8px;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;

    -webkit-display: flex;
    -webkit-flex-direction: row;
    -webkit-flex-wrap: wrap;
  }

  .label {
    width:100px;
  }

  footer {
    display: none;
  }
</style>

<div class="card" id="type_content" style="display: flex;align-items: center;justify-content: center;-webkit-display: flex;-webkit-align-items: center;-webkit-justify-content: center;">
    <div class="card buy-type">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;text-align: center;">
        <span  style="color: #866D8D">预约</span> <span style="color:red;">vs</span> <span>现售</span>
      </div>

      <div id="rule" style="margin-top: 1%;font-size: 14px;width:100%;">
        <ul>
          <li>
            <div class="label" style="color: #866D8D">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;新鲜优质：
            </div>
            <div>
              本地水果可当日采购。进口水果供应商到货即采购，挑选平台同批次佳果，保证新鲜度和质量
            </div>
          </li>

          <li>
            <div class="label" style="color: #866D8D">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;快递发货：
            </div>
            <div>
            限成都，每周<span style="color:red;font-weight: bold;">1、6</span>顺丰发货
            </div>
          </li>
        </ul>
        <hr/>
        <ul>
          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;价格实惠：
            </div>
            <div>
              低于市场价，享全场95折
            </div>
          </li>

          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;快递发货：
            </div>
            <div>
            限成都，24小时内顺丰发货
            </div>
          </li>
        </ul>
      </div>
    </div>

    <br/>
    <div style="display: flex;flex-direction: row;justify-content: space-around;width:100%;margin-top:1%;-webkit-display: flex;-webkit-flex-direction: row;-webkit-justify-content: space-around;">
      <div class="choose_type" data-href='/buy/booking?id=<?=$id ?>' data-type=2 style="width:80px; height: 80px;border: 1px solid #866D8D;border-radius: 40px;background-color: #866D8D; margin-bottom: 1%;text-align: center;line-height: 80px;color:white;font-size: 16px;">爱预约</div>

      <div class="choose_type" data-type=1 style="width:80px; height: 80px;border: 1px solid #53a93f;border-radius: 40px;background-color: #53a93f; margin-bottom: 1%;text-align: center;line-height: 80px;color:white;font-size: 16px;" data-href='/buy/?id=<?=$id ?>'>买现货</div>
      </div>
    </div>
</div>
