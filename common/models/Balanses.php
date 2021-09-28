<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "balanses".
 *
 * @property int $id
 * @property string $title
 * @property string $sum
 */
class Balanses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'balanses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sum'], 'number'],
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
            'title' => 'Название',
            'sum' => 'Баланс',
        ];
    }
}
