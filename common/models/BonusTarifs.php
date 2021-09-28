<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bonus_tarifs".
 *
 * @property int $id
 * @property string $title
 * @property int $sum
 * @property int $children
 */
class BonusTarifs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bonus_tarifs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sum', 'children'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'sum' => 'Sum',
            'children' => 'Children',
        ];
    }
}
