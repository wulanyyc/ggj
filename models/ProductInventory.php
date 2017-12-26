<?php

namespace app\models;

use yii\db\ActiveRecord;

class ProductInventory extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'product_inventory';
    }
    
    public function rules() {
        return [
            [['pid'], 'required'],
            [['operator_func'], 'required'],
            [['num'], 'required'],
            [['operator'], 'required'],
            [['price'], 'required'],
        ];
    }
}
