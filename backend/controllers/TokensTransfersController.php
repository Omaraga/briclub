<?php


namespace backend\controllers;


use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class TokensTransfersController extends Controller
{
    public function actionIndex(){
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => \common\models\Actions::find()->where(['type' => 56]),
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