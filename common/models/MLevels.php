<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "m_levels".
 *
 * @property int $id
 * @property string $title
 * @property int $price
 * @property int $count
 * @property int $withdrawal
 * @property int $fee
 */
class MLevels extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_levels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'count', 'withdrawal', 'fee'], 'integer'],
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
        ];
    }
}
