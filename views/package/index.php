<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '订制套餐';

MsaView::registerJsFile($this,'/js/package/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);
?>

<style type="text/css">
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

<div style="margin-top: 1%;">
    <div class="card" style="margin: 1%;">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        私人订制套餐规则
      </div>

      <div id="rule" style="margin-top: 1%;">
        <ul>
          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;绝对新鲜：
            </div>
            <div>
              早上在水果批发市场进货，上午和下午打包发货，预计下午或隔日送达
            </div>
          </li>

          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;水果丰富：
            </div>
            <div>
            根据时令动态调整，目前支持12种水果, 如有特殊需求请联系我们
            </div>
          </li>

          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;预约限制：
            </div>
            <div>
            为保持水果新鲜度，仅接受<span style="color:red;font-weight: bold;">大成都</span>地区的订单，且<span style="color:red;font-weight: bold;">周三、周六</span>可发货，后期可增加进/发货日
            </div>
          </li>

          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;价格优惠：
            </div>
            <div>
              价格享其它优惠后，再享<span style="color:red;font-weight: bold;">9</span>折，订单金额单次满<span style="color:red;font-weight: bold;">69</span>元，免运费，少于则加收<span style="color:red;font-weight: bold;">6</span>元快递费，单次金额不低于<span style="color:red;font-weight: bold;">39</span>元
            </div>
          </li>

          <li>
            <div class="label">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;快递收货：
            </div>
            <div>收货后如发现水果损坏等情况，请拍照后<a href="/contact">联系我们</a>，进行赔偿</div>
          </li>
        </ul>
    </div>

    <a class='btn btn-success' href='/package/order' id='order' style="width:30%;margin: 0 auto;margin-bottom: 2%;margin-top:-2px;">立即预订</a>
</div>
