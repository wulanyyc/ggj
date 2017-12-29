<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\models\Customer;
use app\modules\product\models\Coupon;
use app\modules\product\models\CouponUse;
use app\models\FeedBack;
use app\models\ProductOrder;

class CustomerController extends Controller
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
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        } else {
            $phone = $_COOKIE['userphone'];
            $info = Customer::find()->where(['phone' => $phone])->asArray()->one();
            $cartid = ProductOrder::find()->select('cart_id')
                ->where(['userphone' => $phone, 'status' => 1])->scalar();
            return $this->render('index', [
                'controller' => Yii::$app->controller->id,
                'info' => $info,
                'cartid' => $cartid,
            ]);
        }
    }

    public function actionInfo() {
        return $this->render('info', [
            'controller' => Yii::$app->controller->id,
        ]);
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

    public function actionAdvice() {
        $phone = $_COOKIE['userphone'];
        $params = Yii::$app->request->post();
        $advice = !empty($params['advice']) ? $params['advice'] : '';

        $ar = new FeedBack();
        $ar->userphone = $phone;
        $ar->advice = $advice;

        if ($ar->save()) {
            echo 'ok';
        } else {
            echo '提交失败';
        }
    }

    public function actionCoupon() {
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
        $phone = $_COOKIE['userphone'];
        $tongyong = Coupon::find()->where(['type' => 2])
            ->andWhere(['<=', 'start_date', $currentDate])
            ->andWhere(['>=', 'end_date', $currentDate])
            ->asArray()->all();

        foreach($tongyong as $key => $item) {
            $exsit = CouponUse::find()->where(['userphone' => $phone, 'use_status' => 2, 'cid' => $item['id']])->count();
            if ($exsit > 0) {
                unset($tongyong[$key]);
            }
        }

        return $tongyong;
    }

    private function getJob() {
        $currentDate = date('Ymd', time());
        $phone = $_COOKIE['userphone'];
        $job = Coupon::find()->where(['type' => 1])
            ->andWhere(['<=', 'start_date', $currentDate])
            ->andWhere(['>=', 'end_date', $currentDate])
            ->asArray()->all();

        foreach($job as $key => $item) {
            $exsit = CouponUse::find()->where(['userphone' => $phone, 'use_status' => 2, 'cid' => $item['id']])->count();
            if ($exsit > 0) {
                unset($job[$key]);
            }
        }

        return $job;
    }
}
