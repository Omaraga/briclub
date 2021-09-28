<?php

namespace frontend\components;

use Yii;
use yii\base\BootstrapInterface;
use common\models\Courses;  // assuming Cms is the Model class for table containing aliases
class DynaRoute implements BootstrapInterface
{
    public function bootstrap($app)
    {

        $cmsModel = Courses::find()
            ->all(); // customize the query according to your need
        $routeArray = [];
        foreach($cmsModel as $row) { // looping through each cms table row
            $routeArray[] = ['class' => 'yii\web\UrlRule', 'pattern' => $row->alias, 'route' => '/courses', 'defaults' => ['id' => $row->id]];
    }
        $mainid = Courses::find()->where(['alias'=>'main'])->one();
        if(!empty($mainid)){
            $routeArray[] = ['class' => 'yii\web\UrlRule', 'pattern' => '', 'route' => '/courses', 'defaults' => ['id' => $mainid['id']]];
        }

        $app->urlManager->addRules($routeArray);// Append new rules to original rules
}
}