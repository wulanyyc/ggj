<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\modules\right\models\User;
use app\modules\right\models\Mod;
use app\modules\right\models\RoleMod;
use app\modules\right\models\UserRole;

/**
 * 返回 数据 类
 * @author yangyuncai
 *
 */
class ResponseHelper extends Component{

    public static function jsonResponse($status, $data, $msg = '')
    {
        $res = [];
        $res['status']  = $status;
        $res['data']    = $data;
        $res['msg']     = $msg;
        echo json_encode($res);

        Yii::$app->end();
    }
}
