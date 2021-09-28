<?php


namespace backend\controllers;


use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class TokensListController extends Controller
{
    public function actionIndex(){
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
		
		$sum = \common\models\Tokens::find()->where(['not in','user_id',1])->sum('balans');

		
        $dataProvider = new ActiveDataProvider([
            'query' => \common\models\Tokens::find()->where(['not in','user_id',1]),
            'sort' => [
                'defaultOrder' => [
                    'balans' => SORT_DESC
                ]
            ]
        ]);
        return $this->render('index', [
           'dataProvider' => $dataProvider,
			'sum'=>$sum
        ]);
    }
}