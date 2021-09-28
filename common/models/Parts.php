<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "parts".
 *
 * @property int $id
 * @property string $title
 * @property int $course_id
 * @property int $position
 * @property int $is_intro
 * @property int $free
 */
class Parts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id','free', 'is_intro'], 'integer'],
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
            'course_id' => 'Курс',
            'free' => 'Бесплатно',
            'is_intro' => 'Вводный урок',
        ];
    }

    public function getModulePosition(){
        $modules = Parts::find()->where(['course_id' => $this->course_id, 'is_intro' => 0])->orderBy('position ASC')->all();
        $counter = 1;
        foreach ($modules as $module){
            if ($module->id == $this->id){
                return $counter;
            }
            $counter++;
        }
    }
}
