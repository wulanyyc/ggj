<?php

namespace app\models;

use yii\db\ActiveRecord;

class ProductPackage extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'product_package';
    }
    
    public function rules() {
        return [
            [['product_id'], 'required'],
            [['product_package_id'], 'required'],
            [['num'], 'required'],
        ];
    }
}
