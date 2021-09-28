<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_lessons".
 *
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property int $status
 * @property int $start
 * @property int $course_id
 */
class UserLessons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_lessons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'lesson_id', 'status', 'start', 'course_id'], 'integer'],
        ];
    }

    public function __construct($userId = null, $lessonId = null, $courseId = null)
    {
        $this->lesson_id = $lessonId;
        $this->user_id = $userId;
        $this->course_id = $courseId;
        $this->start = time();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'lesson_id' => 'Lesson ID',
            'status' => 'Status',
            'start' => 'Start',
            'course_id' => 'Course ID',
        ];
    }

    /**
     * @param $userId
     * @param $lessonId
     * @return array|UserLessons|false|\yii\db\ActiveRecord
     */
    public static function isLessonPassed($userId, $lessonId){
        $userLesson = UserLessons::find()->where(['user_id' => $userId, 'lesson_id' => $lessonId])->one();
        if ($userLesson){
            return $userLesson;
        }
        return false;
    }


}
