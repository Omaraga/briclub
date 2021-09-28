<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property int $balans_pl0
 * @property int $balans_pl1
 * @property int $balans_fee
 * @property int $balans_pl7
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['balans_pl0', 'balans_pl1', 'balans_fee', 'balans_pl7'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'balans_pl0' => 'Balans Pl0',
            'balans_pl1' => 'Balans Pl1',
            'balans_fee' => 'Balans Fee',
            'balans_pl7' => 'Balans Pl7',
        ];
    }
}
