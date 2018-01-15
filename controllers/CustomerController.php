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
use app\filters\CustomerFilter;

class CustomerController extends Controller
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
                    'refund',
                    'login',
                ]
            ]
        ];
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
        $id   = SiteHelper::getCustomerId();
        $info = Customer::find()->where(['id' => $id])->asArray()->one();
        $cartid = ProductCart::find()->select('id')->where(['customer_id' => $id])
            ->orderBy('id desc')->limit(1)->scalar();

        $cartOver = ProductOrder::find()->where(['cart_id' => $cartid, 'status' => [2,3,4]])->count();
        if ($cartOver > 0) {
            $cartid = 0;
        }

        $isWechat = false;
        if (!empty($_COOKIE['openid'])) {
            $isWechat = true;
        }

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'info' => $info,
            'cartid' => $cartid,
            'isWechat' => $isWechat,
        ]);
    }

    public function actionInfo() {
        $id = SiteHelper::getCustomerId();

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
        $id = SiteHelper::getCustomerId();
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
        $id     = isset($params['id']) ? $params['id'] : '';

        if (empty($id)) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $cid  = SiteHelper::getCustomerId();
        $data = Customer::find()->where(['id' => $cid])->asArray()->one();

        if ($data['score'] > $this->scoreConfig[$id]['score']) {
            PriceHelper::adjustScore($this->scoreConfig[$id]['score'], 'minus');
            PriceHelper::adjustWallet($data['id'], $this->scoreConfig[$id]['money'], 'plus', 'score_pay');
            SiteHelper::render('ok');
        } else {
            SiteHelper::render('fail', '积分不够');
        }
    }

    public function actionLogin() {
        return $this->render('login', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionFeedback() {
        return $this->render('feedback', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionEdit() {
        $params = Yii::$app->request->post();

        $exsit = Customer::find()->where(['phone' => $params['phone']])->count();

        if ($exsit > 0) {
            SiteHelper::render('fail', '此号码已存在');
        }

        $cid = SiteHelper::getCustomerId();
        $ar  = Customer::findOne($cid);
        $ar->phone = $params['phone'];
        $ar->save();

        if ($ar->save()) {
            $secret = SiteHelper::buildSecret($params['phone']);

            SiteHelper::render('ok', ['secret' => $secret, 'cid' => SiteHelper::getCustomerId()]);
        } else {
            SiteHelper::render('fail', '修改失败');
        }
    }

    public function actionAdvice() {
        $cid = SiteHelper::getCustomerId();
        $params = Yii::$app->request->post();
        $advice = !empty($params['advice']) ? $params['advice'] : '';

        $ar = new FeedBack();
        $ar->customer_id = $cid;
        $ar->advice = $advice;

        if ($ar->save()) {
            SiteHelper::render('ok');
        } else {
            SiteHelper::render('fail', '提交失败');
        }
    }

    public function actionCoupon() {
        $data = PriceHelper::getValidCoupon();
        $html = '';

        if (empty($data)) {
            $html = '很抱歉，您账户里没有可用的通用券';
        } else {
            foreach($data as $key => $value) {
                $info = Coupon::find()->where(['id' => $value['cid']])->asArray()->one();
                $info['start_date'] = date('Y.m.d', strtotime($info['start_date']));
                $info['end_date']   = date('Y.m.d', strtotime($info['end_date']));

                $html .= <<<EOF
                <div class="coupon_item">
        <p class="coupon_item_label">{$info['name']}</p>
        <div class="coupon_item_text">
          <p class="coupon_item_money text-danger">{$info['money']}元</p>
          <p class="coupon_item_date">{$info['start_date']}～{$info['end_date']}有效</p>
        </div>
    </div>
EOF;
            }
        }

        $jobData = $this->getJob();
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
        $cid = SiteHelper::getCustomerId();

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
        $cid = SiteHelper::getCustomerId();

        $job = Coupon::find()->where(['type' => 1])
            ->andWhere(['<=', 'start_date', $currentDate])
            ->andWhere(['>=', 'end_date', $currentDate])
            ->asArray()->all();

        foreach($job as $key => $item) {
            $exsit = CouponUse::find()->where(['customer_id' => $cid, 'cid' => $item['id']])->count();
            if ($exsit > 0) {
                unset($job[$key]);
            }
        }

        return $job;
    }
}
