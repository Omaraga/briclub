<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Actions */
/* @var $form yii\widgets\ActiveForm */
$session = Yii::$app->session;
$error = $session['error-balans'];
$session->remove('error-balans');
?>

<div class="actions-form">
    <p style="color: red;">
        <?php
        if(!empty($error)){
            echo $error;
        } ?>
    </p>
    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <?= $form->field($model, 'comment')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Пополнить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
