<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "beds".
 *
 * @property int $id
 * @property string $title
 * @property string $email
 * @property string $tel
 * @property int $course_id
 * @property int $pay
 * @property string $text
 * @property int $type
 */
class Beds4 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'beds';
    }
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        $message = \Yii::$app->mailer->compose();
        $course = Courses::findOne($this->course_id)['title'];
        $message
            ->setFrom('no-reply@skconsult.kz')
            ->setTo('bakhti.info@mail.ru')
            ->setSubject('Новая заявка')
            ->setTextBody("Здравствуйте, $this->title оставил заявку на курс $course . Телефон: $this->tel")
            ->setHtmlBody("Здравствуйте, $this->title оставил заявку на курс $course . Телефон: $this->tel")
            ->send();
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'pay', 'type'], 'integer'],
            [['text'], 'string'],
            [['title', 'email', 'tel'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Имя',
            'email' => 'Email',
            'tel' => 'Телефон',
            'course_id' => 'Курс',
            'pay' => 'Способ оплаты',
            'text' => 'Текст',
            'type' => 'Тип',
        ];
    }
}
