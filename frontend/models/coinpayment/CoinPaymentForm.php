<?php


namespace frontend\models\coinpayment;
use common\models\CoinPaymentsAPI;
use common\models\Coinpayments;
use yii\base\Model;
use Yii;
class CoinPaymentForm extends Model
{
    public $email;
    public $amount;
    public $currency;
    public function rules()
    {
        return [
            [['email', 'amount'], 'required'],
            [['email'], 'email'],
            ['currency', 'in', 'range' => ['BTC', 'USDT.ERC20', 'ETH','LTCT']],
        ];
    }
    public function attributeLabels()
    {
        return [
            'email' => "Email",
            'amount' => "Сумма",
            'currency' => "Валюта",
        ];
    }

    public function sendTransaction(){
        $coinPayment = new CoinPaymentsAPI();
        $coinPayment->Setup(Yii::$app->params['coin.private_key'],Yii::$app->params['coin.public_key']);

        $amount = $this->amount;
        $email = $this->email;
        $scurrency = "USD";
        $rcurrency = $this->currency;
        $request = [
            'amount' => $amount,
            'currency1' => $scurrency,
            'currency2' => $rcurrency,
            'buyer_email' => $email,
            'item' => "Buy Shanyrakplus+",
            'address' => "",
            'ipn_url' => "https://test.shanyrakplus.com/coinpayment"
        ];

        $result = $coinPayment->CreateTransaction($request);

        if ($result['error'] == "ok") {
            $payment = new Coinpayments();
            $payment->user_id = Yii::$app->user->identity['id'];
            $payment->entered_amount = $amount;
            $payment->amount = $result['result']['amount'];
            $payment->from_currency = $scurrency;
            $payment->to_currency = $rcurrency;
            $payment->status = "initialized";
            $payment->gateway_id = $result['result']['txn_id'];
            $payment->gateway_url = $result['result']['status_url'];
            $payment->save();
        } else {
            print 'Error: ' . $result['error'] . "\n";
            die();
        }
        return $result;
    }


}