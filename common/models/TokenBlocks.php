<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_blocks".
 *
 * @property int $id
 * @property int $count
 * @property int $time
 * @property string $hash
 */
class TokenBlocks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_blocks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['count', 'time'], 'integer'],
            [['hash'], 'string', 'max' => 510],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'count' => 'Count',
            'time' => 'Time',
            'hash' => 'Hash',
        ];
    }
}
