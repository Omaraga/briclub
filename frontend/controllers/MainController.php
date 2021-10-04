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
        if ($user->activ != 1){
            return $this->redirect('/main/active');
        }
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

    public function actionActive(){
        $user = Yii::$app->user->identity;
        $events = Events::find()->all();
        return $this->render('active', [
            'user' => $user,
            'events' => $events
        ]);

    }

    public function actionEvents($id = null){

    }

    /**
     * @return string
     */
    public function actionDocs(){
        $docs = \common\models\Documents::find()->where(['type'=>1,'status'=>1])->orderBy('order asc')->all();
        return $this->render('docs', [
            'docs'=>$docs,
        ]);
    }


}
