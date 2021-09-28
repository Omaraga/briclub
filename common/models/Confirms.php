<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "confirms".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status
 */
class Confirms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
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
            'status' => 'Status',
        ];
    }
}
