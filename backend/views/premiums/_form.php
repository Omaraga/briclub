<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Premiums */
/* @var $form yii\widgets\ActiveForm */

$taiffs = \common\models\PremiumTariffs::find()->all();

?>

<div class="premiums-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tariff_id')
        ->dropDownList([
                1   =>   'Неделя',
                2   =>   'Месяц',
                3   =>   '3 месяца',
                4   =>   'Полгода',
                5   =>   'Год',
                6   =>   'Навсегда',
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
