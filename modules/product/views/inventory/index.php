<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '库存管理';

MsaView::registerJsFile($this,'/js/product/inventory/index.js', 
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
                <span class='widget-caption'>商品列表</span>
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
                    <a class='btn btn-sm btn-primary' href='javascript:void(0);' id='add' style="height:30px !important;margin-right: 10px;line-height: 20px;">调整库存</a>
                    <div class="form-group" style='width:20%;margin-right: 10px;'>
                        <span class="input-icon">
                            <input type="text" class="form-control input-sm" name='query' value='' id='query'>
                            <i class="glyphicon glyphicon-search blue"></i>
                        </span>
                    </div>
                    <select name='product_id' id="product_id" style='width:100px;margin-right:10px;height:30px;'>
                        <?php foreach($products as $product) { ?>
                        <option value="<?=$product['id'] ?>"><?=$product['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <table id='list' class='hover grid' width='100%' cellspacing='0'>
                    <thead>
                        <tr class='table-title' role='row'>
                            <th>ID</th>
                            <th>名称</th>
                            <th>库存</th>
                            <th>当前库存</th>
                            <th>价格</th>
                            <th>当前价格</th>
                            <th>操作项目</th>
                            <th>操作人</th>
                            <th>备注</th>
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
                <div class='row'>
                    <div class='col-md-12' style="margin-top: 10px;">
                        <label style='width:80px'>商品：</label>
                        <select name='pid' style='width:350px;margin-right:12px;height:30px;'>
                            <?php foreach($products as $product) { ?>
                            <option value="<?=$product['id'] ?>"><?=$product['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class='col-md-12' style="margin-top: 10px;">
                        <label style='width:80px'>事项：</label>
                        <select name='operator' style='width:350px;margin-right:12px;height:30px;'>
                            <option value="1">进货</option>
                            <option value="2">损耗</option>
                            <option value="3">赠送</option>
                            <option value="4">线下销售</option>
                        </select>
                    </div>

                    <div class='col-md-12' style="margin-top: 10px;">
                        <label style='width:80px'>数量：</label>
                        <input style='width:350px;display: inline-block;' type="number" class="form-control input-sm" name='num' value='' placeholder="整数" />
                    </div>

                    <div class='col-md-12' style="margin-top: 10px;">
                        <label style='width:80px'>成本价：</label>
                        <input style='width:350px;display: inline-block;' type="number" class="form-control input-sm" name='price' value='' placeholder="成本价" />
                    </div>

                    <div class='col-md-12' style="margin-top: 10px;">
                        <label style='width:80px'>备注：</label>
                        <textarea name="memo" style='width:350px;display: inline-block;height:50px;'></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>