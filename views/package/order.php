<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '订制套餐';

MsaView::registerJsFile($this,'/js/package/buy.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);
?>

<style type="text/css">
  #items .form-check {
    padding-left: 5px;
    padding-top: 10px;
    width: 49%;
    display: inline-block;
    font-size: 18px;
  }

  .form-check-label {
    width: 120px;
  }

  #items .form-input {
    width:40px;border:none;text-align: center;
  }

  .operator {
    display: inline-flex;flex-direction: row;border:1px solid #ccc;border-radius: 8px;width:100px;
  }

  a.operator-left {
    width:30px;text-align: center;border-right: 1px solid #ccc;text-decoration: none;color:#000;
  }

  a.operator-right {
    width:30px;text-align: center;border-left: 1px solid #ccc;text-decoration: none;color:#000;
  }

  .list-group-item.active {
    background-color: #53a93f;
    border-color: #53a93f;
  }

</style>

<div style="margin-top: 1%;">
    <div class="card" style="margin: 1%;">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        私人订制
      </div>

      <div id="items" style="margin-top: 1%;display: flex;flex-wrap: nowrap;justify-content: center;">
        <div id="list-example" class="list-group" style="width:18%;">
          <a class="list-group-item list-group-item-action" href="#list-item-1">水果</a>
          <a class="list-group-item list-group-item-action" href="#list-item-2">套餐</a>
          <a class="list-group-item list-group-item-action" href="#list-item-3">工具</a>
          <!-- <a class="list-group-item list-group-item-action" href="#list-item-4">Item 4</a> -->
        </div>
        <div id="scroll" data-spy="scroll" data-target="#list-example" data-offset="0" class="scrollspy-example" style="position: relative;overflow-y: scroll;height: 450px;width:80%;padding-left: 2%;padding-right: 2%;"">
          <h4 id="list-item-1" style="height: 200px;">Item 1</h4>
          <p>...</p>
          <h4 id="list-item-2" style="height: 200px;">Item 2</h4>
          <p>...</p>
          <h4 id="list-item-3" style="height: 200px;">Item 3</h4>
          <p>...</p>
   <!--        <h4 id="list-item-4">Item 4</h4>
          <p>...</p> -->
        </div>
<!--           <div class="form-check">
            <label class="form-check-label">
              苹果&nbsp;
            </label>
            <span style="font-size: 12px;color:red;">2元/个</span>
            <div class="operator">
              <a class="operator-left" href="#">-</a>
              <input type="text" class="form-input" placeholder="0">
              <a class="operator-right" href="#">+</a>
            </div>
          </div> -->
      </div>
    </div>
</div>
