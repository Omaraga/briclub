<?php


namespace backend\models;
use common\models\MatrixRef;
use common\models\User;
use yii\base\Model;

class AgentForm extends Model
{
    CONST ACTIVE = 1;
    CONST BLOCK = 0;
    public $username;
    public $status;
    public function rules()
    {
        return [
            [['username', 'status'], 'required'],
            [['status'], 'integer'],
            [['username'], 'string'],
            ['username', 'validateUsername'],

        ];
    }


    public function validateUsername($attribute, $params){
        $user = $this->findUser();
        if ($user){
            $matrixRef = MatrixRef::find()->where(['user_id' => $user->id])->one();
            if ($matrixRef){
                $this->addError($attribute,  'У пользователя есть матрица, он не может быть представителем.');
            }

        }else{
            $this->addError($attribute,  'Пользователь не существует');
        }
    }

    /**
     * @return bool
     */
    public function save(){
        if ($this->validate()){
            $user = $this->findUser();
            $user->is_agent = 1;
            $user->agent_status = $this->status;
            return $user->save(false);
        }

        return false;


    }

    public function findUser(){
        return User::find()->where(['username' => $this->username])->one();
    }


}