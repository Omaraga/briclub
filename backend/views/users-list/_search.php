<?php

use common\models\User;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\typeahead\Typeahead;
$countries = User::find()->select('country_id')->distinct()->all();
$logins = User::find()->all();
$data = array();
$data2 = array();
$data3 = array();
$data4 = array();


foreach ($logins as $item) {
    $data[] = $item['username'];
    $data5[] = $item['username'];
    $data2[] = $item['email'];
    $data3[] = $item['fio'];
    $data4[] = $item['phone'];
}
$countries_map[''] = 'Все страны';
foreach ($countries as $country) {
    if(empty($country['country_id'])) continue;
    $countries_map[$country['country_id']] = \common\models\Countries::findOne($country['country_id'])['title'];
}
/* @var $this yii\web\View */
/* @var $model common\models\UsersSearch */
/* @var $form yii\widgets\ActiveForm */
/*echo "<pre>";
var_dump($countries_map);
exit;*/
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
                                'local' => $data
                            ]
                        ]
                    ]); ?>

                </div>
                <div class="col-xs-3">
                    <?= $form->field($model, 'email')->widget(Typeahead::classname(), [
                        'options' => ['placeholder' => 'Введите email ...','id'=>'email','class'=>'form-control','autocomplete'=>'off'],
                        'pluginOptions' => ['highlight'=>true],
                        'dataset' => [
                            [
                                'local' => $data2
                            ]
                        ]
                    ]); ?>
                </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'fio')->widget(Typeahead::classname(), [
                    'options' => ['placeholder' => 'Введите ФИО ...','id'=>'fio','class'=>'form-control','autocomplete'=>'off'],
                    'pluginOptions' => ['highlight'=>true],
                    'dataset' => [
                        [
                            'local' => $data3
                        ]
                    ]
                ]); ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'phone')->widget(Typeahead::classname(), [
                    'options' => ['placeholder' => 'Введите телефон ...','id'=>'phone','class'=>'form-control','autocomplete'=>'off'],
                    'pluginOptions' => ['highlight'=>true],
                    'dataset' => [
                        [
                            'local' => $data4
                        ]
                    ]
                ]); ?>
            </div>
            <div class="col-xs-3">
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
            <div class="col-xs-3">
                <?= $form->field($model, 'regDateFrom')->widget(\yii\jui\DatePicker::className(), [
                    // if you are using bootstrap, the following line will set the correct style of the input field
                    'options' => ['class' => 'form-control','autocomplete'=>'off'],
                    'dateFormat' => 'dd.MM.yyyy',
                    // ... you can configure more DatePicker properties here
                ])->label('Дата активации От') ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'regDateTo')->widget(\yii\jui\DatePicker::className(), [
                    // if you are using bootstrap, the following line will set the correct style of the input field
                    'options' => ['class' => 'form-control','autocomplete'=>'off'],
                    'dateFormat' => 'dd.MM.yyyy',
                    // ... you can configure more DatePicker properties here
                ])->label('Дата активации По') ?>
            </div>
            <div class="col-xs-3">
                <?php echo $form->field($model, 'country_id')->dropDownList($countries_map) ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'structure')->widget(Typeahead::classname(), [
                    'options' => ['placeholder' => 'Логин спонсора ...','id'=>'structure','class'=>'form-control','autocomplete'=>'off'],
                    'pluginOptions' => ['highlight'=>true],
                    'dataset' => [
                        [
                            'local' => $data5
                        ]
                    ]
                ]); ?>
            </div>

                <?php // echo $form->field($model, 'updated_at') ?>

                <?php // echo $form->field($model, 'order') ?>

                <?php // echo $form->field($model, 'parent_id') ?>

                <?php // echo $form->field($model, 'platform_id') ?>

                <?php // echo $form->field($model, 'balans') ?>

                <?php // echo $form->field($model, 'w_balans') ?>

                <?php // echo $form->field($model, 'firstname') ?>

                <?php // echo $form->field($model, 'lastname') ?>

                <?php // echo $form->field($model, 'secondname') ?>

                <?php // echo $form->field($model, 'country_id') ?>

                <?php // echo $form->field($model, 'level') ?>

                <?php // echo $form->field($model, 'last_ip') ?>

                <?php // echo $form->field($model, 'newmatrix') ?>

                <?php // echo $form->field($model, 'minus_balans') ?>

                <?php // echo $form->field($model, 'activ') ?>

                <?php // echo $form->field($model, 'global') ?>

                <?php // echo $form->field($model, 'start') ?>

                <?php // echo $form->field($model, 'vacant') ?>

                <?php // echo $form->field($model, 'time_start') ?>

                <?php // echo $form->field($model, 'time_personal') ?>

                <?php // echo $form->field($model, 'time_global') ?>

                <?php // echo $form->field($model, 'block') ?>

                <?php // echo $form->field($model, 'overdraft') ?>

                <?php // echo $form->field($model, 'overbinar') ?>

                <?php // echo $form->field($model, 'verification') ?>

                <?php // echo $form->field($model, 'canplatform') ?>

                <?php // echo $form->field($model, 'b_balans') ?>

                <?php // echo $form->field($model, 'limit') ?>

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
