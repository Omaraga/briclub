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
class Actions extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actions';
    }

    /**
     * {@inheritdoc}
     */
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
            'time' => 'Время',
            'status' => 'Статус',
            'user2_id' => 'Получатель',
            'sum' => 'Сумма',
            'comment' => 'Комментарий',
            'admin_id' => 'Админ',
            'content' => 'Содержание',
            'view' => 'Просмотрено',
        ];
    }
    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            $needTypes = [5,6,8,102];
            if (in_array($this->type, $needTypes)){
                PayNotification::add($this);
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }
}
