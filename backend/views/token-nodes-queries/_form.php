<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TokenNodesQueries */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="token-nodes-queries-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList([1 => "Принята", 2 => "Отклонена", 3 => "Подана"]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
