<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use backend\models\AgentForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $users yii\widgets\ActiveForm */

$status = [AgentForm::ACTIVE=> 'Активен', AgentForm::BLOCK => 'Блокирован'];
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?=$form->field($model, 'username')->widget(TypeaheadBasic::classname(), [
        'data' => $users,
        'options' => ['placeholder' => 'Логин', 'autocomplete'=>'off'],
        'pluginOptions' => ['highlight'=>true],
    ])->label('Логин');?>
    <?= $form->field($model, 'status')->dropDownList($status) ?>
    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
