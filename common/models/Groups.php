<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string $title
 * @property int $group_id
 * @property int $course_screen_id
 * @property int $screen_id
 */
class Groups extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'course_screen_id', 'screen_id'], 'integer'],
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
            'title' => 'Title',
            'group_id' => 'Group ID',
            'course_screen_id' => 'Course Screen ID',
            'screen_id' => 'Screen ID',
        ];
    }
}
