<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "audios".
 *
 * @property int $id
 * @property string $title
 * @property string $link
 * @property int $lib_id
 * @property string $time
 * @property int $status
 *
 * @property Library $lib
 */
class Audios extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lib_id', 'status'], 'integer'],
            [['time'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 510],
            [['lib_id'], 'exist', 'skipOnError' => true, 'targetClass' => Library::className(), 'targetAttribute' => ['lib_id' => 'id']],
            [['file'], 'file', 'extensions' => ['mp3','mp4','wav','flac'], 'maxFiles' => 50]
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
            'lib_id' => 'ID книги',
            'time' => 'Время',
            'status' => 'Статус',
            'file' => 'Файл'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLib()
    {
        return $this->hasOne(Library::className(), ['id' => 'lib_id']);
    }
}
