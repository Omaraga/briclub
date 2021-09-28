<?php


namespace frontend\controllers;


use common\models\Actions;
use common\models\Bills;
use common\models\Tokens;
use common\models\User;
use Yii;
use yii\web\Controller;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionShanyrakLogin(){
        $email = \Yii::$app->request->post('email');
        $password = \Yii::$app->request->post('password');
        $user = User::findOne(['email' => $email]);
        if($user == null){
            $user = User::findOne(['username' => $email]);
        }
        $isCorrect = Yii::$app->security->validatePassword($password, $user->password_hash);

        if($user !== null && $isCorrect){
            $data = $user;
        }
        else{
            $data = ['error' => 'Введены неверные данные'];
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->asJson($data);
    }

    public function actionIsUserExist(){
        $email = Yii::$app->request->post('email');
        $user = User::findOne(['email' => $email]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if($user !== null){
            return $this->asJson('ok');
        }
        else{
            return $this->asJson('error');
        }
    }

    public function actionBill(){

        $request = Yii::$app->request;

        $id = Bills::find()->orderBy(['id' => SORT_DESC])->one();

        if($id == null){
            $id = 1;
        }
        else{
            $id = $id['id'];
        }

        $code = $id * 1992 * 29 * 4;

        $bill = new Bills();
        $bill->comment = $request->post('comment');
        $bill->sum = $request->post('sum');

        if($request->post('receiver_email')){
            $bill->receiver_id = User::findOne(['email' => $request->post('receiver_email')])['id'];
        }

        if($request->post('sender_email')){
            $bill->sender_id = User::findOne(['email' => $request->post('sender_email')])['id'];
        }
        else{
            $bill->sender_id = null;
        }

        $bill->status = 2; // в ожидании
        $bill->type = $request->post('type');
        $bill->created_at = time();
        $bill->code = $code;
        $bill->save(false);

        $action = new Actions();
        $action->status = 1;
        if($bill->type == 1){
            // greenswop
            $action->type = 69;
        }
        else if($bill->type == 3){
            // besroit
            $action->type = 70;
        }

        $action->title = $bill->comment;
        $action->comment = 'https://shanyrakplus.com/profile/pay-bill?code=' . $bill->code;

        $action->user_id = $bill->sender_id; //sender
        $action->user2_id = $bill->receiver_id; //receiver
        $action->tokens = $bill->sum;
        $action->time = time();
        $action->save(false);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->asJson($bill->code);
    }

    public function actionAddBonus(){
        $promo = Yii::$app->request->post('promo');
        $tokens = Yii::$app->request->post('tokens');
        $sum = Yii::$app->request->post('sum');

        $user = User::findOne(['username' => $promo]);
        if($user != null){
            $bonus_percent = 2.5;

            $us_bonus = $sum * $bonus_percent / 100;
            $grc_bonus = $tokens * $bonus_percent / 100;

            $user['w_balans'] += $us_bonus;
            $user->save();

            $bonus_tokens = Tokens::findOne(['user_id' => $user->id, 'wallet_type' => 8]);
            $bonus_tokens->balans += $grc_bonus;
            $bonus_tokens->save();

            $us_action = new Actions();
            $us_action->type = 72;
            $us_action->title = "Бонусы US за продажу токенов";
            $us_action->user_id = $user->id;
            $us_action->time = time();
            $us_action->status = 1;
            $us_action->sum = $us_bonus;
            $us_action->save();

            $grc_action = new Actions();
            $grc_action->type = 73;
            $grc_action->title = "Бонусы GRC за продажу токенов";
            $grc_action->user_id = $user->id;
            $grc_action->time = time();
            $grc_action->status = 1;
            $grc_action->tokens = $grc_bonus;
            $grc_action->save();
        }
    }
}