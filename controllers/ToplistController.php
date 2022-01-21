<?php

namespace app\controllers;
use app\classes\CurlTop20;

class ToplistController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $exec = CurlTop20::loadPage();
        return $this->render('index', ['exec' => $exec]);
    }
    
}
