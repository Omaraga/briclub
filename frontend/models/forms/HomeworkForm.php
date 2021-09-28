<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;

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
class HomeworkForm extends Model
{
    public $file;
    public $text;
    public $user_id;
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
            [['user_id','text'], 'required'],
            [['text'], 'string'],
            ['file', 'file', 'extensions' => ['png', 'jpg', 'pdf', 'doc', 'xls', 'docx', 'xlsx'], 'maxSize' => 1024 * 1024 * 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Текст',
            'user_id' => 'Пользователь',
            'file' => 'Файл',
        ];
    }

}
