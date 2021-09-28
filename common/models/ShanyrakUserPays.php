<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shanyrak_user_pays".
 *
 * @property int $id
 * @property int $user_id
 * @property int $program_id
 * @property int $bed_id
 * @property int $user_shanyrak_id
 * @property int $time_need
 * @property int $type
 * @property int $status
 * @property string $sum_need
 * @property string $sum_pay
 * @property int $time_pay
 * @property int $pan
 */
class ShanyrakUserPays extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shanyrak_user_pays';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'program_id', 'bed_id', 'user_shanyrak_id', 'time_need', 'type', 'status', 'time_pay', 'pan'], 'integer'],
            [['sum_need', 'sum_pay'], 'number'],
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
            'program_id' => 'Program ID',
            'bed_id' => 'Bed ID',
            'user_shanyrak_id' => 'User Shanyrak ID',
            'time_need' => 'Time Need',
            'type' => 'Type',
            'status' => 'Status',
            'sum_need' => 'Sum Need',
            'sum_pay' => 'Sum Pay',
            'time_pay' => 'Time Pay',
            'pan' => 'Pan',
        ];
    }
}
