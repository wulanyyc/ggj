<?php

namespace app\models;

use yii\db\ActiveRecord;

class Pay extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'pay';
    }
    
    public function rules() {
        return [
            [['order_id'], 'required'],
            [['customer_id'], 'required'],
            [['online_money'], 'required'],
            [['wallet_money'], 'required'],
        ];
    }
}
