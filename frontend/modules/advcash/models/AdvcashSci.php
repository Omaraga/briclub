<?php
namespace frontend\modules\advcash\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use frontend\modules\advcash\models\AdvcashDeposit;
/**
 * Модель для вывода средств
 * Из методов, подключение, подпись, проверка подписи
 * @property integer $id
 * @property integer $user_id
 * @property double $sum
 * @property integer $created_at
 * @property integer $updated_at
 */
class AdvcashSci extends Model
{
    const TRANSACTION_STATUS_PENDING = 'PENDING';
    const TRANSACTION_STATUS_PROCESS = 'PROCESS';
    const TRANSACTION_STATUS_CONFIRMED = 'CONFIRMED';
    const TRANSACTION_STATUS_COMPLETED = 'COMPLETED';
    const TRANSACTION_STATUS_CANCELLED = 'CANCELLED';
    // AdvCash internal currencies
    const CURRENCY_USD = 'USD';
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_RUR = 'RUR';
    const CURRENCY_GBP = 'GBP';
    // AdvCash internal payment systems
    const PAYMENT_SYSTEM_ADVANCED_CASH = 'ADVANCED_CASH';
    const PAYMENT_SYSTEM_ALFACLICK = 'ALFACLICK';
    const PAYMENT_SYSTEM_BITCOIN = 'BITCOIN';
    const PAYMENT_SYSTEM_BTC_E = 'BTC_E';
    const PAYMENT_SYSTEM_ECOIN = 'ECOIN';
    const PAYMENT_SYSTEM_OKPAY = 'OKPAY';
    const PAYMENT_SYSTEM_PAXUM = 'PAXUM';
    const PAYMENT_SYSTEM_PAYEER = 'PAYEER';
    const PAYMENT_SYSTEM_PERFECT_MONEY = 'PERFECT_MONEY';
    const PAYMENT_SYSTEM_PRIVAT24 = 'PRIVAT24';
    const PAYMENT_SYSTEM_PSB_RETAIL = 'PSB_RETAIL';
    const PAYMENT_SYSTEM_QIWI = 'QIWI';
    const PAYMENT_SYSTEM_RUSSIAN_STANDARD_BANK = 'RUSSIAN_STANDARD_BANK';
    const PAYMENT_SYSTEM_SBER_ONLINE = 'SBER_ONLINE';
    const PAYMENT_SYSTEM_SVYAZNOY_BANK = 'SVYAZNOY_BANK';
    const PAYMENT_SYSTEM_YANDEX_MONEY = 'YANDEX_MONEY';
    
    public $ac_account_email = 'globicommfd@gmail.com';
    
    public $walletNumber = '';
    
    public $ac_sci_name = 'Gcfond';
    
    public $merchantPassword = '_9s6kf2fSp';
    
    public $ac_currency = self::CURRENCY_USD;
    
    public $sciCheckSign = true;

    public $ac_amount;
    public $ac_order_id;
    public $ac_sign;
    public $ac_comments;
    public $ac_ps = 'ADVANCED_CASH';
    public $type_id;
    public $formAction = 'https://wallet.advcash.com/sci/';
    /** @var null Default suggested payment system */
    public $sciDefaultPs = null;
    public $ac_success_url = 'https://gcfond.com/advcash/advcash-sci/success';
    public $ac_success_url_method = 'POST';
    public $ac_fail_url = 'https://gcfond.com/advcash/advcash-sci/failure';
    public $ac_fail_url_method = 'POST';
    public $ac_status_url = 'https://gcfond.com/advcash/advcash-sci/status';
    public $ac_status_url_method = 'POST';

    public function attributeLabels()
    {
        return [
            'ac_amount' => 'Количество Uc',
        ];
    }

    public function rules()
    {
        return [
            [['ac_amount'], 'required'],
            [['ac_currency', 'ac_sci_name', 'ac_account_email', 'ac_order_id', 'ac_sign', 'ac_comments', 'ac_ps'], 'safe'],
            ['ac_amount', 'number']
        ];
    }

    /**
     * Формирует все данные, собирает из них подпись
     */
    public function getPaymentData()
    {
        if (!isset($this->ac_amount) || empty($this->ac_amount)) return false;

        //$this->ac_success_url = Url::toRoute(Yii::$app->params['main_url'].'/advcash/advcash-sci/success', true);
        //$this->ac_fail_url = Url::toRoute('/advcash/advcash-sci/failure', true);
        //$this->ac_status_url = Url::toRoute('/advcash/advcash-sci/status', true);

        $this->ac_comments = 'USD Deposit';

        $deposit = new AdvcashDeposit();
        $deposit->sum = $this->ac_amount;
        $deposit->user_id = Yii::$app->user->identity->id;
        if (!$deposit->save()) return false;
        $this->ac_order_id = $deposit->id;
        
        $this->ac_sign = $this->createSciSign();
	
        return true;
    }

    public function createSciSign()
    {
        return hash('sha256', implode(':', [
            $this->ac_account_email,
            $this->ac_sci_name,
            number_format($this->ac_amount, 2, '.', ''),
            $this->ac_currency,
            $this->merchantPassword,
            $this->ac_order_id,
        ]));
    }

    public function checkHash($data)
    {
        if (!isset(
            $data['ac_transfer'],
            $data['ac_start_date'],
            $data['ac_sci_name'],
            $data['ac_src_wallet'],
            $data['ac_dest_wallet'],
            $data['ac_order_id'],
            $data['ac_amount'],
            $data['ac_merchant_currency']
        )
        ) {
            return false;
        }
        $params = [
            $data['ac_transfer'],
            $data['ac_start_date'],
            $data['ac_sci_name'],
            $data['ac_src_wallet'],
            $data['ac_dest_wallet'],
            $data['ac_order_id'],
            $data['ac_amount'],
            $data['ac_merchant_currency'],
            $this->merchantPassword
        ];
        $hash = hash('sha256', implode(':', $params));
        if ($hash == $data['ac_hash']) {
            return true;
        }
        \Yii::error('Hash check failed: ' . VarDumper::dumpAsString($params), static::LOG_CATEGORY);
        return false;
    }


}
