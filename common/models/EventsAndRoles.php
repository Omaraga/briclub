<?php

namespace common\models;

use phpDocumentor\Reflection\Types\Integer;
use Yii;

/**
 * This is the model class for table "events_and_roles".
 *
 * @property int $id
 * @property int $event_id
 * @property int $role_id
 */
class EventsAndRoles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events_and_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'role_id'], 'integer'],
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
            'role_id' => 'Role ID',
        ];
    }

    public static function getEventRoles(Events $event){
        return EventsAndRoles::find()->where(['event_id' => $event->id])->all();
    }

    public static function isHasPare(Events $event, $role){
        $obj = EventsAndRoles::find()->where(['event_id' => $event->id, 'role_id' => $role])->one();
        if ($obj){
            return true;
        }else{
            return false;
        }
    }
}
