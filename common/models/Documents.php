<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "documents".
 *
 * @property int $id
 * @property string $alias
 * @property int $type
 * @property string $title
 * @property string $link
 * @property string $link2
 * @property int $status
 * @property int $description
 */
class Documents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $file;
    public static function tableName()
    {
        return 'documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status','order'], 'integer'],
            [['alias', 'title', 'link', 'link2'], 'string', 'max' => 510],
            [['description'], 'string'],
            [['file'], 'file', 'extensions' => ['png, jpg','JPG','PNG','PDF', 'jpeg','JPEG','pdf','doc','docx', 'zip', 'ZIP', 'rar', 'RAR']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Алиас',
            'type' => 'Тип',
            'title' => 'Название',
            'link' => 'Ссылка',
            'file' => 'Документ',
            'status' => 'Статус',
            'order' => 'Очередь',
        ];
    }
}
