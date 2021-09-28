<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Courses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="courses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'old_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'duration')->textInput() ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'certificate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([1=>'Онлайн курс',2=>'MLM предприниматель',3=>'Инвест программа',4=>'Крипто']); ?>

    <?= $form->field($model, 'is_active')->dropDownList([1=>'Активный',2=>'Отключен']); ?>

    <?= $form->field($model, 'soon')->dropDownList([1=>'Да',2=>'Нет']); ?>

<!--    --><?//= $form->field($model, 'start')->checkbox(); ?>
<!--    --><?//= $form->field($model, 'personal')->checkbox(); ?>
<!--    --><?//= $form->field($model, 'global')->checkbox(); ?>
    <?= $form->field($model, 'preview')->fileInput()->label('Превью курса'); ?>
    <?= $form->field($model, 'icon')->fileInput()->label('Иконка курса'); ?>


    <?= $form->field($model, 'icon_color')->dropDownList(['yellow' => 'Желтый', 'violet'=> 'Фиолетовый', 'light-green' => 'Зеленый', 'blue' => 'Синий', 'turquoise' => 'Салатовый'])->label('Цвет иконки'); ?>

    <?
    if(!empty($events)){
        echo $form->field($model, 'updated_at')->dropDownList($events);
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
