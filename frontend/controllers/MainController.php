<?php

namespace frontend\controllers;
use common\models\BriTokens;
use common\models\Events;
use common\models\Tokens;
use common\models\User;
use common\models\UserRank;
use Yii;
class MainController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest){
            $this->redirect('/site/login');
            return false;
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
        $events = Events::find()->all();
        $userEvents = Events::getUserEvents($user);
        return $this->render('index', [
            'user' => $user,
            'selRank' => $selRank,
            'rankList' => $rankList,
            'events' => $events,
            'userEvents' => $userEvents,
        ]);
    }

    public function actionEvents($id = null){

    }

    /**
     * @return string
     */
    public function actionDocs(){
        return $this->render('docs', []);
    }

}
