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
    margin-top: 10px;
    margin-bottom: 8px;
    width: 48%;
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
  }

  #rule li {
    padding-left: 15px;
    padding-top: 8px;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
  }

  .label {
    width:100px;
  }

  footer {
    display: none;
  }
</style>

<div class="card" id="type_content" style="display: flex;align-items: center;justify-content: center;">
  <div class="card-header bg-white" style="font-size:24px;color: #53a93f;border-radius: 0;font-weight: bold;border:none;">
        购买默认类型设置
  </div>
  <div style="display: flex;flex-direction: row;justify-content: space-around;align-items: stretch;flex-wrap: nowrap;">
    <div class="card buy-type" style="border: 1px solid #D5CCDB;">
      <div class="card-header bg-white" style="color: #866D8D;border-radius: 0;border-bottom: 2px solid #866D8D;">
        预约
      </div>

      <div id="rule" style="margin-top: 1%;font-size: 14px;width:100%;">
        <ul>
          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;绝对新鲜：
            </div>
            <div>
              早上在水果市场进货，上午打包，下午发货。
            </div>
          </li>

          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;快递发货：
            </div>
            <div>
            限<span style="color:red;font-weight: bold;">成都</span>预约，每周<span style="color:red;font-weight: bold;">1、3、5</span>发货，2、4、6到货。
            </div>
          </li>

          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;售后服务：
            </div>
            <div>
            如遇缺货无法完成订单，立即按付款方式退款。
            </div>
          </li>
        </ul>
        <br/>
      </div>
    </div>

    <div class="card buy-type" style="border: 1px solid #D0E3DC;">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        买现货
      </div>

      <div id="rule" style="margin-top: 1%;font-size: 14px;width:100%;">
        <ul>
          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;价格实惠：
            </div>
            <div>
              低于市场价，享受平台各种优惠后再享全场<span style="color:red;font-weight: bold;"><?=Yii::$app->params['buyDiscount'] * 10 ?></span>折。
            </div>
          </li>

          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;快递发货：
            </div>
            <div>
            限<span style="color:red;font-weight: bold;">成都</span>购买，下单后24小时内同城发货
            </div>
          </li>
        </ul>
        <br/>
      </div>
    </div>
  </div>

  <div style="display: flex;flex-direction: row;justify-content: space-around;width:100%;margin-top:2%;">
    <div class="choose_type" data-href='/buy/booking?id=<?=$id ?>' data-type=2 style="width:100px; height: 100px;border: 1px solid #866D8D;border-radius: 50px;background-color: #866D8D; margin-bottom: 1%;text-align: center;line-height: 100px;color:white;font-size: 18px;">爱预约</div>

    <div class="choose_type" data-type=1 style="width:100px; height: 100px;border: 1px solid #53a93f;border-radius: 50px;background-color: #53a93f; margin-bottom: 1%;text-align: center;line-height: 100px;color:white;font-size: 18px;" data-href='/buy/?id=<?=$id ?>'>买现货</div>
  </div>

</div>
