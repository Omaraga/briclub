<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_pretrans".
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
class TokenPretrans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_pretrans';
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
}
