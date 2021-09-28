<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "event_tickets".
 *
 * @property int $id
 * @property int $type_id
 * @property int $event_id
 * @property int $user_id
 * @property string $link
 * @property int $status
 * @property int $time
 */
class EventTickets extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_tickets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id', 'event_id', 'user_id', 'status', 'time'], 'integer'],
            [['link'], 'string', 'max' => 510],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'event_id' => 'Event ID',
            'user_id' => 'User ID',
            'link' => 'Link',
            'status' => 'Status',
            'time' => 'Time',
        ];
    }
}
