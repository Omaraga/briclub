<?php

use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Bills */
/* @var $form yii\widgets\ActiveForm */
/* @var $users common\models\User */

$data = array();

foreach ($users as $el){
    $data[] = $el->username;
}
$sender_id = isset($_GET['sender_id']) ? $_GET['sender_id'] : null;
$sum = isset($_GET['sum']) ? $_GET['sum'] : null;

if($sender_id){
    $sender_id = \common\models\User::findOne(['id' => $sender_id])['username'];
}
$comment = isset($_GET['comment']) ? $_GET['comment'] : null;
?>

<div class="bills-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sum')->textInput(['maxlength' => true, 'value' => $sum]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6, 'value' => $comment]) ?>

<!--    --><?//= $form->field($model, 'receiver_id')->textInput() ?>


    <br>

    <p><b>Отправитель</b></p>
    <? echo TypeaheadBasic::widget([
                                'name' => 'sender',
                                'data' =>  $data,
                                'value' => $sender_id,
                                'options' => ['placeholder' => 'Введите логин ...','id'=>'sender','class'=>'form-control','autocomplete'=>'off'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);?>
    <br>
    <?= $form->field($model, 'type')->dropDownList([2 => 'Администрация Shanyrakplus', 1 => 'Greenswop', 3 => 'Besroit']) ?>

    <div class="form-group">
        <?= Html::submitButton('Выставить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
