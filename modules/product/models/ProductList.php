<?php

namespace app\modules\product\models;

use yii\db\ActiveRecord;

class ProductList extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'product_list';
    }
    
    public function rules() {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
            [['price'], 'required'],
            [['unit'], 'required'],
            [['desc'], 'required'],
            [['slogan'], 'required'],
        ];
    }
}
