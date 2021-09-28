<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property int $date
 * @property int $status
 * @property string $token
 */
class Invoices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'course_id', 'date', 'status'], 'integer'],
            [['token'], 'string', 'max' => 510],
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
            'course_id' => 'Course ID',
            'date' => 'Date',
            'status' => 'Status',
            'token' => 'Token',
        ];
    }
}
