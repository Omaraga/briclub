<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Audios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="audios-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lib_id')->hiddenInput(['value' => $lib_id])->label(false) ?>

    <?= $form->field($model, 'file[]')->fileInput(['class'=>'btn btn-primary', 'multiple' => true, 'accept' => 'docs/audios/*']); ?>

    <?= $form->field($model, 'status')->dropDownList([1 => "Опубликовано", 2 => "Скрыто"]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
