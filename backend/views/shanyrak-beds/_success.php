<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ShanyrakBeds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shanyrak-beds-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'file2')->fileInput(['class'=>'form-control-file btn btn-link pl-0']);  ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
