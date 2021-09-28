<?php

namespace frontend\controllers;
use common\models\Coinpayments;
use yii\rest\Controller;
class CoinpaymentController extends Controller
{

    public function actionIndex()
    {


        $ipn_secret = "Shanyrakplus";
        //$debug_email = "omaraga.qit@gmail.com";

        $txn_id = trim($_POST['txn_id']);

        /* @var $payment Coinpayments*/
        $payment = Coinpayments::find()->where(["gateway_id"=>$txn_id])->one();
        if ($payment){
            $order_currency = $payment->to_currency; //BTC
            $order_total = $payment->amount; //BTC
            if ($payment->status == 'success'){
                die("IPN OK");
            }
        }else{
            $this->edie("Not find payment");
        }

        if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
            $this->edie("IPN Mode is not HMAC");
        }

        if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
            $this->edie("No HMAC Signature Sent.");
        }

        $request = file_get_contents('php://input');
        if ($request === false || empty($request)) {
            $this->edie("Error in reading Post Data");
        }


        $hmac =  hash_hmac("sha512", $request, trim($ipn_secret));
        if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
            $this->edie("HMAC signature does not match.");
        }

        $amount1 = floatval($_POST['amount1']); //IN USD
        $amount2 = floatval($_POST['amount2']); //IN BTC
        $currency1 = $_POST['currency1']; //USD
        $currency2 = $_POST['currency2']; //BTC
        $status = intval($_POST['status']);

//        if ($currency2 != $order_currency) {
//            $this->edie("Currency Mismatch");
//        }
//
//        if ($amount2 < $order_total) {
//            $this->edie("Amount is lesser than order total");
//        }

        if ($status >= 100 || $status == 2) {
            // Payment is complete
            $payment->status = "success";
            $payment->save();
            $payment->successPayment();
        } else if ($status < 0) {
            // Payment Error
            $payment->status = "error";
            $payment->save();
        } else {
            // Payment Pending
            $payment->status = "pending";
            $payment->save();
        }

        die("IPN OK");
    }
    public function edie($error_msg)
    {
        $report =  "ERROR : " . $error_msg . "\n\n";
        $report.= "POST DATA\n\n";
        foreach ($_POST as $key => $value) {
            $report .= "|$k| = |$v| \n";
        }
        die($error_msg);
    }

}
