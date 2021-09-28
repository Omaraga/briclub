<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Withdraws */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="withdraws-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'sum')->textInput(['max'=>$model->sum]) ?>
    <?= $form->field($model, 'status')->dropDownList([1=>'Начислено',4=>'Начислено вручную',2=>'Отменено',3=>'В обработке']) ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
