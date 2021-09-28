<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "countries".
 *
 * @property int $id
 * @property string $title
 * @property int $code
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'countries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'integer'],
            [['title', 'link' ], 'string', 'max' => 255],
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
            'code' => 'Код',
            'link' => 'Флаг',
        ];
    }
}
