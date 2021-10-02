<?php

namespace frontend\controllers;

use common\models\User;
use common\models\UserRank;
use Yii;
class SystemController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionStatistic(){
        $user = Yii::$app->user->identity;
        $rank = UserRank::find()->where(['id'=>$user['rank_id']])->one();
//        $children = User::find()->where(['parent_id'=>])
        return $this->render('statistic', ['user'=>$user, 'rank'=>$rank]);
    }

}
