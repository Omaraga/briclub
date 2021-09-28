<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "content".
 *
 * @property int $id
 * @property int $type
 * @property string $title
 * @property int $max_length
 * @property int $link
 * @property int $screen_course_id
 *
 * @property ContentTypes $type0
 * @property CourseScreens $screenCourse
 */
class Content extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'title', 'screen_course_id'], 'required'],
            [['type', 'max_length', 'group_id', 'screen_course_id'], 'integer'],
            [['title','link'], 'string'],
            [['file'], 'file', 'extensions' => 'png, jpg'],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => ContentTypes::className(), 'targetAttribute' => ['type' => 'id']],
            [['screen_course_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourseScreens::className(), 'targetAttribute' => ['screen_course_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'title' => 'Title',
            'max_length' => 'Max Length',
            'link' => 'Link',
            'screen_course_id' => 'Screen Course ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(ContentTypes::className(), ['id' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScreenCourse()
    {
        return $this->hasOne(CourseScreens::className(), ['id' => 'screen_course_id']);
    }
}
