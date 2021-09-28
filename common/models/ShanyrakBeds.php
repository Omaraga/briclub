<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shanyrak_beds".
 *
 * @property int $id
 * @property int $program_id
 * @property int $user_id
 * @property int $time
 * @property int $status
 * @property string $doc1
 * @property string $doc2
 * @property string $doc3
 * @property string $lastname
 * @property string $name
 * @property string $secondname
 * @property int $iin
 * @property int $doc_num
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property int $rooms
 * @property int $term1
 * @property int $term2
 * @property string $car_name
 * @property string $tech_name
 * @property string $sum_all
 * @property string $sum_first
 * @property string $sum_month_1
 * @property string $sum_month_2
 */
class ShanyrakBeds extends \yii\db\ActiveRecord
{
    public $file;
    public $file2;
    public $file3;

    public $sum_kv_all_1;
    public $sum_kv_all_2;
    public $sum_kv_all_3;

    public $sum_auto_all_1;
    public $sum_auto_all_2;
    public $sum_auto_all_3;

    public $sum_tech_all_1;
    public $sum_tech_all_2;
    public $sum_tech_all_3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shanyrak_beds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['program_id', 'user_id', 'time', 'status', 'iin', 'doc_num', 'rooms', 'term1', 'term2'], 'integer'],
            [[ 'name', 'iin', 'doc_num', 'address', 'phone', 'email', 'term1', 'term2', 'sum_all'], 'required'],
            [['address'], 'string'],
            [['sum_all', 'sum_first', 'sum_month_1', 'sum_month_2'], 'number'],
            [['sum_kv_all_1'], 'number', 'min'=>10000, 'max' => 22000],
            [['sum_kv_all_2'], 'number', 'min'=>22001, 'max' => 29000],
            [['sum_kv_all_3'], 'number', 'min'=>29001, 'max' => 36000],
            [['file','file2','file3'], 'file', 'extensions' => ['png', 'jpg', 'pdf' , 'jpeg'], 'maxSize' => 1024 * 1024 * 5],
            [['doc1', 'doc2', 'doc3', 'lastname', 'name', 'secondname', 'phone', 'email', 'car_name', 'tech_name','parent'], 'string', 'max' => 510],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'program_id' => 'Program ID',
            'user_id' => 'User ID',
            'time' => 'Дата',
            'status' => 'Статус',
            'doc1' => 'Удостоверение личности',
            'doc2' => 'Договор',
            'doc3' => 'Подписанный договор',
            'lastname' => 'Фамилия',
            'name' => 'ФИО',
            'parent' => 'Пригласитель',
            'secondname' => 'Отчество',
            'iin' => 'ИИН',
            'doc_num' => 'Номер удостоверения',
            'address' => 'Адрес',
            'phone' => 'Телефон',
            'email' => 'Email',
            'rooms' => 'Количество комнат',
            'term1' => 'Срок накопления',
            'term2' => 'Рассрочка',
            'car_name' => 'Наименование авто',
            'tech_name' => 'Наименование товара',
            'sum_all' => 'Общая стоимость',
            'sum_first' => 'Первый взнос',
            'file' => 'Документ удостоверяющий личность',
            'file2' => 'Договор',
            'file3' => 'Договор',
            'sum_month_1' => 'Ежемесячный взнос накоплений',
            'sum_month_2' => 'Ежемесячный взнос рассрочки',
            'sum_kv_all_1' => 'Стоимость квартиры',
            'sum_kv_all_2' => 'Стоимость квартиры',
            'sum_kv_all_3' => 'Стоимость квартиры',
            'sum_auto_all_1' => 'Стоимость авто',
            'sum_auto_all_2' => 'Стоимость авто',
            'sum_auto_all_3' => 'Стоимость авто',
            'sum_tech_all_1' => 'Стоимость техники',
            'sum_tech_all_2' => 'Стоимость техники',
            'sum_tech_all_3' => 'Стоимость техники',
        ];
    }
}
