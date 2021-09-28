<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_promotions_new".
 *
 * @property int $id
 * @property int $pr_id
 * @property int $user_id
 * @property int $tarif_id
 * @property int $status
 * @property int $all
 * @property int $shoulder1
 * @property int $shoulder2
 */
class UserPromotionsNew extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_promotions_new';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pr_id', 'user_id', 'tarif_id', 'status', 'all', 'shoulder1', 'shoulder2'], 'integer'],
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
            'all' => 'All',
            'shoulder1' => 'Shoulder1',
            'shoulder2' => 'Shoulder2',
        ];
    }
}
