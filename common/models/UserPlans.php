<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_plans".
 *
 * @property int $id
 * @property int $user_id
 * @property int $start
 * @property int $global
 * @property int $personal
 * @property string $sum
 * @property int $status
 */
class UserPlans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_plans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'start', 'global', 'personal', 'status'], 'integer'],
            [['sum'], 'number'],
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
            'start' => 'Start',
            'global' => 'Global',
            'personal' => 'Personal',
            'sum' => 'Сумма награды',
            'status' => 'Status',
        ];
    }
}
