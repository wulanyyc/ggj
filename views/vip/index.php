<?php 
use yii\web\View;
use app\assets\SiteAsset;

$this->title = '无忧套餐-私人订制';

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
    color: #53a93f;
    /*width:38%;*/
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
    justify-content: flex-start;
  }

</style>

<div class="card">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
    无忧套餐
  </div>

  <div id="rule" style="margin-top: 1%;">
    <ul>
      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;质优：
        </div>
        <div style="width:65%;">
          挑选平台果品最优的水果
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;省事：
        </div>
        <div style="width:65%;">
          合理补充营养，精心搭配。每周不一样，一次购买，多次享受
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;超值：
        </div>
        <div style="width:65%;">
          <?=$price ?>元含3种及以上进口水果，2种及以上时令水果
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;客服：
        </div>
        <div style="width:65%;">
          专职客服，及时响应您的需求
        </div>
      </li>

      <li>
        <div class="label">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;快递：
        </div>
        <div style="width:65%;">
        限<span style="color:red;font-weight: bold;">成都</span>地区，每周发1次货，周1、3、5任选一天，隔日达。下单后客服跟进具体的发货日期
        </div>
      </li>
    </ul>
  </div>
</div>

<br/>
<a class='btn btn-success' href='/buy/booking/?id=<?=$id ?>' id='order' style="width:30%;margin-left:35%;margin-bottom: 1%;">立即购买</a>
<br/>

<br/>
