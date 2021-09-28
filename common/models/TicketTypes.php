<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ticket_types".
 *
 * @property int $id
 * @property string $title
 * @property string $fee_token
 */
class TicketTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fee_token'], 'number'],
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
            'fee_token' => 'Fee Token',
        ];
    }
}
