<?php

use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи Start';
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = $this->title;
$logins = \common\models\User::find()->where(['start'=>1])->all();
$c = 0;
$all_balans = 0;
$all_minus_balans = 0;
foreach ($logins as $user) {
    if($user['w_balans']<0){
        $all_minus_balans = $all_minus_balans - $user['w_balans'];
        $c++;
    }else{
        $all_balans = $all_balans + $user['w_balans'];
    }

}
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

?>
<div class="site-index">
    <div class="row">
        <div class="col-12">
            <h4>Всего на балансах пользователей: $<?=$all_balans?></h4>
            <h4>Всего минус баланс у <?=$c?> пользователей, на сумму: $<?=$all_minus_balans?></h4>
            <div class="row">
                <div class="col-3">
                    <form id="w0" action="/usersstart" method="get">

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
                    <form id="w0" action="/usersstart" method="get">

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
                    <form id="w0" action="/usersstart" method="get">

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
                    <form id="w0" action="/usersstart" method="get">

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
            <form id="w0" action="/usersstart" method="get">

                <div class="form-group field-activities-username">
                    <label class="control-label" for="activities-username">Площадка</label>
                    <select name="platform" id="platform_id" class="form-control">
                        <option value="">Все уровни</option>
                        <option value="11" <?if($platform == 11){?>selected<?}?>>Не активировано</option>
                        <option value="1" <?if($platform == 1){?>selected<?}?>>Уровень 1</option>
                        <option value="2" <?if($platform == 2){?>selected<?}?>>Уровень 2</option>
                        <option value="3" <?if($platform == 3){?>selected<?}?>>Уровень 3</option>
                        <option value="4" <?if($platform == 4){?>selected<?}?>>Уровень 4</option>
                    </select>
                    <div class="help-block"><?=$error?></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Поиск</button>    </div>

            </form>
        </div>
        <div class="col-3">
            <form id="w0" action="/usersstart" method="get">

                <div class="form-group field-activities-username">
                    <label class="control-label" for="activities-username">Баланс</label>
                    <select name="balans" id="balans" class="form-control">
                        <option value="">Все</option>
                        <option value="2" <?if($balans == 2){?>selected<?}?>>Отрицательный</option>
                        <option value="1" <?if($balans == 1){?>selected<?}?>>Положительный</option>
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
                <form id="w0" action="/usersstart" method="get">
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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'email',
            [
                'attribute'=>'username',
                'content'=>function($data){
                    return Html::a($data['username'],'/users/view?id='.$data['id']);
                }
            ],
            [
                'attribute'=>'parent_id',
                'content'=>function($data){
                    $parent = \common\models\User::findOne($data['parent_id'] );
                    return Html::a($parent['username'],'/users/view?id='.$parent['id']);
                }
            ],
            'fio',
            'phone',
            [
                'attribute'=>'activ',
                'content'=>function($data){
                    if($data['activ'] == 1){
                        return "Активирован";
                    }else{
                        return "Не активирован";
                    }

                }
            ],
            [
                'attribute'=>'Матрица',
                'content'=>function($data){
                    $res = "";
                    if($data['start'] == 1){
                        $level = \common\models\MatrixStart::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                        $res = $res." Start (Уровень ".$level['platform_id'].")";
                    }
                    if($data['newmatrix'] == 1){
                        $level = \common\models\MatrixRef::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                        $res = $res." Personal (Уровень ".$level['platform_id'].")";
                    }
                    if($data['global'] == 1){
                        $level = \common\models\UserPlatforms::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                        $res = $res." Global (Уровень ".($level['platform_id']-1).")";
                    }
                    return $res;
                }
            ],
            'w_balans',
            //'order',
            [
                'attribute'=>'Дата активации',
                'content'=>function($data){
                    $mat = $data['time_start'];
                    if(!empty($mat)){
                        return date("d.m.Y H:i",$mat);
                    }
                }
            ],
            //'status',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                ],
        ],
    ]); ?>
</div>
