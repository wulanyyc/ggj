<?php 
use yii\web\View;
use app\assets\SiteAsset;

$this->title = '预约须知';

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
    width: 120px;
    color: #53a93f;
  }

  .item {
    padding-left: 20px;
    margin-bottom: 1%;
    display: flex;
    flex-direction: row;
  }

  #rule li {
    padding-left: 8px;
    padding-top: 8px;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-items: center;
  }

  .label {
    width:100px;
  }
</style>

<div class="card">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
    预约须知
  </div>

  <div id="rule" style="margin-top: 1%;">
    <ul>
      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;绝对新鲜：
        </div>
        <div>
          早上在水果市场进货，上午打包，下午快递发货（<span style="color:red;font-weight: bold;">隔日达</span>）。
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;水果丰富：
        </div>
        <div>
          品类根据时令动态调整，只卖好吃又健康的水果，如有特殊需求请联系我们。
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;价格实惠：
        </div>
        <div>
          低于市场价，并享受平台各种优惠。
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;预约限制：
        </div>
        <div>
        为保证水果新鲜度，仅接受<span style="color:red;font-weight: bold;">成都</span>地区的订单，<span style="color:red;font-weight: bold;">周一、三、五</span>发货，二、四、六到货。如遇缺货无法完成订单，立即退款。
        </div>
      </li>
    </ul>
  </div>
</div>

<a class='btn btn-success' href='/buy/booking' id='order' style="width:30%;margin-left:35%;margin-bottom: 1%;">立即预约</a>

<br/>
<br/>
