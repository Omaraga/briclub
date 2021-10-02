<?php

use common\models\Events;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
$main_url = Yii::$app->params['mainUrl'];
/* @var $this yii\web\View */
/* @var $model Events */
/* @var $form yii\widgets\ActiveForm */
$spikersList = \common\models\Spikers::find()->all();
$spikersList = ArrayHelper::map($spikersList, 'id', 'fio');
$selSpikers = \common\models\EventsAndSpikers::find()->where(['event_id' => $model->id])->all();
$rankList = ArrayHelper::map(\common\models\UserRank::find()->all(), 'id', 'title');
$typeList = ArrayHelper::map(\common\models\EventType::find()->all(),'id', 'title');

$script = <<<JS
 $('#addSpiker').click(function (e){
     e.preventDefault();
     var sel = $('#events-spikerlist').val();
     
     
 });
JS;
$this->registerJs($script);

?>

<div class="actions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <div class="row">
        <div class="col-xs-4"><?= $form->field($model, 'type_id')->dropDownList($typeList)->label('Тип') ?></div>
    </div>
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
    <div class="row">
        <div class="col-xs-4"><?= $form->field($model, 'startTimeStr')->widget(DateTimePicker::className(),['autoDefaultTimezone' => true,'options' => ['autocomplete'=>'off']]) ?></div>
    </div>
    <div class="row">
        <div class="col-xs-4"><?= $form->field($model, 'status')->dropDownList([1=>'Опубликовано',3=>'Не опубликовано']) ?></div>
    </div>
    <div class="row">
        <div class="col-xs-4 col-md-6"><?= $form->field($model, 'spiker_id')->dropDownList($spikersList)->label('Добавить спикера') ?></div>
    </div>
    <div class="row">
        <div class="col-xs-4"><?= $form->field($model, 'roleList')->checkboxList($rankList)->label('Кому доступно');?></div>
    </div>
    <div class="row">
        <?foreach ($selSpikers as $spiker):?>
            <div class="col-xs-4">
                <?=$spiker->fio;?>
            </div>
        <?endforeach;?>
    </div>



    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
