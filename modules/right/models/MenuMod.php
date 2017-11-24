<?php

namespace app\modules\right\models;

use yii\db\ActiveRecord;

class MenuMod extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    /**
     * 表名
     * @return string
     */
    public static function tableName(){
        return 'right_menu_module';
    }
    
    /**
     * 入库规则
     * @return array
     */
    public function rules() {
        return [
            [['text'], 'required'],
            [['text'], 'unique'],
        ];
    }
}
