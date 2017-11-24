<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\DataLocationTree;
use app\models\LocationTree;
use app\modules\right\models\User;
use app\modules\right\models\Mod;
use app\modules\right\models\RoleMod;
use app\modules\right\models\UserRole;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class CommonHelper extends Component{
    public static function makePassword($username, $password) {
        return md5(substr(md5($username . '_' . $password), 0, 6) . Yii::$app->params['salt']);
    }

    public static function checkPassword($username, $password) {
        $data = User::find()->where(['username' => $username])->asArray()->one();
        if (empty($data)) {
            return false;
        }

        $dbPassword = $data['password'];
        // $mkPassword = md5($password . Yii::$app->params['salt']);
        $mkPassword = md5(substr(md5($username . '_' . $password), 0, 6) . Yii::$app->params['salt']);

        if ($dbPassword != $mkPassword) {
            return false;
        }

        return true;
    }

    /**
     * 检查模块权限
     * @param number $modId 模块id, 当模块id为空时，检查当前访问controller
     * @param boolean $errorReportFlag 是否直接报错
     * @throws \yii\base\UserException
     * @return boolean
     */
    public static function checkRights($modId = 0){
        // root拥有所有权限
        if(Yii::$app->session['rootflag'] == 1){
            return true;
        }

        if($modId == 0){
            $module = Yii::$app->controller->module->id;
            $controller = Yii::$app->controller->id;

            $modData = Mod::find()->where(['module' => $module, 'controller' => $controller])->asArray()->one();
            if(empty($modData)){
                return false;
            }

            $modId = $modData['id'];
        }

        // 获取用户角色
        $uid = Yii::$app->session['uid'];
        $userRoleData = UserRole::find()->where(['user_id' => $uid])->asArray()->all();
        if(empty($userRoleData)){
            return false;
        }

        $userRoleList = [];
        foreach($userRoleData as $key => $value){
            $userRoleList[] = $value['role_id'];
        }

        // 获取模块角色
        $roleModData = RoleMod::find()->where(['module_id' => $modId])->asArray()->all();
        if(empty($roleModData)){
            return false;
        }

        $modRoleList = [];
        foreach($roleModData as $key => $value){
            $modRoleList[] = $value['role_id'];
        }

        // 用户角色和模块角色取交集
        $result = array_intersect($userRoleList, $modRoleList);

        if(empty($result)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 根据菜单id获取有权限的页面
     * @param int $menuID
     * @return boolean
     */
    public static function getRightLinkByMenuId($menuID) {
        // root拥有所有权限
        // $menuID = '4';

        // 获取用户角色
        $uid      = Yii::$app->session['uid'];
        $rootflag = Yii::$app->session['rootflag'];

        if ($rootflag == '0') {
            $userRoleData = UserRole::find()->where(['user_id' => $uid])->asArray()->all();
            if(empty($userRoleData)){
                return false;
            }

            $userRoleList = [];
            foreach($userRoleData as $key => $value){
                $userRoleList[] = $value['role_id'];
            }

            // 获取模块角色
            $roleModData = RoleMod::find()->where(['role_id' => $userRoleList])->asArray()->all();
            if(empty($roleModData)){
                return false;
            }

            $modList = [];
            foreach($roleModData as $key => $value){
                $modList[] = $value['module_id'];
            }

            $modData = Mod::find()->where(['id' => $modList, 'menu_id' => $menuID])
                    ->orderBy('menu_order asc')->asArray()->all();
        } else {
            $modData = Mod::find()->where(['menu_id' => $menuID])
                    ->orderBy('menu_order asc')->asArray()->all();
        }

        if (empty($modData)) {
            return false;
        }

        $modGroupParentOrNodeData = array();
        $modValidData = array();
        foreach($modData as $key => $value) {
            if ($value['type'] == 'group_node') {
                $modGroupParentOrNodeData[] = $value['group_parent_id'];
                $modValidData[] = $value['id'];
            }

            if ($value['type'] == 'node') {
                $modGroupParentOrNodeData[] = $value['id'];
                $modValidData[] = $value['id'];
            }
        }
        $modGroupParentOrNodeData = array_unique($modGroupParentOrNodeData);

        if (!empty($modGroupParentOrNodeData)) {
            $modOrderData = Mod::find()->where(['id' => $modGroupParentOrNodeData])
                    ->orderBy('menu_order asc')->asArray()->all();
            // 第一个为含有了组的菜单
            if ($modOrderData[0]['type'] == 'group_parent') {
                $modChildData = Mod::find()->where(['id' => $modValidData, 'group_parent_id' => $modOrderData[0]['id']])->orderBy('id asc')->asArray()->all();
                return $modChildData[0]['link'];
            } else {
                return $modOrderData[0]['link'];
            }
        } else {
            return false;
        }
    }

    /**
     * 根据用户id获取有权限的页面
     * @param int $menuID
     * @return boolean
     */
    public static function getRightLinkByUid() {
        $rootflag = Yii::$app->session['rootflag'];

        if ($rootflag == 0) {
            //获取用户角色
            $uid = Yii::$app->session['uid'];
            $userRoleData = UserRole::find()->where(['user_id' => $uid])->asArray()->all();
            if(empty($userRoleData)){
                return false;
            }

            $userRoleList = [];
            foreach($userRoleData as $key => $value){
                $userRoleList[] = $value['role_id'];
            }

            //获取模块角色
            $roleModData = RoleMod::find()->where(['role_id' => $userRoleList])->asArray()->all();
            if(empty($roleModData)){
                return false;
            }

            $modList = [];
            foreach($roleModData as $key => $value){
                $modList[] = $value['module_id'];
            }

            $links = Mod::find()->where(['id' => $modList])->asArray()->all();

            return $links[0]['link'];
        } else {
            return '/';
        }

        
    }

    /**
     * 调整一维数据为多维
     * 例：   $arr = ['a' => '123', 'b' => '456']; $keys = ['a', 'b'];
     *        $return = [
     *          '123' => [
     *              '456' => [
     *                  ['a' => '123', 'b' => '456']
     *              ]
     *          ]
     *      ];
     * @param $arr
     * @param $keys
     * @return
     */
    public static function arrayDivDimension(array $arr, array $keys) {
        if (empty($arr) || empty($keys)) {
            return [];
        }
        $res = [];
        //从最低维开始
        $keys = array_reverse($keys);
        $keyLength = count($keys);
        //keys为单个时，不需要中间变量
        if ($keyLength === 1) {
            $key = $keys[0];
            foreach ($arr as $v) {
                $res[$v[$key]] = $v;
            }
        } else {
            foreach ($arr as $v) {
                $tmp = [];
                for ($i = 0; $i < $keyLength; $i++) {
                    if (!isset($v[$keys[$i]])) {
                        break;
                    }
                    //递归中间变量
                    $key = $v[$keys[$i]];
                    if ($i === $keyLength - 1) {
                        if (isset($res[$key])) {
                            $res[$key] = array_merge_recursive($res[$key], $tmp);
                        } else {
                            $res[$key] = $tmp;
                        }
                    } else if ($i === 0) {
                        $tmp[$key] = $v;
                    } else {
                        $t = $tmp;
                        $tmp = [];
                        $tmp[$key] = $t;
                    }
                }
            }
        }
        return $res;
    }

    /**
     * 菜单分级数据格式化
     * @param array $menus
     * @return array
     */
    public static function getMenuLevelFormatData($menus){
        $nodes = [];
        $groups = [];
        $orderMenus = [];
        $orderFormatMenus = [];

        //菜单项分类
        foreach($menus as $key => $value){
            if($value['type'] == 'node' || $value['type'] == 'other'){
                $nodes[] = $value;
                unset($menus[$key]);
            }

            if($value['type'] == 'group_parent'){
                $groups[$value['id']] = $value;
                $groups[$value['id']]['childs'] = [];
                unset($menus[$key]);
            }

            if($value['type'] == 'group_node'){
                if(isset($groups[$value['group_parent_id']])){
                    array_push($groups[$value['group_parent_id']]['childs'] , $value);
                    unset($menus[$key]);
                }
            }
        }

        if(!empty($menus)){
            foreach($menus as $key => $value){
                if($value['type'] == 'group_node'){
                    if(isset($groups[$value['group_parent_id']])){
                        array_push($groups[$value['group_parent_id']]['childs'] , $value);
                        unset($menus[$key]);
                    }
                }
            }
        }

        //菜单项排序
        if(!empty($nodes)){
            foreach($nodes as $key => $value){
                if($value['menu_order'] > 0){
                    $orderMenus[$value['menu_order']][] = $value;
                    unset($nodes[$key]);
                }
            }
        }

        if(!empty($groups)){
            foreach($groups as $key => $value){
                if($value['menu_order'] > 0){
                    $orderMenus[$value['menu_order']][] = $value;
                    unset($groups[$key]);
                }
            }
        }

        if(!empty($orderMenus)){
            ksort($orderMenus, SORT_NUMERIC);
            foreach($orderMenus as $key => $value){
                foreach($value as $k => $v){
                    $orderFormatMenus[] = $v;
                }
            }
        }

        if(!empty($nodes)){
            foreach($nodes as $key => $value){
                array_push($orderFormatMenus, $value);
                unset($nodes[$key]);
            }
        }

        if(!empty($groups)){
            foreach($groups as $key => $value){
                array_push($orderFormatMenus, $value);
                unset($groups[$key]);
            }
        }

        if(!empty($menus)){
            foreach($menus as $key => $value){
                array_unshift($orderFormatMenus, $value);
            }
        }

        return $orderFormatMenus;
    }

    public static function formatClassName($class){
        return str_replace('\\', '_', $class);
    }

    /**
     * 获取日期的前后几天日期
     * @param string $date 日期
     * @param string $day 获取日期的前后几天，
     *                    负数代表前几天，正数代表后几天，
     *                    默认-1，代表前一天
     * @return string 2013-02-01
     */
    public static function preDate($date, $day='-1 day') {
        return date('Ymd', strtotime($day, strtotime($date)));
    }


    /**
     * 根据经纬度获取geohash
     * @param  [type] $lat     [description]    纬度
     * @param  [type] $lng     [description]    经度   
     * @param  [type] $presion [description]    精确位数
     * @return [type]          [description]    geohash值
     */
    public static function getGeohash($lat, $lng, $presion)
    {
        $geotools = new \League\Geotools\Geotools();
        $coordToGeohash = new \League\Geotools\Coordinate\Coordinate($lat . ',' . $lng);
        $encoded = $geotools->geohash()->encode($coordToGeohash, $presion); 
        $geohash = $encoded->getGeohash();

        return $geohash;
    }


    /**
     * 根据经纬度获取geohash
     * @param  [type] $lat     [description]    纬度
     * @param  [type] $lng     [description]    经度   
     * @param  [type] $presion [description]    精确位数
     * @return [type]          [description]    geohash值
     */
    public static function encodeGeohash($lat, $lng, $presion = 7)
    {
        $geotools = new \League\Geotools\Geotools();
        $coordToGeohash = new \League\Geotools\Coordinate\Coordinate($lat . ',' . $lng);
        $encoded = $geotools->geohash()->encode($coordToGeohash, $presion); 
        $geohash = $encoded->getGeohash();

        return $geohash;
    }


    public static function decodeGeohash($geohash, $float = 0)
    {
        $geotools = new \League\Geotools\Geotools();
        $decoded = $geotools->geohash()->decode($geohash);
        $lat = $decoded->getCoordinate()->getLatitude();
        $lng = $decoded->getCoordinate()->getLongitude();

        if ($float) {
            $lat = round($lat, $float);
            $lng = round($lng, $float);
        }

        return ['lat' => $lat, 'lng' => $lng];
    }


    public function formatProvince($province)
    {
        if (empty($province)) {
            return '';
        }

        $pattern = '/省/';
        $existTitle = preg_match($pattern, $province);

        $existDb = LocationTree::find()
            ->select('title')
            ->where("title like '" . $province . "%'")
            ->andWhere(['type' => 'province'])
            ->scalar();
        
        if (!$existTitle && $existDb) {
            return $existDb;
        }
        return $province;
    }


    public function formatCity($city)
    {
        if (empty($city)) {
            return '';
        }

        $pattern = '/市/';
        $existTitle = preg_match($pattern, $city);

        $existDb = LocationTree::find()
            ->select('title')
            ->where("title like '" . $city . "%'")
            ->andWhere(['type' => 'city'])
            ->scalar();
        
        if (!$existTitle && $existDb) {
            return $existDb;
        }

        $length = mb_strlen($city);
        if ($length <= 3 && !$existTitle) {
            return $city . '市';
        }

        return $city;
    }


    /**
     * [传入日期 ， 计算月数]
     * @param  [int] $startDate     [开始月份]
     * @param  [int] $endDate       [结束月份，默认当前月份]
     * @return [int]                [月数]
     */
    public static function caculateMonths ($startDate, $endDate = 'now') 
    {
        if (empty($startDate)) {
            $startDate = '201509';
        }

        $submitTime = strtotime($startDate);
        $nowTime = strtotime($endDate);
        $months = ceil(($nowTime - $submitTime) / (86400 * 30));
        return $months;
    }


    public static function dealArr2SqlStr ($arr)
    {
        $str = "('";
        $str .= implode("','",$arr);
        $str .= "')";
        return $str;
    }


    public static function DownloadXls ($data)
    {
        header("Content-type:application/vnd.ms-excel");  //设置内容类型
        header("Content-Disposition:attachment;filename=data.xls");
         
        foreach ($data as $key => $val) {
            foreach ($val as $ckey => $cval) {
                echo $cval . "\t";
            }
            echo "\n";
        }
        Yii::$app->end();
    }

    public static function formatPrice ($price) {
        return '¥ ' . $price;
    }

    public static function checkPhone($phone) {
        $reg ='/^(1(([34578][0-9])|(47)|[8][0126789]))\d{8}$/';
        if(preg_match($reg, $phone)) {
            return true;
        }

        return false;
    }

    public static function checkYdPhone($phone) {
        $reg ='/^(134|135|136|137|138|139|147|150|151|152|157|158|159|178|182|183|184|187|188)/';
        if(preg_match($reg, $phone)) {
            return true;
        }

        return false;
    }

    /**
     * 英文数字格式显示
     * @param number $value
     * @param int $decimals
     * @return number
     */
    public static function formatNumber($value, $decimals = 0){
        if ($decimals === false && is_numeric($value)) {
            $sepDec    = explode('.', $value);
            $decLength = count($sepDec) > 1 ? strlen(end($sepDec)) : 0;
            return number_format($value, $decLength);
        }
        return is_numeric($value) ? number_format($value, $decimals) : $value;
    }

}
