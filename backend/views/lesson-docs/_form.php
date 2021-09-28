<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LessonDocs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lesson-docs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput(['class'=>'']) ?>

    <?= $form->field($model, 'lesson_id')->hiddenInput(['value'=>$lesson_id])->label(false) ?>

    <?= $form->field($model, 'type')->dropDownList([1=>'Дополнительный материал',2=>'Домашнее задание',3=>'Ссылка']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
