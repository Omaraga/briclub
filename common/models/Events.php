<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property string $text
 * @property int $time
 * @property string $link
 * @property int $status
 * @property int $order
 */
class Events extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['time', 'status', 'order'], 'integer'],
            [['title', 'link'], 'string', 'max' => 510],
            [['start_date', 'end_date'], 'string', 'max' => 255],
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
            'start_date' => 'Дата начала',
            'end_date' => 'Дата окончания',
            'text' => 'Описание',
            'time' => 'Time',
            'link' => 'Изображение',
            'file' => 'Изображение',
            'status' => 'Статус',
            'order' => 'Очередь',
            'slider' => 'Вывести на слайдер',
        ];
    }
}
