<?php

namespace common\models\logic;

use common\models\Premiums;
use common\models\PremiumTariffs;

class PremiumsManager
{
    public static function addPremium($tariff_id, $user_id){

        $tariff = PremiumTariffs::findOne($tariff_id);

        $premium = Premiums::findOne(['user_id' => $user_id]);

        if($premium == null){
            $premium = new Premiums();
            $premium->user_id = $user_id;
            $premium->time = time();
        }
        else if($premium->tariff_id == 6){
            return 0;
        }
        else if($premium->time + $premium->expires_at > time()){
            $premium->time += $premium->expires_at;
        }else if($premium->time + $premium->expires_at < time()){
            $premium->time = time();
        }

        $premium->expires_at = $tariff->time;
        $premium->tariff_id = $tariff->id;
        $premium->is_active = 1;

        $premium->save();

        return 1;
    }

    /**
     * @param $tariffId integer
     * @return false | string
     */
    public static function getActionTitleByTariffId($tariffId){
        /* @var $tariff PremiumTariffs*/
        $tariff = PremiumTariffs::findOne($tariffId);
        if ($tariff){
            if ($tariff->id == 1){
                return "Вы успешно активировали Premium-аккаунт на неделю";
            }else if($tariff->id == 2){
                return "Вы успешно активировали Premium-аккаунт на месяц";
            }else if($tariff->id == 3){
                return "Вы успешно активировали Premium-аккаунт на 3 месяца";
            }else if($tariff->id == 4){
                return "Вы успешно активировали Premium-аккаунт на 6 месяцев";
            }else if($tariff->id == 5){
                return "Вы успешно активировали Premium-аккаунт на год";
            }else if($tariff->id == 6){
                return "Вы успешно активировали Premium-аккаунт";
            }

        }
        return false;

    }
}