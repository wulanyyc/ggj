<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\components\PriceHelper;
use app\models\Customer;
use app\modules\product\models\Coupon;
use app\modules\product\models\CouponUse;
use app\models\FeedBack;
use app\models\ProductOrder;
use app\models\ProductCart;

class CustomerController extends Controller
{
    public $layout = 'wap';

    private $configKeys = [
        'current-skin',
    ];

    public function init() {
        $this->layout = SiteHelper::getLayout();
    }

    public $scoreConfig = [
        1 => [
            'score' => 100,
            'money' => 2,
        ],
        2 => [
            'score' => 300,
            'money' => 10,
        ],
        3 => [
            'score' => 500,
            'money' => 20,
        ],
    ];

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        if (!SiteHelper::checkSecret()) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        } else {
            $id = $_COOKIE['cid'];
            $info = Customer::find()->where(['id' => $id])->asArray()->one();
            $cartid = ProductCart::find()->select('id')->scalar();
            $cartOver = ProductOrder::find()->where(['cart_id' => $cartid, 'status' => [2,3,4]])->count();
            if ($cartOver > 0) {
                $cartid = 0;
            }
            return $this->render('index', [
                'controller' => Yii::$app->controller->id,
                'info' => $info,
                'cartid' => $cartid,
            ]);
        }
    }

    public function actionInfo() {
        if (!SiteHelper::checkSecret()) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $id = $_COOKIE['cid'];

        $data = Customer::find()->where(['id' => $id])->asArray()->one();
        return $this->render('info', [
            'controller' => Yii::$app->controller->id,
            'data' => $data,
        ]);
    }

    public function actionRefund() {
        return $this->render('refund', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionScore() {
        if (!SiteHelper::checkSecret()) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $id = $_COOKIE['cid'];
        $config = $this->scoreConfig;

        $data = Customer::find()->where(['id' => $id])->asArray()->one();

        return $this->render('score', [
            'controller' => Yii::$app->controller->id,
            'config' => $config,
            'data' => $data,
        ]);
    }

    public function actionChange() {
        $config = $this->scoreConfig;
        $params = Yii::$app->request->post();
        $id = isset($params['id']) ? $params['id'] : '';

        if (empty($id)) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $data = Customer::find()->where(['id' => $_COOKIE['cid']])->asArray()->one();
        if ($data['score'] > $this->scoreConfig[$id]['score']) {
            PriceHelper::adjustScore($this->scoreConfig[$id]['score'], 'minus');
            PriceHelper::adjustWallet($data['id'], $this->scoreConfig[$id]['money'], 'plus', 'score_pay');
            echo 'ok';
        } else {
            echo '积分不够';
        }
    }

    public function actionLogin() {
        return $this->render('login', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionFeedback() {
        if (!SiteHelper::checkSecret()) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        return $this->render('feedback', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionEdit() {
        $params = Yii::$app->request->post();

        $exsit = Customer::find()->where(['phone' => $params['phone']])->count();

        if ($exsit > 0) {
            echo json_encode(['status' => 'fail', 'msg' => '此号码已存在']);
            Yii::$app->end();
        }

        $ar = Customer::findOne($_COOKIE['cid']);
        $ar->phone = $params['phone'];
        // $ar->nick  = $params['nick'];
        $ar->save();

        if ($ar->save()) {
            $secret = SiteHelper::buildSecret($params['phone']);
            echo json_encode(['status' => 'ok', 'secret' => $secret, 'cid' => $_COOKIE['cid']]);
        } else {
            echo json_encode(['status' => 'fail', 'msg' => '修改失败']);
        }
    }

    public function actionAdvice() {
        $cid = $_COOKIE['cid'];
        $params = Yii::$app->request->post();
        $advice = !empty($params['advice']) ? $params['advice'] : '';

        $ar = new FeedBack();
        $ar->customer_id = $cid;
        $ar->advice = $advice;

        if ($ar->save()) {
            echo 'ok';
        } else {
            echo '提交失败';
        }
    }

    public function actionCoupon() {
        if (!SiteHelper::checkSecret()) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $data =  $this->getCommon();
        $html = '';

        if (empty($data)) {
            $html = '很抱歉，您账户里没有可用的通用券';
        } else {
            foreach($data as $key => $value) {
                $value['start_date'] = date('Y.m.d', strtotime($value['start_date']));
                $value['end_date']   = date('Y.m.d', strtotime($value['end_date']));

                $html .= <<<EOF
                <div class="coupon_item">
        <p class="coupon_item_label">{$value['name']}</p>
        <div class="coupon_item_text">
          <p class="coupon_item_money text-danger">{$value['money']}元</p>
          <p class="coupon_item_date">{$value['start_date']}～{$value['end_date']}有效</p>
        </div>
    </div>
EOF;
            }
        }

        $jobData =  $this->getJob();
        $jobHtml = '';

        if (empty($jobData)) {
            $jobHtml = '很抱歉，您账户里没有可获取的任务券';
        } else {
            foreach($jobData as $key => $value) {
                $value['start_date'] = date('Y.m.d', strtotime($value['start_date']));
                $value['end_date']   = date('Y.m.d', strtotime($value['end_date']));

                $jobHtml .= <<<EOF
                <div class="coupon_item">
        <p class="coupon_item_label">{$value['name']}</p>
        <div class="coupon_item_text">
          <p class="coupon_item_money text-danger">{$value['money']}元</p>
          <p class="text-info" style="text-align:center;font-size:14px;">{$value['desc']}</p>
          <p class="coupon_item_date">{$value['start_date']}～{$value['end_date']}有效</p>
        </div>
    </div>
EOF;
            }
        }

        return $this->render('coupon', [
            'controller' => Yii::$app->controller->id,
            'html' => $html,
            'jobHtml' => $jobHtml,
        ]);
    }

    private function getCommon() {
        $currentDate = date('Ymd', time());
        $cid = $_COOKIE['cid'];
        $tongyong = Coupon::find()->where(['type' => 2])
            ->andWhere(['<=', 'start_date', $currentDate])
            ->andWhere(['>=', 'end_date', $currentDate])
            ->asArray()->all();

        foreach($tongyong as $key => $item) {
            $exsit = CouponUse::find()->where(['customer_id' => $cid, 'use_status' => 2, 'cid' => $item['id']])->count();
            if ($exsit > 0) {
                unset($tongyong[$key]);
            }
        }

        return $tongyong;
    }

    private function getJob() {
        $currentDate = date('Ymd', time());
        $cid = $_COOKIE['cid'];
        $job = Coupon::find()->where(['type' => 1])
            ->andWhere(['<=', 'start_date', $currentDate])
            ->andWhere(['>=', 'end_date', $currentDate])
            ->asArray()->all();

        foreach($job as $key => $item) {
            $exsit = CouponUse::find()->where(['customer_id' => $cid, 'use_status' => 2, 'cid' => $item['id']])->count();
            if ($exsit > 0) {
                unset($job[$key]);
            }
        }

        return $job;
    }
}
