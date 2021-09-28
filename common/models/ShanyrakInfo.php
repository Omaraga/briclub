<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shanyrak_info".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property string $link
 * @property int $program
 * @property string $price
 * @property string $description
 */
class ShanyrakInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shanyrak_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'description'], 'string'],
            [['program'], 'integer'],
            [['price'], 'number'],
            [['title'], 'string', 'max' => 255],
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
            'title' => 'Название',
            'text' => 'Описание',
            'link' => 'Изображение',
            'program' => 'Программа',
            'price' => 'Цена',
            'description' => 'Короткое описание',
        ];
    }
}
