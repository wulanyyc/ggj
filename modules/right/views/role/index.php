<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '权限-角色';

MsaView::registerJsFile($this,'/js/right/role/index.js', 
    [
        'position' => View::POS_END, 
        'depends' => [
            'app\assets\TableAsset',
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
                <span class='widget-caption'>角色列表</span>
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
                <div style='margin-bottom:10px;' class='buttons-preview'>
                    <a class='btn btn-sm btn-primary' href='javascript:void(0);' id='add'>添加</a>
                </div>
                <table id='list' class='bordered' width='100%' cellspacing='0'>
                    <thead>
                        <tr class='table-title' role='row'>
                            <th>ID</th>
                            <th>名称</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?=$table;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class='row'>
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <div class='widget'>
            <div class='widget-header '>
                <span class='widget-caption'>人员列表</span>
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
                <div style='margin-bottom:10px;' class='buttons-preview'>
                    <label>角色：</label>
                    <select id='cur_role' style='width:20%;min-width:160px;margin-right:12px;'>
                        <?=$roleSelectHtml; ?>
                    </select>
                    
                    <a class='btn btn-sm btn-primary' href='javascript:void(0);' id='add_user'>添加用户</a>
                    
                    <div class="form-group" style='width:30%;display:inline-block;position:absolute;top:48px;right:30px;'>
                        <span class="input-icon">
                            <input type="text" class="form-control input-sm" name='query' value='' id='query'>
                            <i class="glyphicon glyphicon-search blue"></i>
                        </span>
                    </div>
                </div>
                <table id='user_list' class='bordered' width='100%' cellspacing='0'>
                    <thead>
                        <tr class='table-title' role='row'>
                            <th>ID</th>
                            <th>用户名称</th>
                            <th>创建时间</th>
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
                    <label style='width:50px'>名称：</label>
                    <input style='width:250px' type='text' placeholder='' name='name' class='input-sm'></input>
                </div>
            </form>
        </div>
    </div>
</div>

<div id='mod_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <form name='mod_form' action='#' method='post' style='margin-left:10px' onsubmit='return false;'>
                <?=$module;?>
            </form>
        </div>
    </div>
</div>

<div id='user_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <form name='add_user_form' action='#' method='post' style='margin-left:10px' onsubmit='return false;'>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:60px'>用户：</label>
                    <input type="hidden" class='rid' name='rid' value='' />
                    <select name='uid[]' multiple='multiple' style='width:450px;margin-right:12px;'>
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>
