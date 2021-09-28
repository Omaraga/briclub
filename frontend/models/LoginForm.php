<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email','password'], 'trim'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверное имя пользователя или пароль.');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('users', 'EMAIL_OR_USERNAME'),
            'password' => Yii::t('users', 'PASSWORD'),
            'rememberMe' => Yii::t('users', 'REMEMBER_ME'),
        ];
    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {

            $user = User::find()->where(['email'=>$this->email])->one();
            if(!$user){
                $user = User::find()->where(['username'=>$this->email])->one();
            }
            if($user){
                $status = $user['status'];
                if($status == 1){
                    $text1 = "Email не активирован!<br>";
                    $text2 = "Не пришло письмо?";
                    $text3 = "<a href='/retryConfirmEmail' class='btn btn-link'>отправить письмо повторно</a>";
                    $text = $text1.$text2.$text3;
                    Yii::$app->getSession()->setFlash('error', $text);
                }
                if($status == 3){
                    $text1 = "Логин заблокирован! Обратитесь в тех. поддержку.<br>";
                    Yii::$app->getSession()->setFlash('error', $text1);
                }
            }

            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
