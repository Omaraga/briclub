<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "events_and_spikers".
 *
 * @property int $id
 * @property int $event_id
 * @property int $spiker_id
 */
class EventsAndSpikers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events_and_spikers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'spiker_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
            'spiker_id' => 'Spiker ID',
        ];
    }
}
