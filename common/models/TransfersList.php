<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "actions".
 *
 * @property int $id
 * @property string $title
 * @property int $type
 * @property int $user_id
 * @property int $time
 * @property int $status
 * @property int $user2_id
 * @property string $sum
 * @property string $tokens
 * @property string $comment
 * @property int $admin_id
 * @property string $content
 * @property int $view
 */

class TransfersList extends yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'actions';
    }

    public function rules()
    {
        return [
            [['type', 'user_id', 'time', 'status', 'user2_id', 'admin_id', 'view' ,'target'], 'integer'],
            [['sum','tokens','fee'], 'number'],
            [['content'], 'string'],
            [['title', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Описание',
            'type' => 'Тип операции',
            'user_id' => 'Отправитель',
			'username' => 'Отправитель',
            'time' => 'Время',
			'dateFrom' => 'Дата от',
			'dateTo' => 'Дата до',
            'status' => 'Статус',
            'user2_id' => 'Получатель',
			'second_username' => 'Получатель',
            'sum' => 'Сумма',
            'comment' => 'Комментарий',
            'admin_id' => 'Админ',
            'content' => 'Содержание',
            'view' => 'Просмотрено',
        ];
    }

    public static function find()
    {
        return new TransfersListQuery(get_called_class());
    }

}