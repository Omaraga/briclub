<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_courses".
 *
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property string $date
 */
class UserCourses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'course_id','date'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'course_id' => 'Course ID',
            'date' => 'Date',
        ];
    }

    /**
     * @param $user_id
     * @param $course_id
     * @return bool
     */
    public static function isAccess($user_id,$course_id){
        $course = Courses::findOne($course_id);

        $user_course = UserCourses::find()->where(['user_id'=>$user_id,'course_id'=>$course_id])->one();
        if(!empty($user_course)){
            return true;
        }else{
            return false;
        }

    }


}
