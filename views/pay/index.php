<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '支付详情';

MsaView::registerJsFile($this,'/js/pay/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
  footer {
    display: none;
  }

  .card {
      border-radius: 0;
      border: none;
  }

</style>

<?php if (isset($data['pay_result']) && $data['pay_result'] == 1){ ?>
<div class="card" id="suc" style="background: url('/img/payok.jpeg') no-repeat;background-size: 100% 100%;">
  <div style="position: absolute; bottom: 30px;width: 100%;">
    <div style="display: flex;flex-direction: column;justify-content: center;align-items: center;padding: 10px;margin:20px;">
      <div style="margin-left:10px;font-size: 14px;">
        <div class="text-light">支付方式：<?=$data['pay_type'] ?></div>
        <div class="text-light">支付金额：¥<?=$data['online_money'] ?></div>
        <div class="text-light">赠送积分：<?=round($data['online_money']) ?></div>
      </div>
    </div>
    <a href="/order?type=2" class="btn btn-outline-light btn-sm" style="width:66%;margin-left:17%;">查看订单</a>
    <button type="button" class="btn btn-danger btn-sm" id="order" style="width:66%;margin-left:17%;margin-top:10px;">积分兑换</button>
    <br/>
  </div>
</div>
<?php } else { ?>
<div class="card" id="fail">
  <div class="card-header bg-white" style="text-align:center;position:relative;color: black;border-radius: 0;border-bottom: 1px solid #f5f5f5;">
        订单支付状态未知
  </div>
  <div style="display: flex;flex-direction: row;justify-content: center;align-items: center;padding: 10px;margin:10px;">
    <img src="/img/cry.png" />
    <div style="padding-left:10px;width:70%">请确认支付状态后，刷新订单</div>
  </div>
  <button type="button" class="btn btn-danger btn-sm" id="refresh" data-id="<?=$data['id'] ?>" style="width:66%;margin-left:17%;">刷新订单状态</button>
  <a href="/order?type=1" class="btn btn-outline-success btn-sm" style="width:66%;margin-left:17%;margin-top:10px;">查看订单</a>
  <br/>
</div>
<?php } ?>

