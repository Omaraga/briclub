<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tlgrm_steps".
 *
 * @property int $id
 * @property int $step
 * @property int $created_at
 * @property int $updated_at
 * @property string $step_text
 */
class TelegramSteps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tlgrm_steps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['step', 'created_at', 'step_text'], 'required'],
            [['step', 'created_at', 'updated_at'], 'integer'],
            [['step_text'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'step' => 'Step',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'step_text' => 'Step Text',
        ];
    }
}
