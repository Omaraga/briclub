<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lesson_docs".
 *
 * @property int $id
 * @property string $title
 * @property string $link
 * @property int $lesson_id
 * @property int $time
 * @property int $type
 * @property string $link2
 */
class LessonDocs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $file;
    public static function tableName()
    {
        return 'lesson_docs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_id', 'time', 'type'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['link', 'link2'], 'string', 'max' => 510],
            [['file'], 'file', 'extensions' => ['png', 'jpg', 'pdf', 'doc', 'xls', 'docx', 'xlsx','pptx'], 'maxSize' => 1024 * 1024 * 4],
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
            'link' => 'Ссылка',
            'link2' => 'Ссылка',
            'lesson_id' => 'Урок',
            'time' => 'Время загрузки',
            'type' => 'Тип',
            'file' => 'Документ',
        ];
    }
}
