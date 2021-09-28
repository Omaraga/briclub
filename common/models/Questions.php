<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "questions".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $text
 * @property int $course_id
 */
class Questions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'text', 'course_id'], 'required'],
            [['text'], 'string'],
            [['course_id'], 'integer'],
            [['name', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'text' => 'Text',
            'course_id' => 'Course ID',
        ];
    }
}
