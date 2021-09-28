<?php

namespace frontend\components;

use app\models\Actions;
use app\models\Activities;
use app\models\Kurses;
use app\models\UserInfo;
use app\modules\users\models\forms\DepositUsdcForm;
use app\modules\users\models\forms\DepositUsdcPassForm;
use app\modules\users\models\forms\EdrForm;
use app\modules\users\models\forms\PassEdrForm;
use app\modules\users\models\forms\PassForm;
use app\modules\users\models\forms\TransfersForm;
use app\modules\users\models\forms\WithdrawBtcForm;
use app\modules\users\models\forms\WithdrawPassBtcForm;
use app\modules\users\models\User;
use yii\base\Widget;
use Yii;

class DepositUsdcWidget extends Widget
{
    public function run()
    {
        $user = Yii::$app->user->identity;
        $username = $user['username'];
        $DepositUsdcForm = new DepositUsdcForm();
        $DepositUsdcPassForm = new DepositUsdcPassForm();
        $kurs = Kurses::findOne(1)['price'];
        $balans = UserInfo::find()->where(['user_id'=>$user['id']])->one()['btc'];
        $canusdc = $balans*$kurs;
        $usd = null;
        $validation = false;
        $success = false;

        if ($DepositUsdcForm->load(Yii::$app->request->post()) && $DepositUsdcForm->validate()) {

            $usd = $DepositUsdcForm->usd;
            $validation = true;
        }

        if ($DepositUsdcPassForm->load(Yii::$app->request->post())) {
            $validation = true;
            if($DepositUsdcPassForm->validate()){
                $success = true;
                $user = UserInfo::find()->where(['user_id'=>$user['id']])->one();
                $user = UserInfo::findOne($user['id']);
                $user->bonus = $user->bonus - $DepositUsdcPassForm->usd/$kurs;
                $user->usdc = $user->usdc + $DepositUsdcPassForm->usd;
                $user->save();

                $action = new Activities();
                $action->date = date("d.m.Y H:i");
                $action->sum = "-".$DepositUsdcPassForm->usd/$kurs;
                $action->user_id = $user['user_id'];
                $action->username = $username;
                $action->type = 17;
                $action->status = 1;
                $action->type_text = "Перевод баланса BTC на USDc";
                $action->save();

                $action = new Activities();
                $action->date = date("d.m.Y H:i");
                $action->sum = $DepositUsdcPassForm->usd;
                $action->user_id = $user['user_id'];
                $action->username = $username;
                $action->type = 19;
                $action->status = 1;
                $action->type_text = "Пополнение баланса USDc";
                $action->save();

            }
        }


        return $this->render('depositusdc', [
            'DepositUsdcForm' => $DepositUsdcForm,
            'DepositUsdcPassForm' => $DepositUsdcPassForm,
            'balans' => $balans,
            'canusdc' => $canusdc,
            'usd' => $usd,
            'kurs' => $kurs,
            'validation' => $validation,
            'success' => $success,
        ]);

    }
}