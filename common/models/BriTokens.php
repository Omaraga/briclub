<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bri_tokens".
 *
 * @property int $id
 * @property int $user_id
 * @property string $balans
 */
class BriTokens extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bri_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['balans'], 'number'],
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
            'balans' => 'Balans',
        ];
    }
}
