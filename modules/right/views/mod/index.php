<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '权限-模块';

MsaView::registerJsFile($this,'/js/right/mod/index.js',
    [
        'position' => View::POS_END,
        'depends' => [
            'app\assets\TableAsset',
            'app\assets\BootBoxAsset',
            'app\assets\SelectAsset',
        ]
    ]
);

?>

<style>
<!--
.btn-align{
    margin-top:5px;
}
-->
</style>
<div class='row'>
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <div class='widget'>
            <div class='widget-header '>
                <span class='widget-caption'>菜单配置</span>
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
                    <a class='btn btn-sm btn-primary' href='javascript:void(0);' id='add_menu'>添加主菜单</a>
                </div>
                <table id='list_menu' class='bordered' width='100%' cellspacing='0'>
                    <thead>
                        <tr class='table-title' role='row'>
                            <th>ID</th>
                            <th>名称</th>
                            <th>显示顺序</th>
                            <th>布局</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?=$menuTable; ?>
                    </tbody>
                </table>
                <hr/>
                <div style='margin-bottom:10px;' class='buttons-preview'>
                    <label>主菜单：</label>
                    <select id='cur_menu' style='width:20%;min-width:160px;margin-right:12px;'>
                        <?=$menuSelectHtml; ?>
                    </select>
                    <a class='btn btn-sm btn-primary' href='javascript:void(0);' id='add'>添加子菜单</a>
                </div>
                <table id='list' class='bordered' width='100%' cellspacing='0'>
                    <thead>
                        <tr role='row'>
                            <th>ID</th>
                            <th>名称</th>
                            <th>类型</th>
                            <th>链接</th>
                            <th>icon css</th>
                            <th>module</th>
                            <th>controller</th>
                            <th>组</th>
                            <th>显示顺序</th>
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
<div id='data_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <form name='add_form' action='#' method='post' style='margin-left:10px' onsubmit='return false;'>
                <label style='width:90px'>节点类型：</label>
                <select name='type' style='width:160px;min-width:100px;margin-right:12px;'>
                    <option value='node'>普通节点</option>
                    <option value='group_parent'>组-父节点</option>
                    <option value='group_node'>组-子节点</option>
                    <option value='other'>其它</option>
                </select>

                <br/><br/>
                <label style='width:90px'>主菜单：</label>
                <select name='menu_id' style='width:160px;min-width:100px;margin-right:12px;'>
                    <?=$menuSelectHtml; ?>
                </select>

                <div class='form-group' style='margin-top:10px'>
                    <label style='width:90px'>名称：</label>
                    <input style='width:200px' type='text' placeholder='' name='text' class='input-sm'></input>
                    <span style='color:gray'>例: 首页</span>
                </div>

                <div class='form-group' style='margin-top:10px'>
                    <label style='width:90px'>icon css：</label>
                    <input style='width:200px' type='text' placeholder='' name='css' class='input-sm'></input>
                    <span style='color:gray'>例: glyphicon glyphicon-time</span>
                </div>

                <div class='form-group' style='margin-top:10px'>
                    <label style='width:90px'>链接：</label>
                    <input style='width:200px' type='text' placeholder='' name='link' class='input-sm'></input>
                    <span style='color:gray'>格式: /*, 例: /site/index</span>
                </div>

                <div class='form-group' style='margin-top:10px'>
                    <label style='width:90px'>module：</label>
                    <input style='width:200px' type='text' placeholder='' name='module' class='input-sm'></input>
                    <span style='color:gray'>格式：字母，非module开发默认为 <span style='color:red'>basic</span></span>
                </div>

                <div class='form-group' style='margin-top:10px'>
                    <label style='width:90px'>controller：</label>
                    <input style='width:200px' type='text' placeholder='' name='controller' class='input-sm'></input>
                    <span style='color:gray'>格式：字母</span>
                </div>

            </form>
        </div>
    </div>
</div>

<div id='parent_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <label style='width:60px'>父节点：</label>
            <select name='group_parent_id' style='width:200px;min-width:100px;margin-right:12px;'>
                <?=$groupParentHtml;?>
            </select>
        </div>
    </div>
</div>

<div id='role_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <label style='width:60px'>角色：</label>
            <select name='role' multiple='multiple' style='width:250px;min-width:100px;margin-right:12px;'>
                <?=$roleHtml;?>
            </select>
        </div>
    </div>
</div>

<div id='order_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <label style='width:60px'>顺序：</label>
            <select name='menu_order' style='width:250px;min-width:100px;margin-right:12px;'>
                <?=$orderHtml;?>
            </select>
        </div>
    </div>
</div>

<div id='order_sub_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <label style='width:60px'>顺序：</label>
            <select name='menu_sub_order' style='width:250px;min-width:100px;margin-right:12px;'>
            </select>
        </div>
    </div>
</div>

<div id='menu_order_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <label style='width:60px'>顺序：</label>
            <select name='menu_order' style='width:250px;min-width:100px;margin-right:12px;'>
                <?=$menuOrderHtml;?>
            </select>
        </div>
    </div>
</div>

<div id='menu_data_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <form name='add_form' action='' method='post' style='margin-left:10px' onsubmit='return false;'>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:90px'>名称：</label>
                    <input style='width:200px' type='text' placeholder='' name='text' class='input-sm'></input>
                    <span style='color:gray'>例: 核心数据</span>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:90px'>布局：</label>
                    <select name='type' style='width:200px;min-width:100px;margin-right:12px;'>
                        <option value='left_menu'>左侧菜单</option>
                        <option value='full_screen'>全屏</option>
                    </select>
                    <span style='color:gray'>全屏，子菜单只有一个</span>
                </div>
            </form>
        </div>
    </div>
</div>
