<?php


namespace common\models;

/**
 * @property int $id
 * @property int $category
 * @property string $title
 * @property int $time
 * @property int $user_id
 * @property int $status
 */

class TicketsList extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tickets';
    }

    public function rules()
    {
        return [
            [['category', 'time', 'user_id', 'status'], 'integer'],
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
            'category' => 'Категория',
            'title' => 'Название',
            'time' => 'Время',
            'user_id' => 'Пользователь',
			'username' => 'Пользователь',
            'status' => 'Статус',
			'dateFrom' => 'Дата от',
			'dateTo' => 'Дата до',
			'status' => 'Статус',
        ];
    }

    public static function find()
    {
        return new TicketsListQuery(get_called_class());
    }
}