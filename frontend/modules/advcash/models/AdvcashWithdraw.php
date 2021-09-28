<?php
namespace frontend\modules\advcash\models;

use Yii;
use app\models\Withdraw;
use yii\behaviors\TimestampBehavior;
use app\models\User;
use app\modules\advcash\models\AdvcashApi;

/**
 * Модель для таблички выводов. Изменен для адвакеша
 * @property $payment_mail - почтовый ящик юзера в адвакеше
 * @property $status - описаны в моделе Withdraw
 * @property $response - записывает полученные данные от адвакеша
 */
class AdvcashWithdraw extends Withdraw
{
    const ACTIVITY_TYPE = 45;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_PENDING],
            ['user_id', 'default', 'value' => Yii::$app->user->getId()],
            [['user_id', 'sum', 'status', 'payment_mail'], 'required'],
            [['response', 'payment_mail'], 'string'],
            [['user_id'], 'integer'],
            [['sum'], 'number', 'numberPattern' => '/^\d+(\.\d{1,8})?$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'sum' => 'Сумма',
            'payment_mail' => "Электронная почта в Advcash",
            'status' => 'Статус',
            'response' => 'Ответ системы',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }

    /**
     * Перед сохранением, записываем тип вывода
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            /*$type = AdvcashWithdraw::getTypeId();
            if ($type != false) $this->withdraw_type = $type;
            else return false;*/
     
            return true;
        }
        return false;
    }

    public static function getAll()
    {
        return static::find();
    }

    public static function getById($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function getTypeId()
    {
        $id = '';
        return $id;
    }

    public function getUsername()
    {
        $user = User::findOne(['id' => $this->user_id]);
        if ($user != null) return $user->username;
        else return '';
    }

    /**
     * Проверяет есть ли деньги на долларовом счету
     */
    public function checkBalans()
    {
        $balans = Yii::$app->user->identity->info->advcash;
        $user_id = Yii::$app->user->id;
        $user_block = \app\models\Limits::find()->where(['user_id'=>$user_id])->one();
        if(!empty($user_block) and $user_block['limit']>0){
            $balans = $user_block['limit'];
        }
        
        if (($balans - $this->sum) >= 0) {
            return true;
        }
        return false;
    }

    /**
     * Проверяем если ты такой аккаунт в системе адвакеша
     */
    public function checkAccount()
    {
        $helper = new AdvcashApi();
	    $helper->getAccountData();
        
        if ($helper->registerClient() != false) {
            $data = $helper->validateAccounts(array($this->payment_mail));
            if ($data != false && $data['present']) return true;
        }
        return false;
    }
}
