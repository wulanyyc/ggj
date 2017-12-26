<?php

namespace app\modules\product\models;

use yii\db\ActiveRecord;

class CouponUse extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'coupon_use';
    }
    
    public function rules() {
        return [
            [['required'], 'required'],
            [['cid'], 'required'],
        ];
    }
}
