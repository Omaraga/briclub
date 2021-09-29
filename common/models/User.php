<?php
namespace common\models;
use common\models\Access;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $lastname
 * @property string $firstname
 * @property string $fio
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $phone
 * @property integer $activ
 * @property integer $is_agree_contract
 * @property string $auth_key
 * @property integer $status
 * @property integer $country_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property float $w_balans read-only
 * @property int $is_agent
 * @property int $parent_id
 * @property int $agent_status
 * @property int $time_personal
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_NEW = 9;
    const STATUS_ACTIVE = 10;
    const STATUS_BLOCKED = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($email)
    {
        $user = static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
        if(empty($user)){
            $user = static::findOne(['username' => $email, 'status' => self::STATUS_ACTIVE]);
        }
        return $user;
    }


    public static function getChildren($user_id)
    {
        $result = array();
        $children = User::find()->where(['parent_id'=>$user_id])->andWhere(['>','platform_id',0])->all();
        if(!empty($children)){
            foreach ($children as $child){
                $parent = new Parents();
                $parent->user_id = $child['id'];
                $parent->parent_id = $user_id;
                $parent->level = 1;
                $parent->save();

                $result[] = $child;
                $result = array_merge($result,User::getChildren($child['id']));
            }
        }
        return $result;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        return $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function createEmailConfirmToken($needConfirmOldEmail = false)
    {
        $token = new UserEmailConfirmToken;
        $token->user_id = $this->id;
        $token->new_email = $this->email;
        $token->new_email_token = Yii::$app->security->generateRandomString();
        $token->new_email_confirm = 0;

        if ($needConfirmOldEmail) {
            $token->old_email_token = Yii::$app->security->generateRandomString();
            $token->old_email_confirm = 0;
            $token->old_email = $this->oldAttributes['email'];
        }

        return $token->save();
    }
    public function getEmailConfirmToken()
    {
        return $this->hasOne(\common\models\UserEmailConfirmToken::className(), ['user_id' => 'id']);
    }

    public function sendEmailConfirmationMail($view, $toAttribute)
    {
        return Yii::$app->mailer->compose(['html' => $view . '-html'], ['user' => $this, 'token' => $this->emailConfirmToken])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
            ->setTo($this->emailConfirmToken->{$toAttribute})
            ->setSubject('Активация Email-a  ' . \Yii::$app->name)
            ->send();
    }

   /* public static function createUser($email,$fio=null,$phone=null){
        $user = new User();
        $user->username = $email;
        $user->email = $email;
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }*/
    public static function createUser($order,$login,$parent,$fio,$email,$phone,$fn,$ln,$sn){
        $user = new User();
        $user->username = $login;
        $user->email = $email;
        $user->order = $order;
        $user->firstname = $fn;
        $user->lastname = $ln;
        $user->secondname = $sn;
        $user->fio = $fio;
        $user->phone = $phone;
        $parent = User::find()->where(['username'=>$parent])->one();
        if(!empty($parent)){
            $user->parent_id = $parent['id'];
        }

        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'Имя',
            'email' => 'Email',
            'phone' => 'Телефон',
            'confirm' => 'Подтверждение почты',
            'created_at' => 'Дата регистрации',
			'username' => 'Логин',
            'parent_id' => 'Спонсор',
            'country_id' => 'Страна',
            'w_balans' => 'Баланс',
            'time_personal' => 'Время активации',
        ];
    }

    public static function isAccess($role){
        if(!Yii::$app->user->isGuest){
            $access = array();
            $all_access = Access::find()->where([$role=>1])->all();
            foreach ($all_access as $one_access) {
                $access[] = $one_access['username'];
            }
            if(in_array(Yii::$app->user->identity['username'], $access)){
                return true;
            }
        }
        return false;
    }

    public static function romeNum($num){
        if($num == 1){
            return "I";
        }elseif($num == 2){
            return "II";
        }elseif($num == 3){
            return "III";
        }elseif($num == 4){
            return "IV";
        }elseif($num == 5){
            return "V";
        }elseif($num == 6){
            return "VI";
        }elseif($num == 7){
            return "VII";
        }
    }
    public static function plusBalans($user_id,$sum,$binar=null,$block=false){
        $user = User::findOne($user_id);
        if($block == true){
            $user->b_balans = $user->b_balans + $sum;
        }else{
            $user->p_balans = $user->p_balans + $sum;
        }



        if($user['overdraft'] >0){
            if($user['overbinar'] == null){
                if($binar == 1){
                    $action_bon = new Actions();
                    if($user['overdraft'] >= $sum/4){
                        $user->w_balans = $user->w_balans - $sum/4;
                        $user->overdraft = $user['overdraft'] - $sum/4;
                        $action_bon->sum = $sum/4;
                    }else{
                        $user->w_balans = $user->w_balans - $user['overdraft'];
                        $user->overdraft = null;
                        $action_bon->sum = $user['overdraft'];
                    }

                    $action_bon->time = time();
                    $action_bon->status = 1;

                    $action_bon->user_id = $user['id'];
                    $action_bon->title = "Списание части заработка за счет образовавшегося овердрафта. Остаток долга: ".$user->overdraft;
                    $action_bon->type = 96;
                    $action_bon->save();
                }
            }else{
                $action_bon = new Actions();
                if($user['overdraft'] >= $sum/4){
                    $user->w_balans = $user->w_balans - $sum/4;
                    $user->overdraft = $user['overdraft'] - $sum/4;
                    $action_bon->sum = $sum/4;
                }else{
                    $user->w_balans = $user->w_balans - $user['overdraft'];
                    $user->overdraft = null;
                    $action_bon->sum = $user['overdraft'];
                }

                $action_bon->time = time();
                $action_bon->status = 1;

                $action_bon->user_id = $user['id'];
                $action_bon->title = "Списание части заработка за счет образовавшегося овердрафта. Остаток долга: ".$user->overdraft;
                $action_bon->type = 96;
                $action_bon->save();
            }

        }
        $user->save();

    }

    public static function plusBri($user_id, $sum){
        $briTokens = BriTokens::find()->where(['user_id' => $user_id])->one();
        if ($briTokens){
            $briTokens->balans += $sum;
        }else{
            $briTokens = new BriTokens();
            $briTokens->user_id = $user_id;
            $briTokens->balans = $sum;
        }
        $briTokens->save();
    }

    public static function plusGrc($user_id, $sum){
        $grcTokens = Tokens::find()->where(['user_id' => $user_id])->one();
        if ($grcTokens){
            $grcTokens->balans += $sum;
        }else{
            $grcTokens = new Tokens();
            $grcTokens->user_id = $user_id;
            $grcTokens->balans = $sum;
        }
        $grcTokens->save();
    }
}
