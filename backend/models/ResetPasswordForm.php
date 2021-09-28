<?php
namespace backend\models;

use common\models\UserPasswordResetToken;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;
use common\models\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $email;
    public $password;
    public $id;

    /**
     * @var \budyaga\users\models\UserPasswordResetToken
     */
    private $_token;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'email'],
            [['id'], 'integer'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('users', 'Email'),
            'password' => Yii::t('users', 'NEW_PASSWORD'),
            'password_repeat' => Yii::t('users', 'NEW_PASSWORD_REPEAT'),
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $this->_token->user->setPassword($this->password);

        return ($this->_token->user->save() && $this->_token->delete());
    }
}
