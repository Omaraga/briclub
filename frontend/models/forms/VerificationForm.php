<?php
namespace frontend\models\forms;

use app\modules\users\models;
use yii\base\Model;
use Yii;

class VerificationForm extends Model
{
    public $file1;
    public $file2;
    public $file3;
    public $file4;
    public $file1Name;
    public $file2Name;
    public $file3Name;
    public $file4Name;
    public $address;
    public $country_id;
    public $documentType;
    public $postIndex;
    public $city;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file1', 'file2','file3'], 'safe'],
            [['file1','file2', 'file3'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'pdf'], 'maxSize' => 1024 * 1024 * 10],
            [['address'], 'string', 'max' => 510],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file1' => 'Паспорт/Удостоверение',
            'file2' => 'Паспорт/Удостоверение (обратная сторона)',
            'file3' => 'Фото с паспортом на руках',
            'file4' => 'Документ подтверждающий место проживания',
            'address' => 'Адрес проживания',
        ];
    }

}
