<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "insurances".
 *
 * @property int $id
 * @property string $img
 * @property string $country
 * @property string $city
 * @property string $phone
 * @property string $email
 * @property int $user_id
 * @property string $img2
 * @property int $created_at
 * @property int $status
 */
class Insurances extends \yii\db\ActiveRecord
{
    const STATUS_EMPTY = 0;
    const STATUS_APPROVED = 1;
    const STATUS_MODERATION = 2;
    const STATUS_DECLINED = 3;

    public $file;
    public $file2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insurances';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['img', 'country', 'city', 'address', 'phone', 'email', 'user_id', 'created_at', 'file', 'file2'], 'required'],
            [['user_id', 'created_at', 'status'], 'integer'],
            [['img', 'country', 'city', 'address', 'phone', 'email', 'img2'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => ['jpeg','png','jpg',]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'img' => 'Скан уд. личности',
            'country'=> 'Страна',
            'city' => 'Город',
            'phone' => 'Телефон',
            'email' => 'Email',
            'user_id' => 'Пользователь',
            'img2' => 'Обратная сторона',
            'status' => 'Статус',
            'created_at' => 'Дата',
        ];
    }
}
