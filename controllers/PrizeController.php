<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\PriceHelper;
use app\components\SiteHelper;
use app\components\WechatHelper;
use app\filters\WechatFilter;


class PrizeController extends Controller
{
    public $layout = 'blank';
    public $prefix = "prize_";
    public $dayLimit = 5; // 抽奖天数限制
    public $prizeLimit = 5; // 领奖期限
    public $limit = 1; // 抽奖次数限制

    public function behaviors() {
        return [
            'wechat' => [
                'class' => WechatFilter::className(),
            ]
        ];
    }
    
    public function actionIndex() {
        $params = Yii::$app->request->get();

        $sid  = isset($params['share_id']) ? $params['share_id'] : '';
        $from = isset($params['from']) ? $params['from'] : '';

        $openid = SiteHelper::getOpenid();

        if ($from == 'timeline' || $from == 'singlemessage' || $from == 'groupmessage' || !empty($openid)){
            if (empty($_COOKIE['ggjuid'])) {
                $uniq = uniqid();
                setcookie('ggjuid', $uniq, time() + 86400 * $this->dayLimit, '/');
            } else {
                $uniq = $_COOKIE['ggjuid'];
            }

            if (!empty($sid) && empty(Yii::$app->redis->get($uniq . '_from'))) {
                Yii::$app->redis->setex($uniq . '_from', 86400 * $this->dayLimit, $sid);
            }

            if (empty(Yii::$app->redis->get($uniq . '_exsit'))) {
                Yii::$app->redis->setex($uniq . '_exsit', 86400 * $this->dayLimit, $sid);
            }
            
            return $this->render('index', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        return $this->render('error', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionGetrotate() {
        $limit = $this->limit;
        $dayLimit = $this->dayLimit;
        $uniq = $_COOKIE['ggjuid'];
        // $customerId = SiteHelper::getCustomerId();

        // 判断参数
        if (empty($uniq)) {
            return $this->render('error', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        // if ($customerId == 27) {
        //     $limit = 300;
        // }

        $cntKey = $uniq . '_cnt';
        $cnt = Yii::$app->redis->get($cntKey);

        // 抽奖控制
        if ($cnt >= $limit) {
            $remainDay = $this->getRemainDay($uniq);

            $rotate = Yii::$app->redis->get($uniq);

            if ($rotate > 0) {
                $prize = PriceHelper::getPrize($rotate);
                echo json_encode([
                    'status' => 'ok', 
                    'rotate' => 0,
                    'msg' => '您本轮的抽奖次数已用完，' . $remainDay . '天后继续<br/>您的奖品：<span style="color:red">' . $prize['text'] . '</span>，是否已领取？',
                ]);
            } else {
                echo json_encode([
                    'status' => 'ok',
                    'rotate' => 0,
                    'msg'    => '您本轮的抽奖次数已用完，请' . $remainDay . '天后继续',
                ]);
            }

            Yii::$app->end();
        }

        // 判断是否已领奖
        $data = Yii::$app->redis->get($uniq . "_code");
        if (!empty($data) && $customerId != 27) {
            $info = json_decode($data, true);
            $prizeCode = $info['code'];

            $get = Yii::$app->redis->get('prize_' . $prizeCode . '_get');
            if ($get > 0) {
                // $rotate = Yii::$app->redis->get($uniq);
                $remainDay = $this->getRemainDay($uniq);

                echo json_encode([
                    'status' => 'ok',
                    'rotate' => 0,
                    'msg'    => '您' . $dayLimit . '天内已领取过奖品，请' . $remainDay . '天后继续',
                ]);

                Yii::$app->end();
            }
        }

        // 正常抽奖
        $rotate = 5 * 360 - $this->getRand() * 45;
        $prize = PriceHelper::getPrize($rotate);

        // 记录旋转值
        Yii::$app->redis->setex($uniq, 86400 * $dayLimit, $rotate);

        // 设置抽奖次数
        $cnt += 1;
        Yii::$app->redis->setex($cntKey, 86400 * $dayLimit, $cnt);

        $remain = $limit - $cnt;

        if ($remain < 0) {
            $remain = 0;
        }

        echo json_encode([
            'status' => 'ok',
            'rotate' => $rotate,
            'msg' => '恭喜您获得：<span style="color:red">' . $prize['text'] . '</span>',
            // 'msg' => '恭喜您获得：<span style="color:red">' . $prize['text'] . '</span><br/>您还剩'. $remain .'次抽奖机会',
        ]);
    }

    private function getRemainDay($uniq) {
        return PriceHelper::getPrizeRemainDay($uniq);
    }

    public function actionSuc() {
        $uniq = isset($_COOKIE['ggjuid']) ? $_COOKIE['ggjuid'] : '';

        if (empty($uniq)) {
            return $this->render('error', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $rotate = Yii::$app->redis->get($uniq);
        $prize = PriceHelper::getPrize($rotate);

        $prize['uniq'] = $uniq;

        $cntKey = $uniq . '_cnt';

        $cnt = Yii::$app->redis->get($cntKey);

        $data = Yii::$app->redis->get($uniq . "_code");

        if (empty($data)) {
            $prizeCode = SiteHelper::getRandomStr(6);

            // 存在的话，重新生成一个
            if (Yii::$app->redis->get($this->prefix . $prizeCode)) {
                $prizeCode = SiteHelper::getRandomStr(6);
            }

            Yii::$app->redis->setex($this->prefix . $prizeCode, 86400 * $this->prizeLimit, json_encode($prize));

            $qrData = WechatHelper::getTempqrcode($prizeCode);

            $ticket = isset($qrData['ticket']) ? $qrData['ticket'] : '';
            if (empty($ticket)) {
                return $this->render('error', [
                    'controller' => Yii::$app->controller->id,
                ]);
            }

            Yii::$app->redis->setex($uniq . "_code", 86400 * $this->dayLimit, json_encode(['ticket' => $ticket, 'code' => $prizeCode]));
        } else {
            $data = json_decode($data, true);
            $ticket = $data['ticket'];
            $prizeCode = $data['code'];

            Yii::$app->redis->setex($this->prefix . $prizeCode, 86400 * $this->prizeLimit, json_encode($prize));
        }

        $remainDay = $this->getRemainDay($uniq);

        return $this->render('suc', [
            'controller' => Yii::$app->controller->id,
            'ticket' => urlencode($ticket),
            'text' => $prize['text'],
            'code' => $prizeCode,
            'day'  => $remainDay,
            'prizeLimit' => $this->prizeLimit,
        ]);
    }

    public function actionFail() {
        return $this->render('fail', [
            'controller' => Yii::$app->controller->id,
            'day' => $this->dayLimit,
        ]);
    }

    private function getRand() {
        $rand = rand(1, 100);

        // 5元优惠券
        if ($rand >= 1 && $rand < 20) {
            return 0;
        }

        // 8元优惠券
        if ($rand >= 20 && $rand < 25) {
            return 1;
        }

        // 10元优惠券
        if ($rand >= 25 && $rand < 30) {
            return 2;
        }

        // 3元优惠券
        if ($rand >= 30 && $rand < 50) {
            return 3;
        }

        // 20元优惠券
        if ($rand >= 50 && $rand < 51) {
            return 4;
        }

        // 6元优惠券
        if ($rand >= 51 && $rand < 75) {
            return 5;
        }

        // 2元优惠券
        if ($rand >= 75 && $rand < 98) {
            return 6;
        }

        // 12元优惠券
        if ($rand >= 98 && $rand <= 100) {
            return 7;
        }
    }
}
