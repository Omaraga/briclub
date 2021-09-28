<?php
namespace frontend\models;

use common\models\UserPasswordResetToken;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm2 extends Model
{
    public $token;
    public $course_id;
    private $_user = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['token', 'trim'],
            ['token', 'required'],
            ['token', 'email'],
            ['token', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
}
