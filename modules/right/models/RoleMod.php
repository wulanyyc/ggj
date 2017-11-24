<?php

namespace app\modules\right\models;

use yii\db\ActiveRecord;

class RoleMod extends ActiveRecord
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
        return 'right_role_module';
    }
    
    /**
     * 入库规则
     * @return array
     */
    public function rules() {
        return [
            [['role_id', 'module_id'], 'required'],
            [['role_id', 'module_id'], 'integer'],
        ];
    }
}
