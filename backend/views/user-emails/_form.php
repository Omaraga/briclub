<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserEmails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-emails-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'old_email')->textInput() ?>

    <?= $form->field($model, 'new_email')->textInput() ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
