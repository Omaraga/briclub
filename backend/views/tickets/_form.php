<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Tickets */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tickets-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList([1=>'Закрыт',2=>'Отвечен',3=>'В обработке']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
