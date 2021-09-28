<?php
namespace frontend\models\forms;

use app\modules\users\models;
use yii\base\Model;
use Yii;

class ChangeDataForm extends Model
{
    public $fio;
    public $firstname;
    public $lastname;
    public $phone;
    public $country;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fio', 'phone', 'lastname', 'firstname'], 'filter', 'filter' => 'trim'],
            [['country'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fio' => Yii::t('users', 'ФИО'),
            'phone' => Yii::t('users', 'Телефон'),
            'country' => Yii::t('users', 'Страна'),
        ];
    }

}
