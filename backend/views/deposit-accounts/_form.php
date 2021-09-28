<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DepositAccounts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deposit-accounts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'system')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
