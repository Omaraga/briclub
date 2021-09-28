<?php


namespace frontend\models\forms;


use Yii;
use yii\base\Model;

class ConvertForm extends Model
{
    public $sum;

    public function rules(){
        return [
            [['sum'], 'number', 'numberPattern' => '/^\d+(.\d{1,8})?$/'],
        ];
    }
}