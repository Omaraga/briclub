<?php

use common\models\Tickets;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\typeahead\Typeahead;

/* @var $this yii\web\View */
/* @var $model common\models\TicketsSearch */
/* @var $form yii\widgets\ActiveForm */

$tickets = Tickets::find()->all();
$titles = array();
$users = array();


foreach ($tickets as $item) {
    $titles[] = $item['title'];
    $users[] = \common\models\User::findOne(['id' => $item['user_id']])->username;
}

?>

<div class="users-list-search">


                <?php $form = ActiveForm::begin([
                    'action' => ['index'],
                    'method' => 'get',
                    'options' => [
                        'data-pjax' => 1
                    ],
                ]); ?>
    <div class="col-12">
        <div class="row">
                <div class="col-xs-3">
                    <?= $form->field($model, 'username')->widget(Typeahead::classname(), [
                        'options' => ['placeholder' => 'Введите логин ...','id'=>'username','class'=>'form-control','autocomplete'=>'off'],
                        'pluginOptions' => ['highlight'=>true],
                        'dataset' => [
                            [
                                'local' => $users
                            ]
                        ]
                    ]); ?>

                </div>
                <div class="col-xs-3">
                    <?= $form->field($model, 'title')->widget(Typeahead::classname(), [
                        'options' => ['placeholder' => 'Введите название ...','id'=>'email','class'=>'form-control','autocomplete'=>'off'],
                        'pluginOptions' => ['highlight'=>true],
                        'dataset' => [
                            [
                                'local' => $titles
                            ]
                        ]
                    ]); ?>
                </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'dateFrom')->widget(\yii\jui\DatePicker::className(), [
                    // if you are using bootstrap, the following line will set the correct style of the input field
                    'options' => ['class' => 'form-control','autocomplete'=>'off', 'placeholder' => 'Дата от'],
                    'dateFormat' => 'dd.MM.yyyy',
                    // ... you can configure more DatePicker properties here
                ]) ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'dateTo')->widget(\yii\jui\DatePicker::className(), [
                    // if you are using bootstrap, the following line will set the correct style of the input field
                    'options' => ['class' => 'form-control','autocomplete'=>'off', 'placeholder' => 'Дата до'],
                    'dateFormat' => 'dd.MM.yyyy',
                    // ... you can configure more DatePicker properties here
                ]) ?>
            </div>

                <div class="col-xs-12 mb-4">
                    <div class="form-group">

                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Сбросить','/users-list',['class'=>'btn btn-default']) ?>
                    </div>
                </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
