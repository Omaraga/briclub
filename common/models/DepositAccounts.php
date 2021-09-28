<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deposit_accounts".
 *
 * @property string $account
 * @property int $system
 * @property int $id
 */
class DepositAccounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deposit_accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account'], 'string'],
            [['system'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'account' => 'Текст',
            'system' => 'Сервис',
            'id' => 'ID',
        ];
    }
}
