<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "library".
 *
 * @property int $id
 * @property string $alias
 * @property int $type
 * @property string $title
 * @property string $link
 * @property int $status
 * @property int $order
 */
class Library extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $file;
    public $file2;
    public static function tableName()
    {
        return 'library';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'order'], 'integer'],
            [['alias', 'title', 'link','link2'], 'string', 'max' => 510],
            [['file','file2'], 'file', 'extensions' => ['png, jpg','JPG','PNG','PDF', 'jpeg','JPEG','pdf','doc','docx', 'zip', 'ZIP', 'rar', 'RAR']],
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
            'file' => 'Книга',
            'file2' => 'Обложка',
            'status' => 'Статус',
            'order' => 'Очередь',
        ];
    }
}
