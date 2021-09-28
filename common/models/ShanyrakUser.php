<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shanyrak_user".
 *
 * @property int $id
 * @property int $user_id
 * @property int $program_id
 * @property int $time
 * @property int $step
 * @property int $status
 */
class ShanyrakUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shanyrak_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'program_id', 'time', 'step', 'status'], 'integer'],
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
            'time' => 'Time',
            'step' => 'Step',
            'status' => 'Status',
        ];
    }
}
