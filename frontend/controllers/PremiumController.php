<?php


namespace frontend\controllers;


use common\models\PremiumTariffs;
use common\models\User;
use common\models\logic\PremiumsManager;
use yii\web\Controller;
use common\models\Premiums;
use common\models\Actions;
use Yii;

class PremiumController extends Controller
{
    public function actionIndex(){

        if (Yii::$app->user->isGuest){
            return $this->redirect('/');
        }
        $premium = Premiums::findOne(['user_id' => Yii::$app->user->id]);

        if($premium != null && $premium->tariff_id == 6){
            return $this->redirect('/profile');
        }
        $tariffs = \common\models\PremiumTariffs::find()->all();
        $user = Yii::$app->user->identity;
        $premiumEvent = \common\models\PremiumEvent::find()->where(['user_id' => $user->id])->one();
        if ($premiumEvent && $premiumEvent->end_time > time()){
            $tariffs[5]->price = intval($premiumEvent->price);
        }

        return $this->render('index', [
            'tariffs' => $tariffs
        ]);
    }

    public function actionBuy($id){
        if (Yii::$app->user->isGuest){
            return $this->redirect('/');
        }
        $premium = Premiums::findOne(['user_id' => Yii::$app->user->id]);
        $user = Yii::$app->user->identity;
        if($premium != null && $premium->tariff_id == 6){
            return $this->redirect('/profile');
        }

        $tariffs = PremiumTariffs::findOne($id);
        $price = $tariffs->price;
        if ($id == 6){
            $premiumEvent = \common\models\PremiumEvent::find()->where(['user_id' => $user->id])->one();
            if ($premiumEvent && $premiumEvent->end_time > time()){
                $price = intval($premiumEvent->price);
            }
        }
        $user = User::findOne([Yii::$app->user->id]);
        $program = 14;

        return $this->render('buy', [
            'price' => $price,
            'user' => $user,
            'program' => $program,
            'premium_id' => $id,
            'tokens' => $price / 10
        ]);
    }

    public function actionPay($id){

        $premium = Premiums::findOne(['user_id' => Yii::$app->user->id]);

        if($premium != null && $premium->tariff_id == 6){
            return $this->redirect('/profile');
        }

        $user = User::findOne(Yii::$app->user->id);

        $tariffs = PremiumTariffs::findOne($id);
        $price = $tariffs->price;
        if ($id == 6){
            $premiumEvent = \common\models\PremiumEvent::find()->where(['user_id' => $user->id])->one();
            if ($premiumEvent && $premiumEvent->end_time > time()){
                $price = intval($premiumEvent->price);
            }
        }
        if($price <= $user->w_balans){

            $status = PremiumsManager::addPremium($id, $user->id);

            if($status == 1){
                $user->w_balans -= $price;
                $user->save();

                $action = new Actions();
                $action->user_id = $user->id;
                $action->time = time();
                $action->sum = $price;
                $action->type = 84;
                $action->status = 1;
                if ($actionTitle = PremiumsManager::getActionTitleByTariffId($tariffs->id)){
                    $action->title = $actionTitle;
                }else{
                    $action->title = "Вы успешно активировали Premium-аккаунт";
                }
                $action->save();

                Yii::$app->session->setFlash('success', 'Вы успешно активировали Premium-аккаунт');
                return $this->redirect('/profile');
            }
            else{
                Yii::$app->session->setFlash('danger', 'Ошибка! Premium-аккаунт был активирован навсегда');
                return $this->redirect('/profile');
            }

        }
        else{
            Yii::$app->session->setFlash('danger', 'На вашем счету недостаточно средств');
            return $this->redirect('/profile');
        }
    }

    public function actionIsActive(){
        $premiums = Premiums::find()->all();
        foreach ($premiums as $el){
            if($el->tariff_id != 6 && ($el->expires_at + $el->time < time())){
                $el->is_active = 0;
                $el->save();
            }
        }
    }
}