<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $alias
 * @property int $type
 * @property string $title
 * @property string $link
 * @property int $status
 * @property int $order
 * @property int $slider
 * @property string $text
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $file;
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'order', 'slider', 'time'], 'integer'],
            [['text'], 'string'],
            [['alias', 'title', 'link'], 'string', 'max' => 510],
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
            'alias' => 'Alias',
            'type' => 'Тип',
            'title' => 'Заголовок',
            'link' => 'Изображение',
            'file' => 'Изображение',
            'status' => 'Статус',
            'order' => 'Порядок',
            'text' => 'Содержание',
            'slider' => 'Вывести на слайдер',
        ];
    }
}
