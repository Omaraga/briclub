<?php

namespace frontend\controllers;
use common\models\MatrixRef;
use common\models\User;
use yii\db\Exception;
use yii\rest\Controller;
use Yii;
class RestController extends Controller
{

    public function actionMatrix(){

        //$this->enableCsrfValidation = false;
        if (Yii::$app->request->isPost){
            $username = Yii::$app->request->post('username');
            $level = Yii::$app->request->post('level');
            if (!$level){
                $level = 1;
            }
            $user = User::find()->where(['username' => $username])->one();
            if (!$user){
                return json_encode(['error' => 'Не найден пользователь']);
            }
            $matrixRef = MatrixRef::find()->where(['user_id'=> $user->id, 'platform_id' => $level])->orderBy('id ASC')->one();
            if (!$matrixRef){
                return json_encode(['error' => 'Матрица не найдена']);
            }
            $response = ['username' => $user->username, 'matrixId' => $matrixRef->id];
            return json_encode($response);
        }
    }
    public function actionMatrixChild(){
        if (Yii::$app->request->isPost){
            $id = Yii::$app->request->post('id');
            $matrixRef = MatrixRef::findOne($id);
            if ($matrixRef->shoulder1){
                $left = MatrixRef::findOne($matrixRef->shoulder1);
                $leftUser = User::findOne($left->user_id);
                $leftUsername = $leftUser->username;
                $leftId = $left->id;
            }else{
                $left = null;
                $leftUser = null;
                $leftUsername = null;
                $leftId = null;
            }
            if ($matrixRef->shoulder2){
                $right = MatrixRef::findOne($matrixRef->shoulder2);
                $rightUser = User::findOne($right->user_id);
                $rightUsername = $rightUser->username;
                $rightId = $right->id;
            }else{
                $right = null;
                $rightUser = null;
                $rightUsername = null;
                $rightId = null;
            }
            $response = ['left' => ['username' => $leftUsername, 'matrixId' => $leftId], 'right' => ['username' => $rightUsername, 'matrixId' => $rightId]];
            return json_encode($response);
        }
    }
    private function sendTelegramNotification($notification, $localPass = null)
    {
        //https://api.telegram.org/bot1962918487:AAGjXyBbJDEBO5rRmGi3zw5K-X99bJH_cOE/getUpdates - узнать id чата
        //1962918487:AAGjXyBbJDEBO5rRmGi3zw5K-X99bJH_cOE
        if ($localPass == 'ShanyrakPlus+'){
            $token = "1962918487:AAGjXyBbJDEBO5rRmGi3zw5K-X99bJH_cOE";
            $chat_id = "-576194718";
            $txt = $notification;
            $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}";
            try {
                $sendTelegram = fopen($url, "r");
                if (!$sendTelegram){
                    throw new Exception('Error');
                }
            }catch (\Exception $exception){
                return false;
            }
        }else{
            return false;
        }

    }

    public function actionOrderTelegram(){
        $time = time();
        $start_time = $time - 24*60*60;
        $users = User::find()->where(['>=','time_personal',$start_time])->andWhere(['<=','created_at',$time])->orderBy('time_personal ASC')->all();
        $text = "<b>Отчет активированных с ".date('d.m.Y H:i', $start_time)." по ".date('d.m.Y H:i',$time)."</b>%0a %0a";
        $i = 1;
        foreach ($users as $user){
            $username = $user->username;
            $date = date('d.m.Y H:i', $user->time_personal);
            $sponsorId = $user->parent_id;
            if ($sponsorId && ($sponsorId) > 0){
                $sponsor = User::findOne($sponsorId);
                $sponsorName = $sponsor->username;
            }else{
                $sponsorName = 'Без спонсора';
            }
            $text.=$i.') Логин: <b>'.$username.'%0a</b> Дата активации: <b>'.$date.'%0a</b> Спонсор: <b>'.$sponsorName.'</b>%0a %0a';
            $sponsor = null;
            $sponsorName = null;
            $sponsorId = null;
            $i++;
        }
        if ($users){
            $this->sendTelegramNotification($text, "ShanyrakPlus+");
        }

    }


}