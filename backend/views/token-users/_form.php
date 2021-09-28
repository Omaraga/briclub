<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TokenUsers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="token-users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true, 'required' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
