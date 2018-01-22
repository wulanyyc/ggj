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
<div class="card" id="suc" style="background: url('http://img.guoguojia.vip/img/payok_adjust.jpeg') no-repeat;background-size: 100% 100%;">
  <div style="position: absolute; bottom: 30px;width: 100%;">
    <div style="padding: 10px;margin:20px;text-align: center;">
      <div style="margin-left:10px;font-size: 14px;">
        <div class="text-light">支付方式：<?=$data['pay_type'] ?></div>
        <div class="text-light">支付金额：¥<?=$data['online_money'] ?></div>
        <div class="text-light">赠送积分：<?=round($data['online_money']) ?></div>
      </div>
    </div>
    <a href="/order?type=2" class="btn btn-outline-light btn-sm" style="width:66%;margin-left:17%;">查看订单</a>
    <button type="button" class="btn btn-danger btn-sm" id="change" style="width:66%;margin-left:17%;margin-top:10px;">积分兑换</button>
    <br/>
  </div>
</div>
<?php } else { ?>
<div class="card" id="fail">
  <div style="width:300px;margin-top:50px;margin-bottom: 30px;text-align: center;display: table-cell;vertical-align: middle;">
    <img src="/img/cry.png" />
    <div style="padding-left:10px;width:70%">您的订单号：<?=$data['id'] ?><br/>请确认支付后，刷新订单或<a href="/contact">联系客服</a></div>
  </div>
  <button type="button" class="btn btn-danger btn-sm" id="refresh" data-id="<?=$data['id'] ?>" style="width:66%;margin-left:17%;">刷新订单</button>
  <a href="/order?type=1" class="btn btn-outline-success btn-sm" style="width:66%;margin-top:10px;margin-left:17%;">查看订单</a>
</div>
<?php } ?>

