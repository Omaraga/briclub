<?php


namespace backend\controllers;


use common\models\Actions;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class TokenGetsController extends Controller
{
    public function actionIndex($username=null)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $error = null;
        $dataProvider = new ActiveDataProvider([
            'query' => Actions::find()->where(['type'=>55,'system'=>11]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        $dataProvider2 = new ActiveDataProvider([
            'query' => Actions::find()->where(['type'=>55,'system'=>1]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider3 = new ActiveDataProvider([
            'query' => Actions::find()->where(['type'=>55,'system'=>2]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider4 = new ActiveDataProvider([
            'query' => Actions::find()->where(['type'=>55,'system'=>3]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3,
            'dataProvider4' => $dataProvider4,
            'error' => $error,
        ]);
    }
}