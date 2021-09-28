<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_nodes_queries".
 *
 * @property int $id
 * @property int $user_id
 * @property int $tokens_count
 * @property int $admin_id
 * @property int $query_date
 * @property int $status
 */
class TokenNodesQueries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_nodes_queries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'tokens_count', 'query_date', 'status'], 'required'],
            [['user_id', 'tokens_count', 'admin_id', 'query_date', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'user_id' => 'Id пользователя',
            'tokens_count' => 'Количество токенов',
            'admin_id' => 'Id админа',
            'query_date' => 'Дата заявки',
            'status' => 'Статус',
        ];
    }
}
