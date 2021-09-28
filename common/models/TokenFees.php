<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_fees".
 *
 * @property int $id
 * @property string $title
 * @property string $type_table
 * @property int $platform_id
 * @property string $fee_token
 * @property string $fee_percent
 * @property int $type_id
 */
class TokenFees extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_fees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['platform_id', 'type_id'], 'integer'],
            [['fee_token', 'fee_percent'], 'number'],
            [['title', 'type_table'], 'string', 'max' => 255],
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
            'type_table' => 'Type Table',
            'platform_id' => 'Platform ID',
            'fee_token' => 'Fee Token',
            'fee_percent' => 'Fee Percent',
            'type_id' => 'Type ID',
        ];
    }
}
