<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_node_types".
 *
 * @property int $id
 * @property string $title
 * @property string $tokens
 * @property int $percent
 */
class TokenNodeTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_node_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tokens'], 'number'],
            [['percent'], 'integer'],
            [['title'], 'string', 'max' => 255],
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
            'tokens' => 'Tokens',
            'percent' => 'Percent',
        ];
    }
}
