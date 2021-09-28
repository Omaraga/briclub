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
class Doc3Form extends Model
{
    public $file;
    public $bed_id;
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
            [['file','bed_id'], 'required'],
            ['file', 'file', 'extensions' => ['png', 'jpg', 'pdf'], 'maxSize' => 1024 * 1024 * 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bed_id' => 'Пользователь',
            'file' => 'Договор',
        ];
    }

}
