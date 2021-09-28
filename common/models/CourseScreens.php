<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_screens".
 *
 * @property int $id
 * @property string $title
 * @property int $screen_id
 * @property int $position
 * @property int $course_id
 * @property int $is_active
 */
class CourseScreens extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course_screens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['screen_id', 'position', 'course_id', 'is_active'], 'integer'],
            [['title'], 'string', 'max' => 255],
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
            'screen_id' => 'Тип экрана',
            'position' => 'Позиция',
            'course_id' => 'ID курса',
            'is_active' => 'Статус',
        ];
    }
}
