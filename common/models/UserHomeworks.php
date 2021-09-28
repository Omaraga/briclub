<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_homeworks".
 *
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property string $text
 * @property string $link
 * @property int $time
 * @property int $is_admin
 */
class UserHomeworks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_homeworks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'lesson_id', 'time', 'is_admin'], 'integer'],
            [['text'], 'string'],
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
            'user_id' => 'User ID',
            'lesson_id' => 'Lesson ID',
            'text' => 'Text',
            'link' => 'Link',
            'time' => 'Time',
            'is_admin' => 'Is Admin',
        ];
    }
    public static function getCurrent($user_id){
        $last_work = self::find()->where(['user_id'=>$user_id])->orderBy('time desc')->one();
        $last_lesson = Lessons::findOne($last_work['lesson_id']);
        $current = Lessons::find()->where(['part_id'=>$last_lesson['part_id']])->andWhere(['>','position',$last_lesson['position']])->orderBy('position desc')->one();
        if(empty($last_work)){
            $first_lesson = Lessons::find()->orderBy('position asc')->one();
            return $first_lesson;
        }else{
            return $current;
        }
    }
}
