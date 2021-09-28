<?php

use common\models\User;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи с долгом';
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = $this->title;
$logins = \common\models\User::find()->where(['>','overdraft',0])->all();
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
$all_balans = 0;
$all_minus_balans = 0;

$users = User::find()->all();
$c = 0;
foreach ($users as $user) {
    if($user['overdraft']>0){
        $all_minus_balans = $all_minus_balans + $user['overdraft'];
        $c++;
    }
    $all_balans = $all_balans + $user['w_balans'];

}
$actions = \common\models\Actions::find()->where(['type'=>96])->orderBy('id desc')->all();
?>
<div class="site-index">
    <div class="row">
        <div class="col-12">
            <h4>Всего минус баланс у <?=$c?> пользователей, на сумму: $<?=$all_minus_balans?></h4>
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
            </div>
        </div>
    </div>

</div>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#panel2">Пользователи</a></li>
    <li><a data-toggle="tab" href="#panel3">Погашение долгов</a></li>
</ul>

<div class="tab-content">
    <div id="panel2" class="tab-pane fade in active">
        <h3>Пользователи</h3>
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
                //'w_balans',
                'overdraft',
                //'order',
                [
                    'attribute'=>'created_at',
                    'content'=>function($data){
                        return date("d.m.Y H:i",$data['created_at']);
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
    <div id="panel3" class="tab-pane fade">
        <h3>Погашение долгов</h3>
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Аккаунт</th>
                <th scope="col">Сумма</th>
                <th scope="col">Время</th>
                <th scope="col">Статус</th>
                <th scope="col">Тип</th>
            </tr>
            </thead>
            <tbody>
            <?
            $i = 0;
            foreach ($actions as $withdraw) {
                if($withdraw['type'] == 96){
                    $i++;
                    ?>
                    <tr>
                        <th scope="row"><?=$i?></th>
                        <td><?=\common\models\User::findOne($withdraw['user_id'])['username']?></td>
                        <td><?=$withdraw['sum']?></td>
                        <td><?=date("d.m.Y H:i", $withdraw['time'])?></td>
                        <td>
                            <?
                            $color = null;
                            $text = null;
                            if($withdraw['status'] == 3){
                                $color = "primary";
                                $text = "В обработке";
                            }elseif($withdraw['status'] == 2){
                                $color = "danger";
                                $text = "Отменено";
                            }elseif($withdraw['status'] == 1){
                                $color = "success";
                                $text = "Выполнено";
                            }
                            ?>
                            <span class="badge badge-<?=$color?>"><?=$text?></span>
                        </td>
                        <td><?=$withdraw['title']?></td>
                    </tr>
                <? }}?>

            </tbody>
        </table>
    </div>

</div>
