<?php


namespace frontend\models\forms;
use yii\base\Model;
use Yii;

class InsuranceEmailForm extends Model
{

    public static function sendInsuranceEmail($user, $file1, $file2, $country, $city, $phone, $email){

        return Yii::$app->mailer->compose()
            ->setFrom([\Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo(array('centrasinsurance24@gmail.com','support@briincorp.com'))
            ->setSubject('Данные страхования: ' . $user->username)
            ->setHtmlBody('Данные для страхования пользователя: '.$user->username.'<br>'. 'Страна: '.$country.'<br>'.'Город: '.$city.'<br>'.'Адрес: '.$address.'<br>'.'Телефон: '.$phone.'<br>'.'Email: '.$email.'<br>'.'Фото удостоверения прикреплены.')
            ->attach($file1)
            ->attach($file2)
            ->send();
    }
}