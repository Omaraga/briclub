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
class Beds extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */


    /*public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        $message = \Yii::$app->mailer->compose();
        $course = Courses::findOne($this->course_id)['title'];
        $message
            ->setFrom('noreply@oneroom.kz')
            ->setTo('bakhti.info@mail.ru')
            ->setSubject('Новая заявка')
            ->setTextBody("Здравствуйте, $this->title оставил заявку на курс $course . Телефон: $this->tel, Email: $this->email")
            ->setHtmlBody("Здравствуйте, $this->title оставил заявку на курс $course . Телефон: $this->tel, Email: $this->email")
            ->send();
        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'SUCCESS BED'));
    }*/

    public static function tableName()
    {
        return 'beds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'email', 'tel'], 'string', 'max' => 255],
            [['title', 'email', 'tel'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCourses()
    {
        return $this->hasOne(Courses::className(), ['id' => 'course_id']);
    }
    public function getPays()
    {
        return $this->hasOne(PayTypes::className(), ['id' => 'pay']);
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Имя',
            'email' => 'Email',
            'tel' => 'Телефон',
            'course_id' => 'Курс',
            'pay' => 'Оплачено',
            'text' => 'Текст',
            'access' => 'Доступ',
            'type' => 'Тип',
            'status' => 'Статус',
            'created_at' => 'Дата заявки',
        ];
    }

}
