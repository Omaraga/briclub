<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Changes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="changes-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'cur2')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
