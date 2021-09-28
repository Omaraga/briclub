<?php

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Lessons */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="lessons-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vimeo_link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'youtube_link')->textInput(['maxlength' => true]) ?>

    <?
    echo $form->field($model, 'description')->widget(CKEditor::className(),[
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',[/* Some CKEditor Options */]),
    ]);
    ?>
    <?
    echo $form->field($model, 'homework')->widget(CKEditor::className(),[
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',[/* Some CKEditor Options */]),
    ]);
    ?>


    <?= $form->field($model, 'course_id')->hiddenInput(['value'=>$c_id])->label(false) ?>

    <?= $form->field($model, 'part_id')->hiddenInput(['value'=>$part_id])->label(false)?>

    <?= $form->field($model, 'free')->dropDownList([0=>'Нет',1=>'Да'])?>

    <?= $form->field($model, 'image')->fileInput()->label('Превью картинка урока'); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
