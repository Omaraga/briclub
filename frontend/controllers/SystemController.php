<?php

namespace frontend\controllers;

class SystemController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionStatistic(){
        return $this->render('statistic');
    }

}
