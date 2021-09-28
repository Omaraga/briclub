<?php
namespace frontend\models\forms;

use app\models\UserInfo;
use app\modules\users\models\User;
use yii\base\Model;
use Yii;

class ShanpayForm extends Model
{
    public $user_id;
    public $program_id;
    public $pay_id;
    public $sum;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sum'], 'filter', 'filter' => 'trim'],
            [['sum'], 'required'],
            [['sum'], 'number','numberPattern' => '/^\d+(.\d{1,8})?$/'],
            ['sum', 'checkBalans'],
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
            'sum' => "Сумма",
        ];
    }


}
