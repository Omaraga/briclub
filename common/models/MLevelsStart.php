<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "m_levels_start".
 *
 * @property int $id
 * @property string $title
 * @property string $price
 * @property int $count
 * @property int $withdrawal
 * @property int $fee
 * @property string $line1
 * @property string $line2
 */
class MLevelsStart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_levels_start';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'line1', 'line2'], 'number'],
            [['count', 'withdrawal', 'fee'], 'integer'],
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
            'price' => 'Price',
            'count' => 'Count',
            'withdrawal' => 'Withdrawal',
            'fee' => 'Fee',
            'line1' => 'Line1',
            'line2' => 'Line2',
        ];
    }
}
