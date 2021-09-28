<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tlgrm_answers".
 *
 * @property int $id
 * @property string $text
 * @property int $created_at
 * @property int $updated_at
 *
 * @property TlgrmAnswersQuestions[] $tlgrmAnswersQuestions
 */
class TelegramAnswers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tlgrm_answers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'created_at'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['text'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTlgrmAnswersQuestions()
    {
        return $this->hasMany(TlgrmAnswersQuestions::className(), ['answer_id' => 'id']);
    }
}
