<?php

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$main_url = Yii::$app->params['mainUrl'];
/* @var $this yii\web\View */
/* @var $model common\models\Documents */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="documents-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList([1=>'Документ',2=>'Промо материал']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <? if(!empty($model->link)){
        echo "Текущее изображение";
        echo "<br>";
        echo "<img src='$main_url/$model->link' width='150px'>";
        }
    echo $form->field($model, 'file')->fileInput(['class'=>'btn btn-primary']);

     echo $form->field($model, 'status')->dropDownList([1=>'Опубликовано',2=>'Скрыто']);
     echo $form->field($model, 'slider')->checkbox(); ?>

    <?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'link2')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'description')->widget(CKEditor::className(),[
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',[/* Some CKEditor Options */]),
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
