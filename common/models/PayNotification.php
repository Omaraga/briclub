<?php

namespace common\models;

use common\models\Actions;
use yii\base\Model;
use yii\db\Exception;

class PayNotification extends Model
{


    public static function add(Actions $action){
        $user = User::findOne($action->user_id);
        $date = date('H:i d.m.Y г', $action->time);
        $type = $action->title;
        if ($action->type){
            $type = ActionTypes::findOne($action->type)->title;
        }

        $sum = $action->sum;
        $message = 'Пополнение: <b>'.$user->username.'</b>%0a Сумма: '.$sum.'CV %0a '.$date.'%0aТип: '.$type;
        self::send($message);

    }
    private static function send($message){
        //https://api.telegram.org/bot1997292988:AAEek6PnEwzh-oKhGhychw3b4j06i2hUIu0/getUpdates - узнать id чата
        //1997292988:AAEek6PnEwzh-oKhGhychw3b4j06i2hUIu0 1001516693442

        $token = "1997292988:AAEek6PnEwzh-oKhGhychw3b4j06i2hUIu0";
        $chat_id = "-100151669344";
        $txt = $message;
        $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}";
        try {
            $sendTelegram = fopen($url, "r");
            if (!$sendTelegram){
                throw new Exception('Error');
            }
        }catch (\Exception $exception){
            return false;
        }
    }

}