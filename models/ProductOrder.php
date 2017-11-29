<?php

namespace app\models;

use yii\db\ActiveRecord;

class ProductOrder extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'product_order';
    }
    
    public function rules() {
        return [
            [['username'], 'required'],
            [['cellphone'], 'required'],
            [['address'], 'required'],
            [['money'], 'required'],
        ];
    }
}
