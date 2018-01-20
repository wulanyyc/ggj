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

        // $exsit = Customer::find()->where(['phone' => $params['phone']])->count();

        // if ($exsit > 0) {
        //     SiteHelper::render('fail', '此号码已存在');
        // }
        
        if (!SiteHelper::checkPhone($params['phone'])) {
            SiteHelper::render('fail', '此号码格式有问题');
        }

        $cid = SiteHelper::getCustomerId();
        $ar  = Customer::findOne($cid);
        $ar->phone = $params['phone'];

        if ($ar->save()) {
            $secret = SiteHelper::buildSecret($params['phone']);

            SiteHelper::render('ok', ['secret' => $secret, 'cid' => $cid]);
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
            $html = '没有可用的券';
        } else {
            foreach($data as $key => $value) {
                $info = Coupon::find()->where(['id' => $value['cid']])->asArray()->one();
                // $info['start_date'] = date('Y.m.d', strtotime($info['start_date']));
                // $info['end_date']   = date('Y.m.d', strtotime($info['end_date']));

                $dayDiff = ceil((strtotime($info['end_date']) - time()) / 86400);

                $html .= <<<EOF
                <a class="coupon_item" href="/">
                    <div class="coupon_item_content">
                        <img src="http://img.guoguojia.vip/img/icon/coupon_use.jpeg" class="coupon_item_content_img" />
                        <div class="coupon_item_label">{$info['name']}</div>
                        <div class="coupon_item_text">
                          <div class="coupon_item_money">{$info['money']}元</div>
                          <div class="coupon_item_date">剩{$dayDiff}天到期</div>
                        </div>
                    </div>
                </a>
EOF;
            }
        }

        $jobData = $this->getJob();
        $jobHtml = '';

        if (empty($jobData)) {
            $jobHtml = '没有可领取的券';
        } else {
            foreach($jobData as $key => $value) {
                $dayDiff = ceil((strtotime($value['end_date']) - time()) / 86400);
                if ($value['type'] == 1) {
                    $img = "http://img.guoguojia.vip/img/icon/coupon_sys.jpeg";
                } else {
                    $img = "http://img.guoguojia.vip/img/icon/coupon_get.jpeg";
                }

                $jobHtml .= <<<EOF
                <div class="coupon_item" data-type={$value['type']} data-id={$value['id']}>
                    <div class="coupon_item_content">
                        <img src="{$img}" class="coupon_item_content_img" />
                        <div class="coupon_item_label">{$value['name']}</div>
                        <div class="coupon_item_text">
                          <div class="coupon_item_money">{$value['money']}元</div>
                          <div class="coupon_item_date">剩{$dayDiff}天到期</div>
                        </div>
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

    public function actionGetcoupon() {
        $params = Yii::$app->request->post();
        $couponId = !empty($params['cid']) ? $params['cid'] : 0;

        $customerId = SiteHelper::getCustomerId();

        $exsit = CouponUse::find()->where(['customer_id' => $customerId, 'cid' => $couponId])->count();

        if ($exsit > 0) {
            SiteHelper::render('fail', '已领过');
        } else {
            $id = PriceHelper::createCouponById($couponId, $customerId);

            if ($id > 0) {
                SiteHelper::render('ok');
            } else {
                SiteHelper::render('fail', '领取失败');
            }
        }
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

        $job = Coupon::find()
            ->andWhere(['!=', 'type', 3])
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
