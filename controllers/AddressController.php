<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\models\Address;
use app\models\Customer;
use app\filters\CustomerFilter;
use app\filters\WechatFilter;

class AddressController extends Controller
{
    public $layout = 'wap';

    private $configKeys = [
        'current-skin',
    ];

    public function init() {
        $this->layout = SiteHelper::getLayout();
    }

    public function behaviors() {
        return [
            'customer' => [
                'class' => CustomerFilter::className(),
                'actions' => [

                ]
            ],
            'wechat' => [
                'class' => WechatFilter::className(),
            ],
        ];
    }

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        $cid = SiteHelper::getCustomerId();
        $address = Address::find()->where(['customer_id' => $cid])->orderBy('id desc')->asArray()->all();

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'address' => $address,
            'city' => array_keys(Yii::$app->params['citymap']),
            'citymap' => Yii::$app->params['citymap']['成都市'],
        ]);
    }

    public function actionHtml() {
        $cid = SiteHelper::getCustomerId();
        $address = Address::find()->where(['customer_id' => $cid])->orderBy('id desc')->asArray()->all();
        
        $html = '';
        foreach($address as $item) {
            $html .= <<<EOF
            <div class="all_address_item" style="display: table;width: 100%;">
              <div style="width: 80%;display: table-cell;" class="address-content" data-id="{$item['id']}">
                <p style="font-weight: bold;color:#333;">
                  {$item['rec_name']}
                  <span id="rec_phone" style="padding-left: 5px;">{$item['rec_phone']}</span>
                  &nbsp;
                  <span class="border border-success text-success" style="font-size: 12px;padding:0px 10px;">{$item['label']}</span>
                </p>
                <p style="color:#a3a3a3;font-size:16px;">
                  <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                  <span>&nbsp;{$item['rec_city']}{$item['rec_district']}{$item['rec_detail']}</span>
                </p>
              </div>

              <div data-id="{$item['id']}" class="edit_address_item" style="width: 10%;display: table-cell;vertical-align: middle;">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </div>

              <div data-id="{$item['id']}" class="del_address_item" style="width: 10%;display: table-cell;vertical-align: middle;">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
              </div>
          </div>
EOF;
        }

        // echo $html;
        SiteHelper::render('ok', $html);
    }

    public function actionCarthtml() {
        $cid = SiteHelper::getCustomerId();
        $address = Address::find()->where(['customer_id' => $cid])->orderBy('id desc')->asArray()->all();
        
        $html = '';
        foreach($address as $item) {
            $html .= <<<EOF
            <div class="all_address_item">
              <div class="address-status" data-id="{$item['id']}">
                <i class="fa fa-check-square-o" aria-hidden="true"></i>
              </div>
              <div class="address-content" data-id="{$item['id']}">
                <p style="font-weight: bold;color:#333;">
                  {$item['rec_name']}
                  <span id="rec_phone" style="padding-left: 5px;">{$item['rec_phone']}</span>
                  &nbsp;
                  <span class="border border-success text-success" style="font-size: 12px;padding:0px 10px;">{$item['label']}</span>
                </p>
                <p style="color:#a3a3a3;" class="address-content-desc">
                  <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                  <span>&nbsp;{$item['rec_city']}{$item['rec_district']}{$item['rec_detail']}</span>
                </p>
              </div>

              <div data-id="{$item['id']}" class="edit_address_item">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </div>

              <div data-id="{$item['id']}" class="del_address_item">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
              </div>
          </div>
EOF;
        }

        // return $html;
        SiteHelper::render('ok', $html);
    }

    public function actionAdd() {
        $params = Yii::$app->request->post();
        $id = $params['id'];

        if (empty($id)) {
            $ar = new Address();
            unset($params['id']);
        } else {
            $ar = Address::findOne($id);
            unset($params['id']);
        }

        if (!SiteHelper::checkPhone($params['rec_phone'])) {
            SiteHelper::render('fail', '收货人手机格式不正确');
        }

        $params['customer_id'] =  SiteHelper::getCustomerId();;

        foreach($params as $key => $value) {
            $ar->$key = $value;
        }

        if($ar->save()){
            SiteHelper::render('ok', $ar->id);
        }else{
            SiteHelper::render('fail', '提交的数据不完整');
        }
    }

    public function actionInfo() {
        $cid = SiteHelper::getCustomerId();
        $params = Yii::$app->request->get();
        $id = $params['id'];

        $data = Address::find()->where(['customer_id' => $cid, 'id' => $id])->asArray()->one();

        // echo json_encode($data);
        SiteHelper::render('ok', $data);
    }

    public function actionDel() {
        $params = Yii::$app->request->post();
        $id = $params['id'];

        $ar = Address::findOne($params['id']);

        if($ar->delete()){
            // echo 'ok';
            SiteHelper::render('ok');
        }else{
            // echo '删除失败';
            SiteHelper::render('fail', '删除失败');
        }
    }

    public function actionGetcitymap() {
        $params = Yii::$app->request->post();
        $city = isset($params['city']) ? $params['city'] : '';

        if (empty($city)) {
            echo '';
            Yii::$app->end();
        } else {
            $districts = Yii::$app->params['citymap'][$city];
            if (empty($districts)) {
                echo '';
            } else {
                $html = '';
                foreach($districts as $item) {
                    $html .= '<option value="' . $item .'">' . $item . '</option>';
                }
                echo $html;
            }
        }
    }
}
