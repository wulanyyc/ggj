<?php 
use yii\web\View;
use app\components\MsaView;
use app\widgets\UnitWidget;
use app\widgets\CategoryWidget;

$this->title = '用户列表';

MsaView::registerJsFile($this,'/js/product/customer/index.js', 
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
                <span class='widget-caption'>用户列表</span>
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
                <div id="search_form" style='display: flex;justify-content: flex-start;flex-direction: row;'>
                    <a class='btn btn-sm btn-primary' href='javascript:void(0);' id='add' style="height:30px !important;margin-right: 10px;line-height: 20px;">添加用户</a>
                    <div class="form-group" style='width:20%;margin-right: 10px;'>
                        <span class="input-icon">
                            <input type="text" class="form-control input-sm" name='query' value='' id='query'>
                            <i class="glyphicon glyphicon-search blue"></i>
                        </span>
                    </div>

                    <select name='status' id="search_status" style='width:100px;margin-right:10px;height:30px;'>
                        <option value="0">全部</option>
                        <option value="1">已激活</option>
                        <option value="2">未激活</option>
                    </select>
                </div>
                <table id='list' class='hover grid' width='100%' cellspacing='0'>
                    <thead>
                        <tr class='table-title' role='row'>
                            <th>ID</th>
                            <th>昵称</th>
                            <th>手机</th>
                            <th>余额</th>
                            <th>积分</th>
                            <th>状态</th>
                            <th>注册时间</th>
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
                    <label style='width:80px'>昵称：</label>
                    <input style='width:250px' type='text' placeholder='' name='nick' class='input-sm'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>手机号码：</label>
                    <input style='width:250px' type='num' placeholder='' name='phone' class='input-sm'></input>
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
                    <label style='width:80px'>昵称：</label>
                    <input style='width:250px' type='text' placeholder='' name='nick' class='input-sm nick'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>手机号码：</label>
                    <input style='width:250px' type='num' placeholder='' name='phone' class='input-sm phone'></input>
                </div>
            </form>
        </div>
    </div>
</div>

<div id='status_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <label style='width:80px'>状态：</label>
            <select name='status' style='width:350px;min-width:100px;margin-right:12px;'>
                <option value="1">已激活</option>
                <option value="2">未激活</option>
            </select>
        </div>
    </div>
</div>

<div id='money_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12' style="margin-top: 10px;">
            <label style='width:80px'>金额：</label>
            <input style='width:350px;display: inline-block;' type="number" class="form-control input-sm" name='money' value='' placeholder="数字" />
        </div>

        <div class='col-md-12' style="margin-top: 10px;">
            <label style='width:80px'>加/减：</label>
            <select name='operator' style='width:350px;margin-right:12px;'>
                <option value="plus">增加</option>
                <option value="minus">减少</option>
            </select>
        </div>

        <div class='col-md-12' style="margin-top: 10px;">
            <label style='width:80px'>备注：</label>
            <input style='width:350px;display: inline-block;' type="text" class="form-control input-sm" name='reason' value='' placeholder="" />
        </div>
    </div>
</div>
