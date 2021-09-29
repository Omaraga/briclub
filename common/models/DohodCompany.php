<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dohod_company".
 *
 * @property int $id
 * @property string $sum
 * @property int $user_id
 */
class DohodCompany extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dohod_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sum'], 'number'],
            [['user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sum' => 'Sum',
            'user_id' => 'User ID',
        ];
    }
}
