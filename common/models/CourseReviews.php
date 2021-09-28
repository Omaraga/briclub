<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_reviews".
 *
 * @property int $id
 * @property string $name
 * @property int $course_id
 * @property string $content
 * @property string $img_url
 */
class CourseReviews extends \yii\db\ActiveRecord
{
    public $img;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course_reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['img'], 'file'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО',
            'course_id' => 'Курс',
            'content' => 'Отзыв',
            'img' => 'Фото',
        ];
    }

}
