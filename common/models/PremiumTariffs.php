<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "premium_tariffs".
 *
 * @property int $id
 * @property int $time
 * @property string $name
 * @property int $price
 */
class PremiumTariffs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'premium_tariffs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time', 'name'], 'required'],
            [['time', 'price'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'name' => 'Name',
            'price' => 'Price',
        ];
    }
}
