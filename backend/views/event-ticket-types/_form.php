<?php

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$main_url = Yii::$app->params['mainUrl'];
/* @var $this yii\web\View */
/* @var $model common\models\EventTicketTypes */
/* @var $form yii\widgets\ActiveForm */

$event_id = Yii::$app->request->get('event_id');
?>

<div class="event-ticket-types-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_id')->hiddenInput(['value'=>$event_id])->label(false) ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <? if(!empty($model->link)){
        echo "Текущее изображение";
        echo "<br>";
        echo "<img src='$main_url/$model->link' width='150px'>";
    }
    echo $form->field($model, 'file')->fileInput(['class'=>'btn btn-primary']);

    ?>

    <?= $form->field($model, 'status')->dropDownList([1=>'Опубликовано',3=>'Не опубликовано']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
