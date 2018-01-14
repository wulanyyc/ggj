<?php 
use yii\web\View;
use app\components\MsaView;
use app\widgets\UnitWidget;
use app\widgets\CategoryWidget;

$this->title = '订单列表';

MsaView::registerJsFile($this,'/js/product/order/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\BootBoxAsset',
            'app\assets\SelectAsset',
            'app\assets\GridAsset',
            'app\assets\DatePickerAsset',
        ]
    ]
);

?>
<div class='row'>
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <div class='widget'>
            <div class='widget-header '>
                <span class='widget-caption'>套餐列表</span>
                <div class='widget-buttons'>
                    <a data-toggle='maximize' href='#'>
                        <i class='fa fa-expand'></i>
                    </a>
                    <a data-toggle='collapse' href='#'>
                        <i class='fa fa-minus'></i>
                    </a>
                </div>
            </div>
            <div class='widget-body'>
                <div style='margin-bottom:10px;position:relative;display: flex;flex-direction: row;flex-wrap: wrap;' class='buttons-preview'>
                    <div class="form-group" style='width:30%;display:inline-block;'>
                        <span class="input-icon">
                            <input type="text" placeholder="收件人" class="form-control input-sm" name='query' value='' id='query'>
                            <i class="glyphicon glyphicon-search blue"></i>
                        </span>
                    </div>

                    <label style="padding-left: 10px;">日期：</label>
                    <div id="date-container">
                        <div class="input-daterange input-group" id="datepicker" style="width:300px;margin-left:10px;">
                            <input type="text" class="input-sm form-control" name="start" id="start_date"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" name="end" id="end_date" />
                        </div>
                    </div>

                    <label style="padding-left: 10px;">状态：</label>
                    <div class="form-group" style='display:inline-block;'>
                        <select name='status' id='status' style='width:100px;min-width:100px;margin-right:12px;'>
                            <option value=''>全部</option>
                            <?php foreach($status as $key => $value) { ?>
                                <option value=<?=$key ?>><?=$value ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <label style="padding-left: 10px;">类型：</label>
                    <div class="form-group" style='display:inline-block;'>
                        <select name='order_type' id='order_type' style='width:100px;min-width:100px;margin-right:12px;'>
                            <option value=''>全部</option>
                            <option value='1'>普通购买</option>
                            <option value='2'>预约</option>
                        </select>
                    </div>
                </div>
                <table id='list' class='hover grid' width='100%' cellspacing='0'>
                    <thead>
                        <tr class='table-title' role='row'>
                            <th>ID</th>
                            <th>下单号码</th>
                            <th>收件人</th>
                            <th>收件人号码</th>
                            <th>总价</th>
                            <th>收件地址</th>
                            <th>状态</th>
                            <th>下单时间</th>
                            <th>快递公司</th>
                            <th>快递单号</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- <div id='add_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <form name='add_form' action='#' method='post' style='margin-left:10px' onsubmit='return false;'>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>名称：</label>
                    <input style='width:250px' type='text' placeholder='' name='name' class='input-sm'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>描述：</label>
                    <input style='width:250px' type='text' placeholder='' name='desc' class='input-sm'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>宣传语：</label>
                    <input style='width:250px' type='text' placeholder='' name='slogan' class='input-sm'></input>
                </div>
            </form>
        </div>
    </div>
</div> -->


<div id='edit_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <form name='edit_form' action='#' method='post' style='margin-left:10px' onsubmit='return false;'>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>名称：</label>
                    <input style='width:250px' type='text' placeholder='' name='rec_name' class='input-sm rec_name'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>手机号码：</label>
                    <input style='width:250px' type='text' placeholder='' name='rec_phone' class='input-sm rec_phone'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>地址：</label>
                    <textarea style='width:250px' name='rec_address' class='input-sm rec_address'></textarea>
                </div>
            </form>
        </div>
    </div>
</div>

<div id='express_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <div class='form-group' style='margin-top:10px'>
                <label style='width:80px'>快递公司：</label>
                <select name="express_company" class="input-sm express_company">
                    <option value="SF">顺丰</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div id='status_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <label style='width:80px'>状态：</label>
            <select name='status' style='width:350px;min-width:100px;margin-right:12px;'>
                <?php foreach($status as $key => $value) { ?>
                    <option value=<?=$key ?>><?=$value ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
