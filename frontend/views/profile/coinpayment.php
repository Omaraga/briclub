<?php
/* @var $coinPaymentForm frontend\models\coinpayment\CoinPaymentForm */
/* @var $result  array */
?>
<main style="margin-top: 4rem">
<div id="app" class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3" style="margin:auto; background: white; padding: 20px; box-shadow: 10px 10px 5px #888;">
            <div class="panel-heading">
                <h1>Подтверждение оплаты </h1>
<!--                <p style="font-style: italic;">to <strong>--><?php //echo $username; ?><!--</strong></p>-->
            </div>
            <hr>
            <form>
                <label for="amount">Сумма (<?php echo $coinPaymentForm->currency; ?>)</label>
                <h1><?php echo $coinPaymentForm->amount.' CV = '; ?><?php echo $result['result']['amount'] ?> <?php echo $coinPaymentForm->currency ?></h1>
                <hr>
                <a href="<?php echo $result['result']['status_url'] ?>" class="btn btn-success btn-block">Оплатить</a>
            </form>
        </div>
    </div>
</div>
</main>
