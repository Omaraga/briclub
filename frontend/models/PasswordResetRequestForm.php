<?php
namespace frontend\models;

use common\models\UserPasswordResetToken;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    private $_user = false;
    public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        $user = $this->user;
        if (!$user) {
            return false;
        }

        return \Yii::$app->mailer->compose(['html' => '@frontend/mail/passwordResetToken-html'], ['user' => $user, 'token' => $this->token])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Изменение пароля для: ' . \Yii::$app->name)
            ->send();
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
            ]);
        }

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('users', 'EMAIL'),
        ];
    }

    public function getToken()
    {
        $user = $this->user;

        $token = UserPasswordResetToken::findOne(['user_id' => $user->id]);

        if (empty($token->token)) {
            $token = new UserPasswordResetToken;
            $token->user_id = $user->id;
            $token->token = $user->generatePasswordResetToken();
            $token->save();
        }
        $user->password_reset_token = $token->token;
        $user->save();
        return $token;
    }
}
