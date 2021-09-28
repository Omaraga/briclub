<?php

use common\models\User;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\typeahead\Typeahead;

?>
<style>
    .pagination {
        display: inline-block;
        padding-left: 0;
        margin: 20px 0;
        border-radius: 4px;
    }
    .pagination > li {
        display: inline;
    }
    .pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
        z-index: 3;
        color: #fff;
        cursor: default;
        background-color: #337ab7;
        border-color: #337ab7;
    }

    .pagination>li>a {
        background: #fafafa;
        color: #666;
    }
    .pagination > li > a, .pagination > li > span {
        position: relative;
        float: left;
        padding: 6px 12px;
        margin-left: -1px;
        line-height: 1.42857143;
        color: #337ab7;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
    }
</style>
<div class="users-list-search">


                <?php $form = ActiveForm::begin([
                    'action' => ['children'],
                    'method' => 'get',
                    'options' => [
                        'data-pjax' => 1
                    ],
                ]); ?>
    <div class="col-12">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'context')->textInput(['placeholder' => 'Логин, email, ФИО, телефон','id'=>'username','class'=>'form-control','autocomplete'=>'off'])->label('Поиск'); ?>

            </div>
<!--            <div class="col-md-3">-->
<!--                --><?//= $form->field($model, 'username')->textInput(['placeholder' => 'Введите логин ...','id'=>'username','class'=>'form-control','autocomplete'=>'off']); ?>
<!---->
<!--            </div>-->
<!--            <div class="col-md-3">-->
<!--                --><?//= $form->field($model, 'email')->textInput(['placeholder' => 'Введите email ...','id'=>'email','class'=>'form-control','autocomplete'=>'off']); ?>
<!--            </div>-->
<!--            <div class="col-md-3">-->
<!--                --><?//= $form->field($model, 'fio')->textInput(['placeholder' => 'Введите ФИО ...','id'=>'fio','class'=>'form-control','autocomplete'=>'off']); ?>
<!--            </div>-->
<!--            <div class="col-md-3">-->
<!--                --><?//= $form->field($model, 'phone')->textInput(['placeholder' => 'Введите телефон ...','id'=>'phone','class'=>'form-control','autocomplete'=>'off']); ?>
<!--            </div>-->
            <div class="col-md-2 col-6">
                <?php echo $form->field($model, 'platform')->dropDownList([
                        ''=>'Все уровни',
                        11=>'Не активированные',
                        12=>'Активированные',
                        1=>'Площадка 1',
                        2=>'Площадка 2',
                        3=>'Площадка 3',
                        4=>'Площадка 4',
                        5=>'Площадка 5',
                        6=>'Площадка 6',
                ]) ?>
            </div>
            <div class="col-md-2 col-6">
                <?php echo $form->field($model, 'isOwn')->dropDownList([
                    '0'=>'Все',
                    '1'=>'Личники',

                ])->label('Моя структура') ?>
            </div>
            <div class="col-md-2 col-6">
                <?= $form->field($model, 'regDateFrom')->widget(\yii\jui\DatePicker::className(), [
                    // if you are using bootstrap, the following line will set the correct style of the input field
                    'options' => ['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Дата'],
                    'dateFormat' => 'dd.MM.yyyy',
                    // ... you can configure more DatePicker properties here
                ])->label('Регистрация с') ?>
            </div>
            <div class="col-md-2 col-6">
                <?= $form->field($model, 'regDateTo')->widget(\yii\jui\DatePicker::className(), [
                    // if you are using bootstrap, the following line will set the correct style of the input field
                    'options' => ['class' => 'form-control','autocomplete'=>'off', 'placeholder'=>'Дата'],
                    'dateFormat' => 'dd.MM.yyyy',
                    // ... you can configure more DatePicker properties here
                ])->label('Регистрация по') ?>
            </div>



                <div class="col-md-12 mb-4">
                    <div class="form-group">

                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Сбросить','/profile/children',['class'=>'btn btn-default']) ?>
                    </div>
                </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
