<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\forms\BriTestForm */
?>


<main style="margin-left: 10%">
    <?$form = \yii\widgets\ActiveForm::begin()?>
        <?=$form->field($model, 'complete')->label('Закрыли 3 стол');?>
        <?=$form->field($model, 'branch')->label('Сколько веток');?>
        <input type="submit" value="Запуск" class="btn btn-primary">
    <?\yii\widgets\ActiveForm::end();?>

</main>
