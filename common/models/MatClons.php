<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mat_clons".
 *
 * @property int $id
 * @property int $user_id
 * @property int $mat_id
 * @property int $num
 */
class MatClons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mat_clons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'mat_id', 'num'], 'integer'],
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
            'mat_id' => 'Mat ID',
            'num' => 'Num',
        ];
    }
}
