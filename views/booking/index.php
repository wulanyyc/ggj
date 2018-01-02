<?php 
use yii\web\View;
use app\assets\SiteAsset;

$this->title = '预约规则';

SiteAsset::register($this);
?>

<style type="text/css">
  .card {
    border-radius: 0;
    border: none;
    border-bottom: 1px solid #eee;
    border-top: 1px solid #eee;
    margin: 5px 5px;
  }

  .label {
    display: inline-block;
    width: 100px;
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
</style>

<div class="card">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
    预约规则
  </div>

  <div id="rule" style="margin-top: 1%;">
    <ul>
      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;绝对新鲜：
        </div>
        <div>
          早上在水果市场进货，上午打包，顺丰发货，预计当天下午或隔日上午送达。
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;水果丰富：
        </div>
        <div>
        根据时令动态调整，如有特殊需求请联系我们。
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;预约限制：
        </div>
        <div>
        为保证水果新鲜度，仅接受<span style="color:red;font-weight: bold;">大成都</span>地区的预约订单，目前仅<span style="color:red;font-weight: bold;">周二、周四、周六</span>可发预约的订单，后期可增加发货日。
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;价格优惠：
        </div>
        <div>
          价格享其它优惠后，全部再享<span style="color:red;font-weight: bold;"><?=$bookingDiscount ?></span>折，订单金额单次满<span style="color:red;font-weight: bold;"><?=$bookingGod ?></span>元，免运费，少于则加收<span style="color:red;font-weight: bold;"><?=$expressFee ?></span>元运费，单次预约金额不低于<span style="color:red;font-weight: bold;"><?=$bookingLimit ?></span>元。
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;快递收货：
        </div>
        <div>收货后如发现水果有损坏等情况，请拍照后立即<a href="/contact">联系我们</a>，进行赔偿。</div>
      </li>
    </ul>
</div>

<a class='btn btn-success' href='/buy/booking' id='order' style="width:30%;margin: 0 auto;margin-bottom: 2%;margin-top:-2px;">立即预约</a>
