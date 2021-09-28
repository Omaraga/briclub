<?php
namespace frontend\models;

use common\models\Courses;
use common\models\Referals;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $parent;
    public $email;
    public $username;
    public $fn;
    public $ln;
    public $phone;
    public $country_id;
    public $password;
    public $password_repeat;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id'], 'integer'],
            [['email','phone','username'], 'trim'],
            [['email','username','phone','country_id'], 'required'],
            ['email', 'email'],
            [['phone','fn','ln'], 'required'],
            [['email','parent','phone','fn','ln'], 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Данная почта уже зарегистрирована в системе.<br> Вам необходимо авторизоваться.'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Данный логин уже используется.'],
            ['username', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            [['password', 'password_repeat'], 'required'],
            [['password', 'password_repeat'], 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password','message' => 'Пароли не совпадают'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        try{
            $message = \Yii::$app->mailer->compose();
            $username = $this->username;
            $email = $this->email;
            $message
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($email)
                ->setSubject('Регистрация Shanyrakplus.com')
                ->setTextBody("Здравствуйте, $username .Поздравляем! Вы успешно зарегистрировались!")
                ->setHtmlBody("Здравствуйте, $username .Поздравляем! Вы успешно зарегистрировались!")
                ->send();

        }
        catch (\Exception $e){
            Yii::$app->session->setFlash('error', 'Укажите правильный адрес почты.');
            return false;
        }
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->fio = $this->fn." ".$this->ln;
        $user->firstname = $this->fn;
        $user->lastname = $this->ln;
        $user->phone = $this->phone;
        $user->country_id = $this->country_id;

        if(!empty($this->parent)){
            $parent = User::find()->where(['username'=>$this->parent])->one();
            if(!empty($parent)){
                $user->parent_id = $parent['id'];
            }
        }
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if($user->save()){
            Referals::setParents($user['id']);
            //Courses::setAccess($user['id'],29);
            return $user;
        }else{
            return null;
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('users', 'EMAIL'),
            'fn' => Yii::t('users', 'Имя'),
            'ln' => Yii::t('users', 'Фамилия'),
            'parent' => Yii::t('users', 'Спонсор'),
            'username' => Yii::t('users', 'Логин'),
            'phone' => Yii::t('users', 'PHONE'),
            'country_id' => Yii::t('users', 'Страна'),
            'password' => Yii::t('users', 'PASSWORD'),
            'password_repeat' => Yii::t('users', 'PASSWORD_REPEAT'),
        ];
    }
}
