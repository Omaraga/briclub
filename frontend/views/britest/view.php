<?php
/* @var $this yii\web\View */
/* @var $list array */
use yii\helpers\Html;

?>
<style>
    table{
        text-align: center;
    }
    table td{
        padding:5px;
    }
</style>

<main style="color: #000;">
    <?=Html::a('Удалить все', '/britest/destroy', ['class' => 'btn btn-danger']);?>
    <h4 style="font-weight: bold">Можно зайти и проверить любого пользователя логин: test0(test0 это первый созданный пользователь дальше по порядку) пароль: qwerty</h4>
    <h5>Пользователи главные в ветке:</h5>
    <?foreach ($list['user_branch'] as $user):?>
        <h5 style="font-weight: bold"><?=$user->username;?></h5>
    <?endforeach;?>

<!--    <p>Баланс компании: <b>--><?//=$list['balance_company']?><!--</b></p>-->
<!--    <p>Баланс всех пользователей: <b>--><?//=$list['total_pv']?><!-- PV</b></p>-->
<!--    <p>Баланс GRC: <b>--><?//=$list['total_grc']?><!-- GRC = --><?//=$list['total_grc'] *10?><!--$ у --><?//=$list['total_grc_users'];?><!-- пользователей</b></p>-->
<!--    <p>Баланс BRI: <b>--><?//=$list['total_bri']?><!-- BRI = --><?//=$list['total_bri'] *2?><!--$ у --><?//=$list['total_bri_users'];?><!-- пользователей</b></p>-->
<!--    <p>3 стол закрыли: --><?//=$list['complete_third']?><!-- </p>-->
<!--    <p>3 стол: --><?//=$list['third']?><!-- </p>-->
<!--    <p>2 стол закрыли: --><?//=$list['complete_second']?><!-- </p>-->
<!--    <p>2 стол: --><?//=$list['second']?><!-- </p>-->
<!--    <p>1 стол закрыли: --><?//=$list['complete_first']?><!-- </p>-->
<!--    <p>1 стол: --><?//=$list['first']?><!-- </p>-->
<!--    <p>Всего пользователей: --><?//=$list['user_amount']?><!--</p>-->


    <table border="1">
        <tr style="font-weight: bold;">
            <td>Пришло</td>
            <td>1 стол</td>
            <td>Закрыли первый стол</td>
            <td>2 стол</td>
            <td>Закрыли 2 стол</td>
            <td>3 стол</td>
            <td>Закрыли 3 стол</td>
            <td>Всего</td>
        </tr>
        <tr style="padding:5px;">
            <td>Количество</td>
            <td><?=$list['first']?></td>
            <td><?=$list['complete_first']?></td>
            <td><?=$list['second']?></td>
            <td><?=$list['complete_second']?></td>
            <td><?=$list['third']?></td>
            <td><?=$list['complete_third']?></td>
            <td><?=$list['user_amount'];?></td>
        </tr>
        <tr style="padding:5px;">
            <td>PV</td>
            <td>-</td>
            <td>-</td>
            <td><?=$list['second'];?>x2500 PV = <?=$list['second']*2500;?>$ </td>
            <td>-</td>
            <td><?=$list['third'];?>x10000 PV = <?=$list['third']*10000;?>$ </td>
            <td><?=$list['complete_third'];?>x100000 PV = <?=$list['complete_third']*100000;?>$</td>
            <td><?=$list['total_pv']?>$ </td>
        </tr>
        <tr style="padding:5px;">
            <td>BRI</td>
            <td>-</td>
            <td>-</td>
            <td><?=$list['second'];?>x750 BRI = <?=$list['second']*750;?>BRI = <?=$list['second']*750*2;?>$ </td>
            <td>-</td>
            <td><?=$list['third'];?>x3250 BRI = <?=$list['third']*3250;?>BRI = <?=$list['third']*3250*2;?>$ </td>
            <td><?=$list['complete_third'];?>x20000 BRI = <?=$list['complete_third']*20000;?>BRI = <?=$list['complete_third']*20000*2;?>$ </td>
            <td><?=intval($list['total_bri'])?> BRI = <?=$list['total_bri']*2?>$ </td>
        </tr>
        <tr style="padding:5px;">
            <td>GRC</td>
            <td>-</td>
            <td>-</td>
            <td><?=$list['second'];?>x100 GRC = <?=$list['second']*100;?>GRC = <?=$list['second']*100*10;?>$ </td>
            <td>-</td>
            <td><?=$list['third'];?>x350 GRC = <?=$list['third']*350;?>GRC = <?=$list['third']*350*10;?>$ </td>
            <td><?=$list['complete_third'];?>x1500 GRC = <?=$list['complete_third']*1500;?>GRC = <?=$list['complete_third']*1500*10;?>$ </td>
            <td><?=intval($list['total_grc'])?> GRC = <?=$list['total_grc']*10?>$ </td>
        </tr>
        <tr style="padding:5px;">
            <td>Сумма</td>
            <td>-</td>
            <td>-</td>
            <td><?=$list['second']*100*10 + $list['second']*2500 + $list['second']*750*2;?>$ </td>
            <td>-</td>
            <td><?=$list['third']*350*10 + $list['third']*10000 + $list['third']*3250*2?>$ </td>
            <td><?=$list['complete_third']*1500*10 + $list['complete_third']*100000 + $list['complete_third']*20000*2;?>$ </td>
            <td><?=$list['total_grc']*10 + $list['total_pv'] + $list['total_bri']*2;?>$ </td>
        </tr>
        <tr style="padding:5px;">
            <td>Компания</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>- </td>
            <td>- </td>
            <td><?=$list['user_amount'];?>x5000$ = <?=$list['user_amount']*5000?> $ </td>
        </tr>
        <tr style="padding:5px;">
            <td>Остаток</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>- </td>
            <td>- </td>
            <td><?=$list['user_amount']*5000 - ($list['total_grc']*10 + $list['total_pv'] + $list['total_bri']*2)?> $ </td>
        </tr>
    </table>



</main>
