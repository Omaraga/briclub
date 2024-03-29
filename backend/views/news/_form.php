<?php

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$main_url = Yii::$app->params['mainUrl'];
/* @var $this yii\web\View */
/* @var $model common\models\Actions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="actions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <? if(!empty($model->link)){
        echo "Текущее изображение";
        echo "<br>";
        echo "<img src='$main_url/$model->link' width='150px'>";
        }
    echo $form->field($model, 'file')->fileInput(['class'=>'btn btn-primary']);


    echo $form->field($model, 'text')->widget(CKEditor::className(),[
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',[/* Some CKEditor Options */]),
    ]);
    ?>

    <?= $form->field($model, 'status')->dropDownList([1=>'Опубликовано',3=>'Не опубликовано']) ?>
    <?= $form->field($model, 'slider')->checkbox(); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
