<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_promotions_ref".
 *
 * @property int $id
 * @property int $pr_id
 * @property int $user_id
 * @property int $count
 * @property int $sum
 * @property int $status
 * @property int $shoulder
 * @property int $all
 * @property int $shoulder1
 * @property int $shoulder2
 * @property int $res
 * @property int $shoulder_next
 * @property int $res_next
 */
class UserPromotionsRef extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_promotions_ref';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

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
            'count' => 'Count',
            'sum' => 'Sum',
            'status' => 'Status',
            'shoulder' => 'Shoulder',
            'all' => 'All',
            'shoulder1' => 'Shoulder1',
            'shoulder2' => 'Shoulder2',
            'res' => 'Res',
            'shoulder_next' => 'Shoulder Next',
            'res_next' => 'Res Next',
        ];
    }
}
