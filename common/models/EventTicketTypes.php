<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "event_ticket_types".
 *
 * @property int $id
 * @property string $title
 * @property int $event_id
 * @property int $count
 * @property string $price
 * @property string $link
 * @property int $status
 * @property int $time
 */
class EventTicketTypes extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_ticket_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'count', 'status', 'time'], 'integer'],
            [['price'], 'number'],
            [['title', 'link'], 'string', 'max' => 510],
            [['file'], 'file', 'extensions' => ['png, jpg','JPG','PNG','PDF', 'jpeg','JPEG','pdf','doc','docx']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'event_id' => 'Мероприятие',
            'count' => 'Всего билетов',
            'price' => 'Цена',
            'link' => 'Изображение',
            'file' => 'Изображение',
            'status' => 'Статус',
            'time' => 'Время',
        ];
    }
}
