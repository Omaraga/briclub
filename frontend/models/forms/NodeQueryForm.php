<?php


namespace frontend\models\forms;


use Yii;
use yii\base\Model;

class NodeQueryForm extends Model
{
    public $sum;
    public $username;

    public function rules()
    {
        return [
            [['sum'], 'number','numberPattern' => '/^\d+(.\d{1,8})?$/'], //регулярка
            ['sum', 'checkBalans']
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['some_scenario3'] = ['sum'];

        return $scenarios;
    }
    public function checkBalans($attribute, $params)
    {
        $user = Yii::$app->user->identity;
        $balans = \common\models\Tokens::findOne(['user_id' => $user['id']])['balans'];
        if($balans < $this->sum){
            $this->addError($attribute,  'Недостаточно средств!');
        }

    }
    public function attributeLabels()
    {
        return [
            'sum' => "Количество токенов",
        ];
    }
}