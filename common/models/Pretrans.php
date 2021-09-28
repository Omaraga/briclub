<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pretrans".
 *
 * @property int $id
 * @property int $user_id
 * @property int $user2_id
 * @property string $sum
 * @property int $time
 * @property int $system_id
 * @property int $status
 * @property string $account
 * @property string $sum2
 * @property string $fee
 * @property int $admin_id
 * @property int $code
 */
class Pretrans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pretrans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user2_id', 'time', 'system_id', 'status', 'admin_id', 'code'], 'integer'],
            [['sum', 'sum2', 'fee'], 'number'],
            [['account'], 'string', 'max' => 255],
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
            'user2_id' => 'User2 ID',
            'sum' => 'Sum',
            'time' => 'Time',
            'system_id' => 'System ID',
            'status' => 'Status',
            'account' => 'Account',
            'sum2' => 'Sum2',
            'fee' => 'Fee',
            'admin_id' => 'Admin ID',
            'code' => 'Code',
        ];
    }
    public static function sendEmail($user_id,$code)
    {

        $user = User::findOne($user_id);
        $email = $user['email'];
        return \Yii::$app->mailer->compose(['html' => '@frontend/mail/code-html'], ['code' => $code,'user'=>$user])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
            ->setTo($email)
            ->setSubject('Подтверждение транзакции: ' . \Yii::$app->name)
            ->send();
    }
}
