<?php

use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$logins = \common\models\User::find()->all();
$data = array();
foreach ($logins as $item) {
    $data[] = $item['username'];
}
/* @var $this yii\web\View */
/* @var $model common\models\Exceptions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exceptions-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="form-group">
        <?
        echo $form->field($model, 'username')->widget(TypeaheadBasic::classname(), [
            'data' =>  $data,
            'options' => ['placeholder' => 'Введите логин ...','id'=>'username','class'=>'form-control','autocomplete'=>'off'],
            'pluginOptions' => ['highlight'=>true],
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
