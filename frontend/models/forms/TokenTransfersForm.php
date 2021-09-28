<?php


namespace frontend\models\forms;


use Yii;
use yii\base\Model;

class TokenTransfersForm extends Model
{
    public $username;
    public $sum;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'username', 'sum'], 'filter', 'filter' => 'trim'],
            [['username', 'sum'], 'required'],
            [['sum'], 'number','numberPattern' => '/^\d+(.\d{1,8})?$/'], //регулярка
            [['username'], 'string', 'min' => 2],
            ['username', 'exist',
                'targetClass' => '\common\models\User',
                'message' => 'Такого пользователя не существует!'
            ],
            ['username', 'checkUsername'],
            ['sum', 'checkBalans'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['some_scenario'] = ['username'];
        $scenarios['some_scenario3'] = ['sum'];

        return $scenarios;
    }

    public function checkUsername($attribute, $params)
    {
        if($this->username == Yii::$app->user->identity['username']){
            $this->addError($attribute,  'Нельзя отправлять токены на свой аккаунт!');
        }

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
            'username' => "Логин получателя",
            'sum' => "Количество токенов",
        ];
    }


}