<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$main_url = Yii::$app->params['mainUrl'];
/* @var $this yii\web\View */
/* @var $model common\models\Documents */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="documents-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <? if(!empty($model->link2)){
        echo "Текущее изображение";
        echo "<br>";
        echo "<img src='$main_url$model->link2' width='150px'>";
        }
    echo $form->field($model, 'file2')->fileInput(['class'=>'btn btn-primary']);
    if(!empty($model->link)){
        echo "Текущая книга";
        echo "<br>";
        echo "<a href='$main_url$model->link'>".$model->title."</a>";
    }
    echo $form->field($model, 'file')->fileInput(['class'=>'btn btn-primary']);

     echo $form->field($model, 'status')->dropDownList([1=>'Опубликовано',2=>'Скрыто']) ?>

    <?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
