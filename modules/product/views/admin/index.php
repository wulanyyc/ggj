<?php 
use yii\web\View;
use app\components\MsaView;
use app\widgets\UnitWidget;
use app\widgets\CategoryWidget;
use app\widgets\FreshWidget;

$this->title = '商品列表';

MsaView::registerJsFile($this,'/js/product/admin/index.js', 
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
                    <a class='btn btn-sm btn-primary' href='javascript:void(0);' id='add' style="height:30px !important;margin-right: 10px;line-height: 20px;">添加商品</a>
                    <div class="form-group" style='width:20%;margin-right: 10px;'>
                        <span class="input-icon">
                            <input type="text" class="form-control input-sm" name='query' value='' id='query'>
                            <i class="glyphicon glyphicon-search blue"></i>
                        </span>
                    </div>

                    <select name='status' id="search_status" style='width:100px;margin-right:10px;height:30px;'>
                        <option value="0">全部</option>
                        <option value="1">销售中</option>
                        <option value="3">待上线</option>
                        <option value="2">下线</option>
                    </select>

                    <select name='booking_status' id="search_booking" style='width:100px;height:30px;'>
                        <option value="0">全部</option>
                        <option value="1">现货</option>
                        <option value="2">预约</option>
                    </select>
                </div>
                <table id='list' class='hover grid' width='100%' cellspacing='0'>
                    <thead>
                        <tr class='table-title' role='row'>
                            <th>ID</th>
                            <th>名称</th>
                            <th>商家</th>
                            <th>价格</th>
                            <th>库存</th>
                            <th>单位</th>
                            <th>种类</th>
                            <th>描述</th>
                            <th>标语</th>
                            <th>状态</th>
                            <th>预约状态</th>
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
                    <label style='width:80px'>名称：</label>
                    <input style='width:250px' type='text' placeholder='' name='name' class='input-sm'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>商家：</label>
                    <select name="seller_id">
                        <?=$sellerHtml ?>
                    </select>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>价格：</label>
                    <input style='width:250px' type='text' placeholder='' name='price' class='input-sm'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>库存：</label>
                    <input style='width:250px' type='number' placeholder='' name='num' class='input-sm'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>单位：</label>
                    <?= UnitWidget::widget() ?>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>新鲜度：</label>
                    <?= FreshWidget::widget() ?>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>单次购买限制：</label>
                    <input style='width:250px' type='text' placeholder='' name='buy_limit' class='input-sm buy_limit'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>品类：</label>
                    <?= CategoryWidget::widget() ?>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>描述：</label>
                    <input style='width:250px' type='text' placeholder='' name='desc' class='input-sm'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>宣传语：</label>
                    <input style='width:250px' type='text' placeholder='' name='slogan' class='input-sm' maxlength="20"></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>主图地址：</label>
                    <input style='width:250px' type='text' placeholder='' name='img' class='input-sm img'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>副图地址1：</label>
                    <input style='width:250px' type='text' placeholder='' name='img1' class='input-sm img1'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>副图地址2：</label>
                    <input style='width:250px' type='text' placeholder='' name='img2' class='input-sm img2'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>副图地址3：</label>
                    <input style='width:250px' type='text' placeholder='' name='img3' class='input-sm img3'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>推荐：</label>
                    <select name="recflag" class="recflag">
                        <option value=0>不推荐</option>
                        <option value=1>推荐</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>


<div id='edit_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <form name='edit_form' action='#' method='post' style='margin-left:10px' onsubmit='return false;'>
                <!-- <input type='hidden' name='id' class='input-sm'></input> -->
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>名称：</label>
                    <input style='width:250px' type='text' placeholder='' name='name' class='input-sm name'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>商家：</label>
                    <select name="seller_id" class="seller_id">
                        <?=$sellerHtml ?>
                    </select>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>价格：</label>
                    <input style='width:250px' type='text' placeholder='' name='price' class='input-sm price'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>库存：</label>
                    <input style='width:250px' type='number' placeholder='' name='num' class='input-sm num'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>单位：</label>
                    <?= UnitWidget::widget() ?>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>新鲜度：</label>
                    <?= FreshWidget::widget() ?>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>单次购买限制：</label>
                    <input style='width:250px' type='text' placeholder='' name='buy_limit' class='input-sm buy_limit'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>品类：</label>
                    <?= CategoryWidget::widget() ?>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>描述：</label>
                    <input style='width:250px' type='text' placeholder='' name='desc' class='input-sm desc'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>宣传语：</label>
                    <input style='width:250px' type='text' placeholder='' name='slogan' class='input-sm slogan' maxlength="20"></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>主图地址：</label>
                    <input style='width:250px' type='text' placeholder='' name='img' class='input-sm img'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>副图地址1：</label>
                    <input style='width:250px' type='text' placeholder='' name='img1' class='input-sm img1'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>副图地址2：</label>
                    <input style='width:250px' type='text' placeholder='' name='img2' class='input-sm img2'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>副图地址3：</label>
                    <input style='width:250px' type='text' placeholder='' name='img3' class='input-sm img3'></input>
                </div>
                <div class='form-group' style='margin-top:10px'>
                    <label style='width:80px'>推荐：</label>
                    <select name="recflag" class="recflag">
                        <option value=0>不推荐</option>
                        <option value=1>推荐</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>

<div id='tag_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <label style='width:80px'>标签：</label>
            <select name='tag' multiple='multiple' style='width:350px;min-width:100px;margin-right:12px;'>
                <?=$tagHtml;?>
            </select>
        </div>
    </div>
</div>

<div id='status_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <label style='width:80px'>销售状态：</label>
            <select name='status' style='width:350px;min-width:100px;margin-right:12px;'>
                <option value="1">销售中</option>
                <option value="2">已下线</option>
                <option value="3">待上线</option>
            </select>
        </div>
    </div>
</div>

<div id='booking_status_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12'>
            <label style='width:80px'>销售方式：</label>
            <select name='booking_status' style='width:350px;min-width:100px;margin-right:12px;'>
                <option value="1">现货</option>
                <option value="2">预约</option>
                <!-- <option value="3">仅现货</option> -->
            </select>
        </div>
    </div>
</div>

<div id='connect_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <form name='connect_form' action='#' method='post' style='margin-left:10px' onsubmit='return false;'>
            <?php foreach($products as $product) { ?>
            <div class='col-md-3'>
                <div class="form-group">
                    <label for="product_<?=$product['id'] ?>"><?=$product['name'] ?></label>
                    <input type="number" class="form-control connect-item product_<?=$product['id'] ?>" name="product_<?=$product['id'] ?>" placeholder="数量" value="" />
                </div>
            </div>
            <?php } ?>
        </form>
    </div>
</div>

<div id='inventory_modal' style='display:none;min-width:600px'>
    <div class='row'>
        <div class='col-md-12' style="margin-top: 10px;">
            <label style='width:80px'>事项：</label>
            <select name='operator' style='width:350px;margin-right:12px;'>
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
    </div>
</div>
