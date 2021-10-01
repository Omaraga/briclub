<?php

namespace frontend\controllers;
use common\models\BriTokens;
use common\models\Tokens;
use common\models\User;
use common\models\UserRank;
use Yii;
class MainController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect('site/login');
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionIndex($selRankId = null)
    {
        /* @var $user User*/
        $user = Yii::$app->user->identity;
        if (!$selRankId){
            UserRank::setRank($user);
            $selRankId = $user->rank_id;
        }
        $selRank = UserRank::findOne($selRankId);

        $rankList = UserRank::find()->all();
        return $this->render('index', [
            'user' => $user,
            'selRank' => $selRank,
            'rankList' => $rankList,
        ]);
    }


}
