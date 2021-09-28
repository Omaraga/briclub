<?php

namespace common\models;

use common\models\User;
use Yii;


/**
 * This is the model class for table "verifications".
 *
 * @property int $id
 * @property int $user_id
 * @property string $doc1
 * @property string $doc2
 * @property string $doc3
 * @property string $doc4
 * @property int $time
 * @property int $status
 * @property int $stage
 * @property int $document_type_id
 * @property int $country_id
 * @property string $comment
 * @property int $doc1_status
 * @property int $doc2_status
 * @property int $doc3_status
 * @property int $doc4_status
 * @property string $city
 * @property string $post_index
 * @property string $address
 */
class Verifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const STAGE_SEND_EMAIL = 0; //отправка email
    const STAGE_CHECK_EMAIL = 1; //проверка email
    const STAGE_READY_TO_VALID_USER_DATA = 2; // email валидирован и подготовка к валидации других данных
    const STAGE_USER_DATA_WAIT_VALID = 3; // данные отправлены на проверку к админку
    const STAGE_MODIFY_USER_DATA = 4; // в основных данных есть ошибка стадия модификации
    const STAGE_READY_TO_VALID_ADDRESS = 5; // основные данные валидированы подготовка к валидации адресных данных
    const STAGE_ADDRESS_WAIT_VALID  = 6; //адресные данные отправлены на валидацию
    const STAGE_ADDRESS_MODIFY = 7; //адресные данные требуют модификацию
    const STAGE_ALL_VALIDATED = 8; //все данные валидированы


    public static function tableName()
    {
        return 'verifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'time', 'status', 'doc1_status', 'doc2_status', 'doc3_status', 'doc4_status','stage'], 'integer'],
            [['doc1', 'doc2', 'doc3', 'doc4', 'comment','address'], 'string', 'max' => 510],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
            'time' => 'Дата',
            'status' => 'Статус',
            'doc1' => 'Паспорт/Удостоверение',
            'doc2' => 'Паспорт/Удостоверение (обратная сторона)',
            'doc3' => 'Фото с паспортом на руках',
            'doc4' => 'Документ подтверждающий место проживания',
            'comment' => 'Комментарии',
            'doc1_status' => 'Паспорт/Удостоверение',
            'doc2_status' => 'Паспорт/Удостоверение (обратная сторона)',
            'doc3_status' => 'Фото с паспортом на руках',
            'doc4_status' => 'Документ подтверждающий место проживания',
            'address' => 'Адрес проживания',
        ];
    }
    public function changeStage(){
        $user = User::findOne($this->user_id);
        $verified = 1;
        if ($this->stage == self::STAGE_USER_DATA_WAIT_VALID){
            if ($this->doc1_status == 1 && $this->doc2_status == 1 && $this->doc3_status == 1){
                $this->stage = self::STAGE_READY_TO_VALID_ADDRESS;
            }else{
                $this->stage = self::STAGE_MODIFY_USER_DATA;
                $verified = 2;
            }
        }else if ($this->stage == self::STAGE_ADDRESS_WAIT_VALID){
            if ($this->doc4_status == 1){
                $this->stage = self::STAGE_ALL_VALIDATED;
                $user = User::findOne($this->user_id);
                $user->verification = 1;
                $user->save(false);
            }else{
                $this->stage = self::STAGE_ADDRESS_MODIFY;
                $verified = 2;
            }
        }
        $user->verification = $verified;
        $user->save(false);
        return $this->save();

    }

    public function getStageName(){
        switch ($this->stage){
            case self::STAGE_SEND_EMAIL:
                return "Верификация почты";
            case self::STAGE_CHECK_EMAIL:
                return "Код верификации почты отравлен";
            case self::STAGE_READY_TO_VALID_USER_DATA:
                return "Почта верифицирована";
            case self::STAGE_USER_DATA_WAIT_VALID:
                return "Проверка администратором основных данных";
            case self::STAGE_MODIFY_USER_DATA:
                return "Отказ верификации основных данных";
            case self::STAGE_READY_TO_VALID_ADDRESS:
                return "Основные данные верифицированы";
            case self::STAGE_ADDRESS_WAIT_VALID:
                return "Проверка администратором адресных данных";
            case self::STAGE_ADDRESS_MODIFY:
                return "Отказ верификации адресных данных";
            case self::STAGE_ALL_VALIDATED:
                return "<span style='font-weight: bold;'>Все этапы верификации пройдены.</span>";
        }
}

}
