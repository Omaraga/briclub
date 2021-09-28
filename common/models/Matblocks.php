<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "matblocks".
 *
 * @property int $id
 * @property int $user_id
 * @property int $mat_id
 */
class Matblocks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'matblocks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'mat_id'], 'integer'],
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
        ];
    }
}
