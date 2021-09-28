<?php
namespace frontend\models\forms;

use yii\base\Model;
use Yii;

class GetTokenForm extends Model
{
    public $sum;
    public $tokens;
    public $user_id;
    public $parent;

    public function rules()
    {
        return [
            [['sum','tokens','parent'], 'filter', 'filter' => 'trim'],
            [['sum','tokens'], 'required'],
            [['sum','tokens'], 'number','numberPattern' => '/^\d+(.\d{1,8})?$/'],
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
        $balans = \common\models\User::findOne($user['id'])['w_balans'];
        if($balans < $this->sum){
            $this->addError($attribute,  'Недостаточно средств!');
        }
    }
    public function attributeLabels()
    {
        return [
            'tokens' => "Количество токенов",
            'sum' => "Сумма",
            'parent' => "Промокод",
        ];
    }

}
