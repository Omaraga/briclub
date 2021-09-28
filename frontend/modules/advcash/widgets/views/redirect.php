<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>
<form action="<?=$model->formAction?>" method="post">
    <div class="form-group">
        <label>Сумма</label>
        <input class="form-control" type="text" name="ac_amount"  readonly value="<?=number_format($model->ac_amount, 2, '.', '')?>"/>
    </div>
    <input type="hidden" name="ac_account_email" value="<?=$model->ac_account_email?>"/>
    <input type="hidden" name="ac_sci_name" value="<?=$model->ac_sci_name?>"/>
    <input type="hidden" name="ac_currency" value="<?=$model->ac_currency?>"/>
    <input type="hidden" name="ac_order_id" value="<?=$model->ac_order_id?>"/>
    <input type="hidden" name="ac_sign" value="<?=$model->ac_sign?>"/>
    <input type="hidden" name="ac_comments" value="<?=$model->ac_comments?>"/>
    <input type="hidden" name="ac_success_url" value="<?=$model->ac_success_url?>"/>
    <input type="hidden" name="ac_success_url_method" value="<?=$model->ac_success_url_method?>"/>
    <input type="hidden" name="ac_fail_url" value="<?=$model->ac_fail_url?>"/>
    <input type="hidden" name="ac_fail_url_method" value="<?=$model->ac_fail_url_method?>"/>
    <input type="hidden" name="ac_status_url" value="<?=$model->ac_status_url?>"/>
    <input type="hidden" name="ac_status_url_method" value="<?=$model->ac_status_url_method?>"/>

    <?= Html::submitButton('Перейти на страницу платежа', ['class' => 'btn btn-primary']) ?>
    
</form>