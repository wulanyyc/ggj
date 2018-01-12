<?php

namespace app\controllers;

use yii\web\Controller;

class ThirdController extends Controller
{
    public $enableCsrfValidation = false;

    public function kdn() {
        echo 'success';
    }
}
