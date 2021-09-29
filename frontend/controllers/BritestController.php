<?php

namespace frontend\controllers;

use frontend\models\forms\BriTestForm;
use Yii;
class BritestController extends \yii\web\Controller
{
    public function actionIndex()
    {

        $model = new BriTestForm();

        if ($model->load(Yii::$app->request->post())){

        }


        return $this->render('index',[
            'model' => $model
        ]);
    }

}
