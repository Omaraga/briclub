<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "withdraws".
 *
 * @property int $id
 * @property int $user_id
 * @property string $sum
 * @property int $time
 * @property int $system_id
 * @property int $status
 * @property string $account
 * @property string $sum2
 * @property string $fee
 * @property int $admin_id
 * @property int $type
 */
class Withdraws extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'withdraws';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'time', 'system_id', 'status', 'admin_id'], 'integer'],
            [['sum', 'sum2', 'fee'], 'number'],
            [['system_id', 'account'], 'required'],
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
            'user_id' => 'Пользователь',
            'sum' => 'Сумма',
            'time' => 'Время',
            'system_id' => 'Платежная система',
            'status' => 'Статус',
            'account' => 'Счет',
            'sum2' => 'На вывод',
            'fee' => 'Комиссия',
        ];
    }

    public static function getWithdraws(){

    }
}
