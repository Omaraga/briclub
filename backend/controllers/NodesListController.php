<?php


namespace backend\controllers;


use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class NodesListController extends Controller
{
    public function actionIndex(){
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => \common\models\TokenNodes::find(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
}