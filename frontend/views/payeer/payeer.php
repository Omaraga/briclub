<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Пополнение баланса через Payeer";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$account1 = \common\models\DepositAccounts::findOne(1)['account'];
$account2 = \common\models\DepositAccounts::findOne(2)['account'];

?>
    <main class="transaction">
        <div class="container">
            <div class="hgroup">
                <h1 class="h1">Подтвердить платеж</h1>
                <div class="hline"></div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <form method="post" action="https://payeer.com/merchant/">
                        <p>Сумма платежа $<?=$m_amount?></p>
                        <p>Платежная система Payeer</p>
                        <input type="hidden" name="m_shop" value="<?=$m_shop?>">
                        <input type="hidden" name="m_orderid" value="<?=$m_orderid?>">
                        <input type="hidden" name="m_amount" value="<?=$m_amount?>">
                        <input type="hidden" name="m_curr" value="<?=$m_curr?>">
                        <input type="hidden" name="m_desc" value="<?=$m_desc?>">
                        <input type="hidden" name="m_sign" value="<?=$sign?>">
                        <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit"  name="m_process" value="Подтвердить">
                    </form>
                </div>
            </div>

        </div>
    </main>
