<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "promo_codes".
 *
 * @property int $id
 * @property int $user_id
 * @property string $promo
 * @property int $created_at
 */
class PromoCodes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promo_codes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at'], 'integer'],
            [['promo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'promo' => 'Promo',
            'created_at' => 'Created At',
        ];
    }
}
