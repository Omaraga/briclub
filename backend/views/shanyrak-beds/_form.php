<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ShanyrakBeds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shanyrak-beds-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iin')->textInput() ?>

    <?= $form->field($model, 'doc_num')->textInput() ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rooms')->textInput() ?>

    <?= $form->field($model, 'term1')->textInput() ?>

    <?= $form->field($model, 'term2')->textInput() ?>

    <?= $form->field($model, 'sum_all')->textInput() ?>

    <?= $form->field($model, 'sum_first')->textInput() ?>

    <?= $form->field($model, 'sum_month_1')->textInput() ?>

    <?= $form->field($model, 'sum_month_2')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
