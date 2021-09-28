<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tlgrm_answers_questions".
 *
 * @property int $id
 * @property int $answer_id
 * @property int $question_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property TlgrmAnswers $answer
 * @property TlgrmQuestions $question
 */
class TelegramAnswersQuestions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tlgrm_answers_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer_id', 'question_id', 'created_at'], 'required'],
            [['answer_id', 'question_id', 'created_at', 'updated_at'], 'integer'],
            [['answer_id'], 'exist', 'skipOnError' => true, 'targetClass' => TlgrmAnswers::className(), 'targetAttribute' => ['answer_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => TlgrmQuestions::className(), 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'answer_id' => 'Answer ID',
            'question_id' => 'Question ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(TlgrmAnswers::className(), ['id' => 'answer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(TlgrmQuestions::className(), ['id' => 'question_id']);
    }
}
