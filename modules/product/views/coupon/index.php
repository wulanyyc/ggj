<?php 
use yii\web\View;
use app\components\MsaView;
use app\widgets\UnitWidget;

$this->title = '商品-优惠券';

MsaView::registerJsFile($this,'/js/product/coupon/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\BootBoxAsset',
            'app\assets\SelectAsset',
            'app\assets\GridAsset',
        ]
    ]
);

?>
<div class='row'>
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <div class='widget'>
            <div class='widget-header '>
                <span class='widget-caption'>列表</span>
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
                <div style='margin-bottom:10px;position:relative;' class='buttons-preview'>
                    <a class='btn btn-sm btn-primary' href='javascript:void(0);' id='add'>添加</a>
                    <div class="form-group" style='width:30%;display:inline-block;position:absolute;top:-1px;left:55px;'>
                        <span class="input-icon">
                            <input type="text" class="form-control input-sm" name='query' value='' id='query'>
                            <i class="glyphicon glyphicon-search blue"></i>
                        </span>
                    </div>
                </div>
                <table id='list' class='hover grid' width='100%' cellspacing='0'>
                    <thead>
                        <tr class='table-title' role='row'>
                            <th>ID</th>
                            <th>名称</th>
                            <th>类型</th>
                            <th>金额</th>
                            <th>有效期（天）</th>
                            <th>描述</th>
                            <th>开始日期</th>
                            <th>结束日期</th>
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

<div id='add_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <form name='add_form' action='#' method='post' style='margin-left:10px' onsubmit='return false;'>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>名称：</label>
                    <input style='width:250px' type='text' placeholder='' name='name' class='input-sm'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>类型：</label>
                    <select style='width:250px' value="" name="type" class="type">
                        <option value=1>系统券</option>
                        <option value=2>用户券</option>
                        <option value=3>私有券</option>
                        <option value=4>活动券</option>
                    </select>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>优惠：</label>
                    <input style='width:250px' type='text' placeholder='' name='money' class='input-sm money'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>金额限制：</label>
                    <input style='width:250px' type='text' placeholder='' name='money_limit' class='input-sm money_limit'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>有效期：</label>
                    <input style='width:250px' type='number' placeholder='' name='day' class='input-sm day'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>描述：</label>
                    <input style='width:250px' type='text' placeholder='' name='desc' class='input-sm desc'></input>
                </div>
            </form>
        </div>
    </div>
</div>


<div id='edit_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <form name='edit_form' action='#' method='post' style='margin-left:10px' onsubmit='return false;'>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>名称：</label>
                    <input style='width:250px' type='text' placeholder='' name='name' class='input-sm name'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>类型：</label>
                    <select style='width:250px' value="" name="type" class="type">
                        <option value=1>系统券</option>
                        <option value=2>用户券</option>
                        <option value=3>私有券</option>
                    </select>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>优惠：</label>
                    <input style='width:250px' type='text' placeholder='' name='money' class='input-sm money'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>金额限制：</label>
                    <input style='width:250px' type='text' placeholder='' name='money_limit' class='input-sm money_limit'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>有效期：</label>
                    <input style='width:250px' type='number' placeholder='' name='day' class='input-sm day'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>描述：</label>
                    <input style='width:250px' type='text' placeholder='' name='desc' class='input-sm desc'></input>
                </div>
            </form>
        </div>
    </div>
</div>


<div id='give_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <form name='edit_form' action='#' method='post' style='margin-left:10px' onsubmit='return false;'>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:70px'>用户：</label>
                    <select name='cid[]' multiple='multiple' style='width:450px;margin-right:12px;'>
                        <?php foreach($customers as $customer) { ?>
                        <option value=<?=$customer['id'] ?>><?=$customer['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>