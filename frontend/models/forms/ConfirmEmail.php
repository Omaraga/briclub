<?php


namespace frontend\models\forms;
use yii\base\Model;
use Yii;

class ConfirmEmail extends Model
{
    const SCENARIO_SEND_CODE = 'send_email';
    const SCENARIO_CHECK_CODE = 'check_code';
    public $code;
    public $userCode;
    public $typeReq;

    public function __construct($config = [])
    {
        $this->code = strval(Yii::$app->session->get('emailCode'));

    }

    public function scenarios()
    {
        return [
            self::SCENARIO_SEND_CODE => ['typeReq'],
            self::SCENARIO_CHECK_CODE => ['code','userCode','typeReq'],
        ];
    }

    public function rules()
    {
        return [
            ['typeReq', 'required'],
            [['userCode', 'code'], 'string'],
            ['userCode', 'required', 'on'=>self::SCENARIO_CHECK_CODE, 'message'=>'Введите код верификации'],
            ['userCode', 'compare', 'compareAttribute' => 'code','message' => 'Не верный код верификации', 'on'=>self::SCENARIO_CHECK_CODE],
        ];
    }
    public function sendValidateCodeToEmail($user){
        $this->code = strval(rand(100000, 999999));
        Yii::$app->session->set('emailCode', $this->code);
        return Yii::$app->mailer->compose(['html' => '@frontend/mail/confirmEmailWithCode.php'], ['user' => $user, 'code' => $this->code])
            ->setFrom([\Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user['email'])
            ->setSubject('Верификация почты: ' . Yii::$app->name)
            ->send();
    }

}