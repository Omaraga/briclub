<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Parts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'course_id')->hiddenInput(['value'=>$c_id])->label(false) ?>

    <?= $form->field($model, 'free')->dropDownList([0=>'Нет',1=>'Да'])?>

    <?= $form->field($model, 'is_intro')->dropDownList([0=>'Нет',1=>'Да'])?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
