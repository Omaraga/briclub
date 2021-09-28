<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Insurances */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurances-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList([1 => "Принято", 2 => "На расммотрении", 3 => "Отклонено"]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
