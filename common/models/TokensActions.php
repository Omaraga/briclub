<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tokens_actions".
 *
 * @property int $id
 * @property int $user_id
 * @property int $user2_id
 * @property string $sum
 * @property int $type
 * @property int $time
 * @property string $hash
 * @property string $tokens
 * @property string $change_course
 * @property string $bonus
 * @property int $bonus_percent
 */
class TokensActions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tokens_actions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user2_id', 'type', 'time', 'bonus_percent'], 'integer'],
            [['sum', 'tokens', 'change_course', 'bonus'], 'number'],
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
            'user_id' => 'User ID',
            'user2_id' => 'User2 ID',
            'sum' => 'Sum',
            'type' => 'Type',
            'time' => 'Time',
            'hash' => 'Hash',
            'tokens' => 'Tokens',
            'change_course' => 'Change Course',
            'bonus' => 'Bonus',
            'bonus_percent' => 'Bonus Percent',
        ];
    }
}
