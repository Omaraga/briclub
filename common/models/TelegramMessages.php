<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tlgrm_messages".
 *
 * @property int $id
 * @property int $user_id
 * @property int $chat_id
 * @property string $message
 * @property int $created_at
 * @property int $updated_at
 */
class TelegramMessages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tlgrm_messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'chat_id', 'message', 'created_at'], 'required'],
            [['id', 'user_id', 'chat_id', 'created_at', 'updated_at'], 'integer'],
            [['message'], 'string', 'max' => 255],
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
            'chat_id' => 'Chat ID',
            'message' => 'Message',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
