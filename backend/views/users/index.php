<?php

use common\models\User;
use kartik\export\ExportMenu;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = $this->title;

$logins = User::find()->all();
$data = array();
$data2 = array();
$data3 = array();
$data4 = array();


foreach ($logins as $item) {
    $data[] = $item['username'];
    $data2[] = $item['email'];
    $data3[] = $item['fio'];
    $data4[] = $item['phone'];
}


$countries = User::find()->select('country_id')->distinct()->all();
$columns = [
    ['class' => 'yii\grid\SerialColumn'],

    'email',
    [
        'attribute'=>'username',
        'content'=>function($data){
            return Html::a($data['username'],'/users/view?id='.$data['id']);
        },
        'format' => 'raw'
    ],
    [
        'attribute'=>'parent_id',
        'content'=>function($data){
            $parent = \common\models\User::findOne($data['parent_id'] );
            return Html::a($parent['username'],'/users/view?id='.$parent['id']);
        },
        'format' => 'raw'
    ],
    'fio',
    'phone',
    [
        'attribute'=>'country_id',
        'content'=>function($data){
            $country = \common\models\Countries::findOne($data['country_id'] );
            return $country['title'];
        }
    ],
    [
        'attribute'=>'Матрица',
        'content'=>function($data){
            $res = "";

            if($data['newmatrix'] == 1){
                $level = \common\models\MatrixRef::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                $res = $res." Shanyrak+";
            }else{
                $res = "Не активирован";
            }

            return $res;
        }
    ],
    'w_balans',

    [
        'attribute'=>'created_at',
        'content'=>function($data){
            return date("d.m.Y H:i",$data['created_at']);
        }
    ],
    [
        'attribute'=>'time_personal',
        'content'=>function($data){
            if(!empty($data['time_personal'])){
                return date("d.m.Y H:i",$data['time_personal']);
            }
        }
    ],
];
echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns
]);
?>
<div>
    <a href="users/balance-t">Статистика баланса<? Html::a('Подробнее ', ['users/balance-t'], ['class' => 'profile-link']);?></a>
</div>
<div class="site-index">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-3">
                    <form id="w0" action="/users" method="get">

                        <div class="form-group field-activities-username">
                            <label class="control-label" for="activities-username">Логин</label>
                            <?
                            echo TypeaheadBasic::widget([
                                'name' => 'username',
                                'data' =>  $data,
                                'options' => ['placeholder' => 'Введите логин ...','id'=>'username','class'=>'form-control','autocomplete'=>'off'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);
                            ?>
                            <!--<input type="text" id="username" <?/*if(!empty($username)){*/?>value="<?/*=$username*/?>" <?/*}*/?> class="form-control" name="username">-->

                            <div class="help-block"><?=$error?></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Поиск</button>    </div>

                    </form>
                </div>
                <div class="col-3">
                    <form id="w0" action="/users" method="get">

                        <div class="form-group field-activities-username">
                            <label class="control-label" for="activities-username">Email</label>
                            <!--<input type="hidden" name="username">-->
                            <?
                            echo TypeaheadBasic::widget([
                                'name' => 'email',
                                'data' =>  $data2,
                                'options' => ['placeholder' => 'Введите email ...','id'=>'email','class'=>'form-control','autocomplete'=>'off'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);
                            ?>
                            <!--<input type="text" id="username" <?/*if(!empty($username)){*/?>value="<?/*=$username*/?>" <?/*}*/?> class="form-control" name="username">-->

                            <div class="help-block"><?=$error?></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Поиск</button>    </div>

                    </form>
                </div>
                <div class="col-3">
                    <form id="w0" action="/users" method="get">

                        <div class="form-group field-activities-username">
                            <label class="control-label" for="activities-username">ФИО</label>
                            <?
                            echo TypeaheadBasic::widget([
                                'name' => 'fio',
                                'data' =>  $data3,
                                'options' => ['placeholder' => 'Введите ФИО ...','id'=>'fio','class'=>'form-control','autocomplete'=>'off'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);
                            ?>
                            <!--<input type="text" id="username" <?/*if(!empty($username)){*/?>value="<?/*=$username*/?>" <?/*}*/?> class="form-control" name="username">-->

                            <div class="help-block"><?=$error?></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Поиск</button>    </div>

                    </form>
                </div>
                <div class="col-3">
                    <form id="w0" action="/users" method="get">

                        <div class="form-group field-activities-username">
                            <label class="control-label" for="activities-username">Телефон</label>
                            <!--<input type="hidden" name="username">-->
                            <?
                            echo TypeaheadBasic::widget([
                                'name' => 'phone',
                                'data' =>  $data4,
                                'options' => ['placeholder' => 'Введите телефон ...','id'=>'phone','class'=>'form-control','autocomplete'=>'off'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);
                            ?>
                            <!--<input type="text" id="username" <?/*if(!empty($username)){*/?>value="<?/*=$username*/?>" <?/*}*/?> class="form-control" name="username">-->

                            <div class="help-block"><?=$error?></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Поиск</button>    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="site-index">
    <div class="row">
        <div class="col-3">
            <form id="w0" action="/users" method="get">

                <div class="form-group field-activities-username">
                    <label class="control-label" for="activities-username">Площадка</label>
                    <select name="platform" id="platform_id" class="form-control">
                        <option value="">Все уровни</option>
                        <option value="11" <?if($platform == 11){?>selected<?}?>>Не активировано</option>
                        <option value="12" <?if($platform == 12){?>selected<?}?>>Активированные</option>
                        <option value="1" <?if($platform == 1){?>selected<?}?>>Уровень 1</option>
                        <option value="2" <?if($platform == 2){?>selected<?}?>>Уровень 2</option>
                        <option value="3" <?if($platform == 3){?>selected<?}?>>Уровень 3</option>
                        <option value="4" <?if($platform == 4){?>selected<?}?>>Уровень 4</option>
                        <option value="5" <?if($platform == 5){?>selected<?}?>>Уровень 5</option>
                        <option value="6" <?if($platform == 6){?>selected<?}?>>Уровень 6</option>
                    </select>
                    <div class="help-block"><?=$error?></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Поиск</button>    </div>

            </form>
        </div>
        <div class="col-3">
            <form id="w0" action="/users" method="get">

                <div class="form-group field-activities-username">
                    <label class="control-label" for="activities-username">Страна</label>
                    <select name="countr" id="countr" class="form-control">
                        <option value="">Все страны</option>
                        <? foreach ($countries as $country) {
                            if($country['country_id'] == null) continue;
                            $user_count = User::find()->where(['country_id'=>$country['country_id']])->count();
                            ?>
                            <option value="<?=$country['country_id']?>" <?if($countr == $country['country_id']){?>selected<?}?>><?=\common\models\Countries::findOne($country['country_id'])['title']?> (<?=$user_count?>)</option>
                        <?}?>
                    </select>
                    <div class="help-block"><?=$error?></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Поиск</button>    </div>

            </form>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                <form id="w0" action="/users" method="get">
                    <div class="row">
                    <div class="col-6">
                        <div class="form-group field-activities-username">
                            <label class="control-label" for="activities-username">Время регистрации от</label>
                            <input class="form-control" type="date" name="from" value="<?=$from?>">
                            <div class="help-block"><?=$error?></div>
                        </div>
                    </div>
                        <div class="col-6">
                            <div class="form-group field-activities-username">
                                <label class="control-label" for="activities-username">До</label>
                                <input class="form-control" type="date" name="to" value="<?=$to?>">
                                <div class="help-block"><?=$error?></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Поиск</button>    </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="user-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]); ?>
</div>
