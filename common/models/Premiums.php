<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "premiums".
 *
 * @property int $id
 * @property int $user_id
 * @property int $time
 * @property int $expires_at
 * @property int $is_active
 * @property string $img_source
 * @property int $tariff_id
 */
class Premiums extends \yii\db\ActiveRecord
{

    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'premiums';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'time', 'expires_at', 'tariff_id'], 'required'],
            [['user_id', 'time', 'expires_at', 'is_active', 'tariff_id'], 'integer'],
            [['img_source'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => ['png, jpg','JPG','PNG', 'jpeg','JPEG']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'time' => 'Время получения',
            'expires_at' => 'Истекает',
            'is_active' => 'Активен',
            'img_source' => 'Аватарка',
        ];
    }
}
