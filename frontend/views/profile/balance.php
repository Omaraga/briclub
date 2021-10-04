<?php
use common\models\User;
$this->title = "Мои балансы";


?>
<main class="payment">
    <div class="payment-page">
        <div class="payment-block">
            <h4 class="w7 margin-bot-50">Мои балансы</h4>
            <div class="payment-blocks">
                <div class="blocks-img mb-4">
                    <div class="rows">
                        <h3 class="w7">СV <?=$user->w_balans?></h3>
                        <a class=" margin-top-38" href="\profile/perfect">Пополнить баланс</a>
                    </div>
                </div>
                <div class="blocks-img blocks-img2">
                    <div class="rows">
                        <h3 class="w7">PV <?=$user->p_balans?></h3>
                        <div class="center-line margin-top-38">
                            <img src="/img/payment/withdraw.svg" alt="">
                            <a class="ml-2" href="\profile/withdraw">Вывод стредств</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fon-main">
        <h6 class="mb-4">Последние активности баланса</h6>
        <?foreach ($withdraws as $withdraw):?>
            <div class="line between">
                <div class="flex-line">
                    <img src="/img/payment/arrow-<?=($withdraw['type']==2 || $withdraw['type']==7 || $withdraw['type']==105)? 'red' : 'green';?>.svg" alt="">
                    <div class="rows ml-3">
                        <p class="txt-mini mr-1"><?=\common\models\ActionTypes::findOne($withdraw['type'])['title'];?></p>
                        <p class="txt-6A7 txt-mini">
                        <?
                            echo $withdraw['title'];
                            if($withdraw['type'] == 3){
                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                            }elseif($withdraw['type'] == 4){
                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                            }
                        ?>
                        </p>
                    </div>
                </div>
                <div class="rows text-right">
                    <?if($withdraw['sum']>0):?>
                        <?if(\common\models\ActionTypes::findOne($withdraw['type'])['minus'] == 1):?>
                            <p class="txt-mini"><span class="ml-2" style="color: #F35653;">- <?=$withdraw['sum']?>
                                    <?=($withdraw['type']==2 || $withdraw['type']==7 || $withdraw['type']==105)? 'PV':'CV';?>
                                </span></p>
                        <?else:?>
                            <p class="txt-mini"><span class="ml-2"><?=$withdraw['sum']?>
                                    <?=($withdraw['type']==2 || $withdraw['type']==7 || $withdraw['type']==105)? 'PV':'CV';?>
                                </span></p>
                        <?endif;?>
                    <?endif;?>
                    <p class="txt-6A7"><?=date("d.m.Y", $withdraw['time'])?></p>
                </div>
            </div>
        <?endforeach;?>
        <a href="/profile/actions"><button class="btn-big fon-btn-blue-100 text-white">Показать все активности</button></a>
    </div>
</main>

