<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bills".
 *
 * @property int $id
 * @property string $code
 * @property string $sum
 * @property string $comment
 * @property int $created_at
 * @property int $receiver_id
 * @property int $sender_id
 * @property int $status
 * @property int $type
 * @property int $updated_at
 */
class Bills extends \yii\db\ActiveRecord
{
    public $sender_login;
    public $receiver_login;
    public $ticket_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bills';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'sum', 'comment', 'created_at', 'receiver_id', 'status', 'type'], 'required'],
            [['sum'], 'number'],
            [['ticket_id'], 'safe'],
            [['comment'], 'string'],
            [['created_at', 'receiver_id', 'sender_id', 'status', 'type', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'sum' => 'Сумма',
            'comment' => 'Комментарий',
            'created_at' => 'Создано',
            'receiver_id' => 'Получатель',
            'sender_id' => 'Отправитель',
            'status' => 'Статус',
            'type' => 'Тип',
            'updated_at' => 'Изменено',
        ];
    }
}
