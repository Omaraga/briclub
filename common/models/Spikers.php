<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "spikers".
 *
 * @property int $id
 * @property string $fio
 * @property string $img_url
 */
class Spikers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spikers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'img_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'Fio',
            'img_url' => 'Img Url',
        ];
    }
}
