<?php


namespace frontend\modules\academy\components;
use yii\web\Controller;

abstract class BaseAcademyController extends Controller
{

    public function beforeAction($action)
    {
        $this->layout = '@frontend/modules/academy/views/layouts/main.php';
        return parent::beforeAction($action);
    }

}