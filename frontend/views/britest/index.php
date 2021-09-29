<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\forms\BriTestForm */
use yii\helpers\Html;
?>



<main>
    <?=Html::a('Посмотреть текущий', '/britest/view', ['class' => 'btn btn-danger']);?>
    <?$form = \yii\widgets\ActiveForm::begin()?>
        <?=$form->field($model, 'complete')->label('Закрыли 3 стол');?>
        <?=$form->field($model, 'branch')->label('Сколько веток');?>

        <input type="submit" value="Запуск" class="btn btn-primary">

    <?\yii\widgets\ActiveForm::end();?>

</main>
