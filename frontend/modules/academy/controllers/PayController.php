<?php

namespace frontend\modules\academy\controllers;
use frontend\modules\academy\components\BaseAcademyController;
use Yii;
class PayController extends BaseAcademyController
{

    /**
     * @return string|yii\web\Response
     */
    public function actionIndex(){
        if(Yii::$app->user->isGuest){
            return $this->redirect('site/login');
        }
        $user = Yii::$app->user->identity;

        return $this->render('index',[
            'user' => $user,
        ]);
    }

    /**
     * @return false|string
     */
    public function actionAgreeContract(){
        if(Yii::$app->user->isGuest){
            return json_encode(['status'=>'error', 'error'=>'Ошибка в запросе']);
        }
        $user = Yii::$app->user->identity;
        if (Yii::$app->request->isPost){
            $isAgree = intval(Yii::$app->request->post('isAgree'));
            if ($isAgree == 1){
                $user->is_agree_contract = 1;
                $user->save();
                return json_encode(['status'=>'success']);
            }
        }
        return json_encode(['status'=>'error', 'error'=>'Ошибка в запросе']);

    }
	public function actionInfo(){
        return $this->render('info',[]);
    }

}