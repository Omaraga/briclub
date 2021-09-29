<?php
/* @var $this yii\web\View */
/* @var $list array */
use yii\helpers\Html;

?>


<main>
    <?=Html::a('Удалить все', '/britest/destroy', ['class' => 'btn btn-danger']);?>
    <h4 style="font-weight: bold">Можно зайти и проверить любого пользователя логин: test0(test0 это первый созданный пользователь дальше по порядку) пароль: qwerty</h4>
    <h5>Пользователи главные в ветке:</h5>
    <?foreach ($list['user_branch'] as $user):?>
        <h5 style="font-weight: bold"><?=$user->username;?></h5>
    <?endforeach;?>

    <p>Баланс компании: <b><?=$list['balance_company']?></b></p>
    <p>Баланс всех пользователей: <b><?=$list['total_pv']?> PV</b></p>
    <p>Баланс GRC: <b><?=$list['total_grc']?> GRC = <?=$list['total_grc'] *10?>$ у <?=$list['total_grc_users'];?> пользователей</b></p>
    <p>Баланс BRI: <b><?=$list['total_bri']?> BRI = <?=$list['total_bri'] *2?>$ у <?=$list['total_bri_users'];?> пользователей</b></p>
    <p>3 стол закрыли: <?=$list['complete_third']?> </p>
    <p>3 стол: <?=$list['third']?> </p>
    <p>2 стол закрыли: <?=$list['complete_second']?> </p>
    <p>2 стол: <?=$list['second']?> </p>
    <p>1 стол закрыли: <?=$list['complete_first']?> </p>
    <p>1 стол: <?=$list['first']?> </p>



</main>
