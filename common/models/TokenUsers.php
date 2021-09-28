<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_users".
 *
 * @property int $id
 * @property int $user_id
 * @property string $login
 */
class TokenUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['login'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'user_id' => 'Id пользователя',
            'login' => 'Логин',
        ];
    }
}
