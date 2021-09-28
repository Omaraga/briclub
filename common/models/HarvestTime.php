<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "harvest_time".
 *
 * @property int $id
 * @property int $time
 * @property int $status
 */
class HarvestTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'harvest_time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time', 'status'], 'required'],
            [['time', 'status'], 'integer'],
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
        ];
    }
}
