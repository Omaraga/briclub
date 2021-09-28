<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_emails".
 *
 * @property int $id
 * @property int $user_id
 * @property string $old_email
 * @property string $new_email
 * @property string $username
 * @property int $time
 */
class UserEmails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_emails';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'old_email', 'new_email', 'username', 'time'], 'required'],
            [['user_id', 'time'], 'integer'],
			[['username', 'old_email', 'new_email'], 'string', 'max' => 255],
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
            'old_email' => 'Old Email',
            'new_email' => 'New Email',
            'username' => 'Username',
        ];
    }
}
