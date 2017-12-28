<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\models\Address;


class AddressController extends Controller
{
    public $layout = 'wap';

    private $configKeys = [
        'current-skin',
    ];

    public function init() {
        $this->layout = SiteHelper::getLayout();
    }

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        if (!SiteHelper::checkSecret()) {
            Yii::$app->controller->redirect('/customer/login');
        } else {
            $phone = $_COOKIE['userphone'];
            $address = Address::find()->where(['userphone' => $phone])->orderBy('id desc')->asArray()->all();

            return $this->render('index', [
                'controller' => Yii::$app->controller->id,
                'address' => $address,
                'citymap' => Yii::$app->params['citymap']['成都'],
            ]);
        }
    }

    public function actionHtml() {
        $phone = isset($_COOKIE['userphone']) ? $_COOKIE['userphone'] : '';

        $address = Address::find()->where(['userphone' => $phone])->orderBy('id desc')->asArray()->all();
        
        $html = '';
        foreach($address as $item) {
            $html .= <<<EOF
            <div class="all_address_item">
              <div style="width: 80%;" class="address-content" data-id="{$item['id']}">
                <p style="font-weight: bold;color:#333;">
                  {$item['rec_name']}
                  <span id="rec_phone" style="padding-left: 5px;">{$item['rec_phone']}</span>
                  &nbsp;
                  <span class="border border-success text-success" style="font-size: 12px;padding:0px 10px;">{$item['label']}</span>
                </p>
                <p style="color:#a3a3a3;display: flex;flex-direction: row;justify-content: flex-start;">
                  <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                  <span>&nbsp;{$item['rec_city']}{$item['rec_district']}{$item['rec_detail']}</span>
                </p>
              </div>

              <div data-id="{$item['id']}" class="edit_address_item" style="width: 10%">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </div>

              <div data-id="{$item['id']}" class="del_address_item" style="width: 10%">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
              </div>
          </div>
EOF;
        }

        echo $html;
    }

    public function actionCarthtml() {
        $phone = isset($_COOKIE['userphone']) ? $_COOKIE['userphone'] : '';

        $address = Address::find()->where(['userphone' => $phone])->orderBy('id desc')->asArray()->all();
        
        $html = '';
        foreach($address as $item) {
            $html .= <<<EOF
            <div class="all_address_item">
              <div style="width: 10%" class="address-status" data-id="{$item['id']}"><i class="fa fa-check-square-o" aria-hidden="true"></i></div>
              <div style="width: 70%;" class="address-content" data-id="{$item['id']}">
                <p style="font-weight: bold;color:#333;">
                  {$item['rec_name']}
                  <span id="rec_phone" style="padding-left: 5px;">{$item['rec_phone']}</span>
                  &nbsp;
                  <span class="border border-success text-success" style="font-size: 12px;padding:0px 10px;">{$item['label']}</span>
                </p>
                <p style="color:#a3a3a3;display: flex;flex-direction: row;justify-content: flex-start;">
                  <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                  <span>&nbsp;{$item['rec_city']}{$item['rec_district']}{$item['rec_detail']}</span>
                </p>
              </div>

              <div data-id="{$item['id']}" class="edit_address_item" style="width: 10%">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </div>

              <div data-id="{$item['id']}" class="del_address_item" style="width: 10%">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
              </div>
          </div>
EOF;
        }

        return $html;
    }

    public function actionAdd() {
        if (!SiteHelper::checkSecret()) {
            echo '验证用户失败';
            Yii::$app->end();
        }

        $phone = $_COOKIE['userphone'];
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
            echo '收货人手机格式不正确';
            Yii::$app->end();
        }

        $params['userphone'] = $phone;

        foreach($params as $key => $value) {
            $ar->$key = $value;
        }

        if($ar->save()){
            echo $ar->id;
        }else{
            echo '提交的数据不完整';
        }
    }

    public function actionInfo() {
        if (!SiteHelper::checkSecret()) {
            echo '验证用户失败';
            Yii::$app->end();
        }

        $phone = $_COOKIE['userphone'];
        $params = Yii::$app->request->get();
        $id = $params['id'];

        $data = Address::find()->where(['userphone' => $phone, 'id' => $id])->asArray()->one();

        echo json_encode($data);
    }

    public function actionDel() {
        if (!SiteHelper::checkSecret()) {
            echo '验证用户失败';
            Yii::$app->end();
        }

        $phone = $_COOKIE['userphone'];
        $params = Yii::$app->request->post();
        $id = $params['id'];

        $ar = Address::findOne($params['id']);

        if($ar->delete()){
            echo 'ok';
        }else{
            echo '删除失败';
        }
    }
}
