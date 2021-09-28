<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property string $text
 * @property int $ticket_id
 * @property int $user_id
 * @property int $link
 * @property int $time
 * @property int $is_private
 * @property int $is_payment
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'time'], 'required'],
            [['text', 'link'], 'string'],
            [['ticket_id', 'user_id', 'time'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'ticket_id' => 'Ticket ID',
            'user_id' => 'User ID',
            'link' => 'Link',
            'time' => 'Time',
        ];
    }
}
