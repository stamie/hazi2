<?php

namespace app\controllers;
use app\classes\CurlTop20;
use app\classes\XmlUse;

class TestsController extends \yii\web\Controller
{
    public function actionCurltest()
    {
        $exec = CurlTop20::loadTop250page();
        return $this->render('index', ['exec' => $exec]);
    }
    
    
}
