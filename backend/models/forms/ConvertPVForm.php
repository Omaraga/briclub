<?php


namespace backend\models\forms;


use Yii;
use yii\base\Model;

class ConvertPVForm extends Model
{
    public $sum;
    public $username;

    public function rules(){
        return [
        [['sum'], 'number','numberPattern' => '/^\d+(.\d{1,8})?$/'],
            [['username'], 'string', 'min' => 2],
        ];
    }
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['some_scenario'] = ['username'];
        $scenarios['some_scenario3'] = ['sum'];

        return $scenarios;
    }
}