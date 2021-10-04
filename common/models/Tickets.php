<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tickets".
 *
 * @property int $id
 * @property int $category
 * @property string $title
 * @property int $time
 * @property int $user_id
 * @property int $status
 * @property int $is_to_dev
 * @property int $end_time
 * @property int $dev_status
 * @property int $bill_id
 * @property int $payment_status
 */
class Tickets extends \yii\db\ActiveRecord
{
    const PAYMENT_STATUS_NOT = 0;
    const PAYMENT_STATUS_NEED_PAY = 1;
    const PAYMENT_STATUS_PAYED = 2;
    const STATUS_CLOSE = 1;
    const STATUS_ANSWERED = 2;
    const STATUS_WORK = 3;
    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes){
        if ($insert){
            parent::afterSave($insert, $changedAttributes);
            $message = \Yii::$app->mailer->compose();
            $username = User::findOne($this->user_id)['username'];
            $emails = ['bakhti.info@mail.ru','support@gcfond.com','gzhani257@gmail.com','adammm1712@gmail.com'];

            $message
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($emails)
                ->setSubject('Новый запрос в тех поддержку')
                ->setTextBody("Здравствуйте, $username оставил запрос в тех поддержку. Номер запроса: $this->id")
                ->setHtmlBody("Здравствуйте, $username оставил запрос в тех поддержку. Номер запроса: $this->id")
                ->send();
        }

        //Yii::$app->getSession()->setFlash('success', Yii::t('users', 'SUCCESS BED'));
    }

    public static function tableName()
    {
        return 'tickets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'time', 'user_id', 'status'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'title' => 'Title',
            'time' => 'Time',
            'user_id' => 'User ID',
            'status' => 'Status',
        ];
    }
    public function getTimeToEnd(){
        $endTime = $this->time;


        return $endTime;
    }
}
