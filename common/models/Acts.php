<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "acts".
 *
 * @property int $id
 * @property int $user_id
 * @property int $time
 * @property string $link
 */
class Acts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'time'], 'integer'],
            [['link'], 'string', 'max' => 510],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'time' => 'Time',
            'link' => 'Link',
        ];
    }
    public static function sendEmail($user_id,$link)
    {
        $message = \Yii::$app->mailer->compose();
        $dir = Yii::getAlias('@frontend/web'.$link);
        $dir2 = Yii::getAlias('@frontend/web/certs/rekassa.pdf');
        $message->attach($dir);
        $message->attach($dir2);
        $user = User::findOne($user_id);
        $username = $user['username'];
        $email = $user['email'];
        $message
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($email)
            ->setSubject('Успешная подписка на библиотеку')
            ->setTextBody("Здравствуйте, $username .Поздравляем! Ваша подписка на онлайн библиотеку активирована!")
            ->setHtmlBody("Здравствуйте, $username .Поздравляем! Ваша подписка на онлайн библиотеку активирована!")
            ->send();
    }
}
