<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_harvests".
 *
 * @property int $id
 * @property int $time
 * @property int $status
 * @property string $all_sum
 */
class TokenHarvests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_harvests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time', 'status'], 'integer'],
            [['all_sum'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'status' => 'Status',
            'all_sum' => 'All Sum',
        ];
    }
}
