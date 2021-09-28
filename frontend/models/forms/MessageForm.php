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
class MessageForm extends Model
{
    public $file;
    public $text;
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
            [['text'], 'required'],
            [['text'], 'string'],
            [['file'], 'file', 'extensions' => ['png', 'jpg', 'pdf', 'doc', 'xls', 'docx', 'xlsx', 'jpeg'], 'maxSize' => 1024 * 1024 * 3],
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
            'title' => 'Тема',
            'file' => 'Файл',
        ];
    }

}
