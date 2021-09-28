<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserPlans */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-plans-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value'=>$user_id])->label(false) ?>

    <?= $form->field($model, 'start')->textInput() ?>

    <?= $form->field($model, 'global')->textInput() ?>

    <?= $form->field($model, 'personal')->textInput() ?>

    <?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
