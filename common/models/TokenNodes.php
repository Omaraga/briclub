<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_nodes".
 *
 * @property int $id
 * @property int $type
 * @property int $user_id
 * @property string $tokens
 */
class TokenNodes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_nodes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'user_id'], 'integer'],
            [['tokens'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'user_id' => 'User ID',
            'tokens' => 'Tokens',
        ];
    }
}
