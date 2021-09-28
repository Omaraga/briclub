<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Выплаты по бонусным программам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promotion-index">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#panel100">Бинар</a></li>
        <li><a data-toggle="tab" href="#panel11">Максимум</a></li>

    </ul>

    <div class="tab-content">
        <div id="panel100" class="tab-pane fade in active">
            <h3>Бинар</h3>
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
                foreach ($binar as $withdraw) {
                    if($withdraw['type'] == 94 or $withdraw['type'] == 95){
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
        <div id="panel11" class="tab-pane fade">
            <h3>Максисум</h3>
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
                foreach ($maksimum as $withdraw) {
                    if($withdraw['type'] == 94 or $withdraw['type'] == 95){
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
</div>
