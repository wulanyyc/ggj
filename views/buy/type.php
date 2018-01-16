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
    display: -webkit-flex;
    flex-direction: -webkit-row;

    display: flex;
    flex-direction: row;
  }

  #rule li {
    padding-left: 15px;
    padding-top: 8px;
    display: -webkit-flex;
    flex-direction: -webkit-row;
    flex-wrap: -webkit-wrap;

    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
  }

  .label {
    width:100px;
  }

  .card {
    border:none;
  }

  footer {
    display: none;
  }
</style>

<div class="card" id="type_content" style="display: -webkit-flex;align-items: -webkit-center;justify-content: -webkit-center;display: flex;align-items: center;justify-content: center;">
    <div class="card buy-type">
      <div id="rule" style="margin-top: 1%;font-size: 14px;width:100%;">
        <div style="display: flex;flex-direction: row;justify-content: center;align-items: center;">
          <img src="http://img.guoguojia.vip/img/booking.png" style="width: 90px;"/>
          <ul style="width:60%">
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
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;顺丰发货：
              </div>
              <div>
              限成都，每周<span style="color:red;font-weight: bold;"><?=Yii::$app->params['bookingSender'] ?></span>发货
              </div>
            </li>
          </ul>
        </div>
        <!-- <hr/> -->
        <div style="display: flex;flex-direction: row;justify-content: center;align-items: center;">
            <img src="http://img.guoguojia.vip/img/sale.png" style="width: 90px;" /> 
            <ul style="width:60%">
              <li>
                <div class="label" style="font-size: 16px;">
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;价格实惠：
                </div>
                <div>
                  新鲜实惠, 低于市场价
                </div>
              </li>

              <li>
                <div class="label"  style="font-size: 16px;">
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;顺丰发货：
                </div>
                <div>
                限成都，24小时内发货
                </div>
              </li>
            </ul>
        </div>
      </div>
    </div>

    <a class="btn btn-success" href="/buy/booking?id=<?=$id ?>" style="width: 50%;margin-bottom: 10px;margin-top:10px;">去预约</a>
    <a class="btn btn-danger" href="/buy/?id=<?=$id ?>" style="width: 50%;">买现货</a>
    <!-- <div style="display: -webkit-flex;flex-direction: -webkit-row;justify-content: -webkit-space-around;display: flex;flex-direction: row;justify-content: space-around;width:100%;margin-top:1%;">
      <div class="choose_type" data-href='/buy/booking?id=<?=$id ?>' data-type=2 style="width:80px; height: 80px;border: 1px solid #866D8D;border-radius: 40px;background-color: #866D8D; margin-bottom: 1%;text-align: center;line-height: 80px;color:white;font-size: 16px;">爱预约</div>

      <div class="choose_type" data-type=1 style="width:80px; height: 80px;border: 1px solid #53a93f;border-radius: 40px;background-color: #53a93f; margin-bottom: 1%;text-align: center;line-height: 80px;color:white;font-size: 16px;" data-href='/buy/?id=<?=$id ?>'>买现货</div>
      </div>
    </div> -->
</div>
