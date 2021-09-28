<?php
namespace backend\models;

use app\models\UserInfo;
use app\modules\users\models\User;
use yii\base\Model;
use Yii;

class TransfersForm extends Model
{
    public $username;
    public $comment;
    public $sum;
    public $target;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'username', 'sum' ,'comment'], 'filter', 'filter' => 'trim'],
            [['target'], 'integer'],
            [['username', 'sum'], 'required'],
            [['sum'], 'number','numberPattern' => '/^\d+(.\d{1,8})?$/'],
            [['username'], 'string', 'min' => 2],
            ['username', 'exist',
                'targetClass' => '\common\models\User',
                'message' => 'Такого пользователя не существует!'
            ],
            ['username', 'checkUsername'],
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
            $this->addError($attribute,  'Нельзя отправлять US на свой аккаунт!');
        }

    }

    public function attributeLabels()
    {
        return [
            'username' => "Логин получателя",
            'sum' => "Сумма",
            'comment' => "Комментарий",
        ];
    }


}
