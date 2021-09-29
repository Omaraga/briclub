<?php
namespace frontend\models;

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
    public $password;
    public $password_repeat;

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
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_token = UserPasswordResetToken::findOne([
            'token' => $token
        ]);

        if (!$this->_token) {
            throw new InvalidParamException('Ссылка устарела, попробуйте запросить ссылку для восстановления пароля еще раз!');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'required'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function attributeLabels()
    {
        return [
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