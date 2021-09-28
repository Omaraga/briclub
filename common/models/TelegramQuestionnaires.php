<?php

namespace common\models;

use Yii;
use common\models\TelegramUsers;

/**
 * This is the model class for table "tlgrm_questionnaires".
 *
 * @property int $id
 * @property int $user_id
 * @property string $full_name
 * @property string $city
 * @property string $programm_source
 *
 * @property TelegramUsers $user
 */
class TelegramQuestionnaires extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tlgrm_questionnaires';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['full_name', 'programm_source'], 'string'],
            [['city'], 'string', 'max' => 255]
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
            'full_name' => 'Full Name',
            'city' => 'City',
            'programm_source' => 'Programm Source',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(TlgrmUsers::className(), ['id' => 'user_id']);
    }
}
