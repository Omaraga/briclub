<?php
namespace frontend\models\forms;

use app\models\UserInfo;
use app\modules\users\models\User;
use common\models\lib\Transactions;
use common\models\Tokens;
use yii\base\Model;
use Yii;

class TransfersForm extends Model
{
    public $username;
    public $sum;
    public $fee;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'username', 'sum' ,'fee'], 'filter', 'filter' => 'trim'],
            [['username', 'sum'], 'required'],
            [['sum'], 'number','numberPattern' => '/^\d+(.\d{1,8})?$/'],
            [['username'], 'string', 'min' => 2],
            ['username', 'exist',
                'targetClass' => '\common\models\User',
                'message' => 'Такого пользователя не существует!'
            ],
            ['username', 'checkUsername'],
            ['sum', 'checkBalans'],
            //['fee', 'checkTokens'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['some_scenario'] = ['username'];
        $scenarios['some_scenario3'] = ['sum'];
        //$scenarios['some_scenario4'] = ['fee'];

        return $scenarios;
    }

    public function checkUsername($attribute, $params)
    {
        /* @var $userTo \common\models\User*/
        $userTo = \common\models\User::find()->where(['username'=>$this->username])->one();
        $currUser = Yii::$app->user->identity;
        if ($userTo['is_agent']){
            $this->addError($attribute,  'Невозможно сделать перевод данному пользователю!');
        }
        if($this->username == Yii::$app->user->identity['username']){
            $this->addError($attribute,  'Нельзя отправлять CV на свой аккаунт!');
        }else if ($currUser->username !='Lider' && $currUser->is_agent != 1 && (!isset($userTo->activ) || $userTo->activ != 1)){
            $this->addError($attribute,  'Нельзя отправлять CV не активированному аккаунту!');
        }

    }
    public function checkBalans($attribute, $params)
    {
        $user = Yii::$app->user->identity;
        //$balans = Transactions::getBalans($user['id']);
		$balans = $user['w_balans'];
        if($user['id'] == 12750 or $user['id'] == 12751 or $user['id'] == 12752 or $user['id'] == 12801  or $user['id'] == 13739 or $user['id'] == 12783){
            if($balans < $this->sum){
                $this->addError($attribute,  'Недостаточно средств!');
            }
        }else{
            if($this->sum > 1000){
                $this->addError($attribute,  'Сумма не может быть больше 1000!');
            }elseif($balans < $this->sum){
                $this->addError($attribute,  'Недостаточно средств!');
            }
        }


    }
    public function checkTokens($attribute, $params)
    {
        $user = Yii::$app->user->identity;
        $user_tokens = Tokens::findOne(['user_id'=>$user['id']]);
        if(!empty($user_tokens)){
            $balans = $user_tokens->balans;
            if($balans < $this->fee){
                $this->addError($attribute,  'Недостаточно токенов!');
            }
        }else{
            $this->addError($attribute,  'Недостаточно токенов!');
        }
    }
    public function attributeLabels()
    {
        return [
            'username' => "Логин получателя",
            'sum' => "Сумма",
            'fee' => "Комиссия в токенах",
        ];
    }


}
