<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_bonuses".
 *
 * @property int $id
 * @property string $percent
 * @property int $expiry_date
 * @property int $count_from
 * @property int $count_to
 */
class TokenBonuses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_bonuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['percent', 'expiry_date', 'count_from', 'count_to'], 'required'],
            [['percent'], 'number'],
            [['expiry_date', 'count_from', 'count_to'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'percent' => 'Percent',
            'expiry_date' => 'Expiry Date',
            'count_from' => 'Count From',
            'count_to' => 'Count To',
        ];
    }
}
