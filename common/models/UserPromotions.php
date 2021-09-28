<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_promotions".
 *
 * @property int $id
 * @property int $pr_id
 * @property int $user_id
 * @property int $tarif_id
 * @property int $status
 */
class UserPromotions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_promotions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pr_id', 'user_id', 'tarif_id', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pr_id' => 'Pr ID',
            'user_id' => 'User ID',
            'tarif_id' => 'Tarif ID',
            'status' => 'Status',
        ];
    }
}
