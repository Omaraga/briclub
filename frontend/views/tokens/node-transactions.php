<?php

/* @var $this yii\web\View */

use yii\widgets\LinkPager;
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
?>
<style>
    table {
        text-align: left;
        border-collapse: separate;
        border-spacing: 5px;
        background: #ECE9E0;
        color: #656665;
        border: 16px solid #ECE9E0;
        border-radius: 10px;
    }
    th {
        font-size: 18px;
        padding: 10px;
    }
    td {
        background: #F5D7BF;
        padding: 10px;
        font-weight: bold;
        border-radius: 3px;
    }
</style>
<div class="container">
    <h1 style="margin: 20px 0px 20px 0px">Транзакции ноды</h1>
    <div class="row">
        <table style="margin-bottom: 10px">
            <tr>
                <th>Id транзакции</th>
                <th>Сумма</th>
                <th>Дата и время</th>
                <th>Комиссия</th>
                <th>Id блока</th>
                <th>Id ноды</th>
                <th>Логин</th>
            </tr>
            <?foreach ($models as $action) { $login = \common\models\User::findOne(['id' => $action['user_id']])["username"]?>
                <tr>
                    <td><?=$action['id']?></td>
                    <td><?=$action['sum']?></td>
                    <td><?=date("d.m.Y H:i", $action['time'])?></td>
                    <td><?=$action['fee']?></td>
                    <td><?=$action['block_id']?></td>
                    <td><?=$action['node_id']?></td>
                    <td><?=$login[0]?>*****<?=$login[strlen($login) - 1]?></td>
                </tr>
            <?}?>
        </table>
    </div>
    <?php
    echo LinkPager::widget([
        'pagination' => $pages,
        'linkContainerOptions' => ['class' => 'page-item'],
        'linkOptions' => ['class' => 'page-link'],
        'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link']
    ]);?>
</div>