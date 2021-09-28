<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "canplatforms".
 *
 * @property int $id
 * @property int $user_id
 * @property int $mat_id
 * @property int $platform
 */
class Canplatforms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'canplatforms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'mat_id', 'platform'], 'integer'],
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
            'platform' => 'Platform',
        ];
    }
}
