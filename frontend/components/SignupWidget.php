<?php
namespace frontend\components;

use common\models\Beds;
use common\models\Courses;
use common\models\User;
use frontend\models\SignupForm;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class SignupWidget extends Widget
{
    public function run() {

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            if ($user = $model->signup()) {
                if ($user->createEmailConfirmToken() && $user->sendEmailConfirmationMail(Yii::$app->controller->getCustomMailView('confirmNewEmail'), 'new_email')) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'CHECK_YOUR_EMAIL_FOR_FURTHER_INSTRUCTION'));
                    $user2 = User::findOne($user['id']);
                    Yii::$app->user->login($user2);
                    $transaction->commit();
                    return true;//Yii::$app->response->refresh();
                } else {
                    Yii::$app->getSession()->setFlash('error', Yii::t('users', 'CAN_NOT_SEND_EMAIL_FOR_CONFIRMATION'));
                    $transaction->rollBack();
                };
            }else {
                Yii::$app->getSession()->setFlash('error', Yii::t('users', 'CAN_NOT_CREATE_NEW_USER'));
                $transaction->rollBack();
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);

    }
}