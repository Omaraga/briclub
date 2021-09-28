<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exceptions".
 *
 * @property int $id
 * @property int $user_id
 */
class Exceptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $username;
    public static function tableName()
    {
        return 'exceptions';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['username'], 'string'],
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
            'username' => 'Логин',
        ];
    }
}
