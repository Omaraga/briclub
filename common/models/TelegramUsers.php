<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tlgrm_users".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $language_code
 * @property int $created_at
 * @property int $updated_at
 */
class TelegramUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tlgrm_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'username', 'language_code', 'created_at'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['first_name', 'last_name', 'username', 'language_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'username' => 'Username',
            'language_code' => 'Language Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
