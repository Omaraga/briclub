<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Verifications */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="verifications-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList([1=>'В обработке',2=>'Отменено',3=>'Верифицирован']) ?>

    <?= $form->field($model, 'comment')->textInput() ?>

    <p>Оставить поля:</p>
    <?= $form->field($model, 'doc1_status')->checkbox() ?>
    <?= $form->field($model, 'doc2_status')->checkbox() ?>
    <?= $form->field($model, 'doc3_status')->checkbox() ?>
    <?= $form->field($model, 'doc4_status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
