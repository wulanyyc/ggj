<?php

namespace app\models;

use yii\db\ActiveRecord;

class GiftUse extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'gift_use';
    }
    
    public function rules() {
        return [
            [['customer_id'], 'required'],
            [['gid'], 'required'],
        ];
    }
}
