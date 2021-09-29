<?php

/* @var $this yii\web\View */

use common\models\User;


$this->title = "Еженедельный максимум";
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$shoulder1 = \common\models\Referals::find()->where(['parent_id'=>$user->id,'shoulder'=>1])->all();
$shoulder2 = \common\models\Referals::find()->where(['parent_id'=>$user->id,'shoulder'=>2])->all();

$proms = \common\models\PromotionNew::find()->orderBy('id desc')->all();


 ?>
    <main class="bonus-history">
        <div class="container">
            <div class="hgroup">
                <h1 class="h1">Еженедельный максимум</h1>
                <div class="hline"></div>
            </div>
            <div class="accordion style3" id="accordionExample">
                <?
                $i = 0;
                foreach ($proms as $prom) {
                    if($prom['status'] == 3) continue;
                    $i++;
                    $level = "Нет уровня";

                    $profit = 0;
                    $user_pr = \common\models\UserPromotionsNew::find()->where(['user_id'=>$user['id'],'pr_id'=>$prom['id']])->orderBy('id desc')->one();
                    if(!empty($user_pr)){
                        $level = \common\models\BonusTarifsNew::findOne($user_pr['tarif_id'])['title'];
                        $profit = \common\models\BonusTarifsNew::findOne($user_pr['tarif_id'])['sum'];
                    }
                    ?>
                    <div class="card">
                        <div class="card-header" id="heading<?=$prom['id']?>">
                            <h2 class="mb-0">
                                <button class="text-left" type="button" data-toggle="collapse" data-target="#collapse<?=$prom['id']?>" aria-expanded="true" aria-controls="collapse<?=$prom['id']?>">
                                    <span class="time"><span class="start">Начало</span><?=date('d.m.Y H:i',$prom['start'])?></span> – <span class="time"><span class="finish">Конец</span><?=date('d.m.Y H:i',$prom['end'])?></span>
                                </button>
                            </h2>
                        </div>

                        <div id="collapse<?=$prom['id']?>" class="collapse <?if($i==1){echo "show";}?>" aria-labelledby="heading<?=$prom['id']?>" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="bonus">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="wrap bonus-header">
                                                <div class="avatar">
                                                    <xml version="1.0" encoding="iso-8859-1"?>
                                                        <!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                             viewBox="0 0 489.1 489.1" style="enable-background:new 0 0 489.1 489.1;" xml:space="preserve">
                                                            <g>
                                                                <g>
                                                                    <path id="XMLID_744_" style="fill:#3C92CA;" d="M323.95,180.9v-22.4c0-4.7-2.1-9.2-5.8-12.2c-20.3-16.6-42.1-27.7-46.5-29.9
                                                                        c-0.5-0.2-0.8-0.8-0.8-1.3V83.5c4-2.6,6.6-7.1,6.6-12.3V38.5c0-16.3-13.2-29.5-29.5-29.5h-3.5h-3.5c-16.3,0.1-29.5,13.3-29.5,29.5
                                                                        v32.7c0,5.1,2.6,9.6,6.6,12.3V115c0,0.6-0.3,1.1-0.8,1.3c-4.4,2.2-26.3,13.2-46.5,29.9c-3.7,3-5.8,7.5-5.8,12.2v22.4"/>
                                                                    <path d="M122.45,306.4c5,0,9.1-4.1,9.1-9.1v-32.6h225.1v32.6c0,5,4.1,9.1,9.1,9.1s9.1-4.1,9.1-9.1v-41.6c0-5-4.1-9.1-9.1-9.1
                                                                        h-112.2V214c0-5-4.1-9.1-9.1-9.1s-9.1,4.1-9.1,9.1v32.6h-112.9c-5,0-9.1,4.1-9.1,9.1v41.6C113.35,302.4,117.45,306.4,122.45,306.4
                                                                        z"/>
                                                                    <path d="M403.75,383.5v-28.8c0-19.3-15.7-35-35-35h-6.2c-19.3,0-35,15.7-35,35v28.8c0,5.6,2.1,10.9,5.8,14.9v18.9
                                                                        c-7.8,4.1-23.6,13-38.4,25.2c-5.3,4.4-8.4,10.9-8.4,17.8V480c0,5,4.1,9.1,9.1,9.1s9.1-4.1,9.1-9.1v-19.7c0-1.5,0.6-2.8,1.8-3.8
                                                                        c17.1-14,35.6-23.4,39.2-25.1c3.6-1.8,5.8-5.3,5.8-9.3v-27.7c0-3-1.5-5.9-4-7.5c-1.1-0.7-1.8-1.9-1.8-3.3v-28.8
                                                                        c0-9.3,7.6-16.9,16.9-16.9h6.2c9.3,0,16.9,7.6,16.9,16.9v28.7c0,1.3-0.7,2.5-1.8,3.3c-2.5,1.7-4,4.5-4,7.5V422
                                                                        c0,4,2.2,7.5,5.8,9.3c3.5,1.7,22.1,11.1,39.2,25.1c1.1,0.9,1.8,2.3,1.8,3.8v19.7c0,5,4.1,9.1,9.1,9.1s9.1-4.1,9.1-9.1v-19.7
                                                                        c0-6.9-3.1-13.4-8.4-17.8c-14.8-12.1-30.5-21.1-38.4-25.2v-18.9C401.65,394.3,403.75,389.1,403.75,383.5z"/>
                                                                    <path d="M119.35,337.9h6.1c9.1,0,16.5,7.4,16.5,16.5v28.4c0,1.3-0.6,2.4-1.7,3.1c-2.5,1.7-4,4.5-4,7.5v27.4c0,4,2.2,7.5,5.8,9.3
                                                                        c3.5,1.7,21.8,10.9,38.7,24.8c1.1,0.9,1.7,2.2,1.7,3.6V478c0,5,4.1,9.1,9.1,9.1s9.1-4.1,9.1-9.1v-19.5c0-6.9-3-13.3-8.3-17.6
                                                                        c-14.6-12-30.1-20.8-37.9-24.9v-18.6c3.6-4,5.7-9.2,5.7-14.7v-28.4c0-19.1-15.6-34.7-34.7-34.7h-6.1c-19.1,0-34.7,15.6-34.7,34.7
                                                                        v28.4c0,5.5,2.1,10.8,5.7,14.7V416c-7.8,4.1-23.3,12.9-37.9,24.9c-5.3,4.3-8.3,10.8-8.3,17.6V478c0,5,4.1,9.1,9.1,9.1
                                                                        s9.1-4.1,9.1-9.1v-19.5c0-1.4,0.6-2.8,1.7-3.6c16.9-13.9,35.2-23.1,38.7-24.8c3.6-1.8,5.8-5.3,5.8-9.3v-27.3c0-3-1.5-5.9-4-7.5
                                                                        c-1.1-0.7-1.7-1.9-1.7-3.1v-28.4C102.85,345.3,110.25,337.9,119.35,337.9z"/>
                                                                    <path d="M323.95,189.9c5,0,9.1-4.1,9.1-9.1v-22.3c0-7.5-3.3-14.5-9.1-19.2c-17.1-14.1-35.5-24.3-44.1-28.8V87.7
                                                                        c4.2-4.4,6.6-10.2,6.6-16.4V38.5c0-21.2-17.3-38.5-38.5-38.5h-7c-21.3,0-38.5,17.3-38.5,38.5v32.7c0,6.2,2.4,12,6.6,16.4v22.7
                                                                        c-8.6,4.5-26.9,14.7-44.1,28.8c-5.8,4.7-9.1,11.8-9.1,19.2v22.4c0,5,4.1,9.1,9.1,9.1s9.1-4.1,9.1-9.1v-22.2c0-2.1,0.9-4,2.5-5.2
                                                                        c19.5-16.1,40.7-26.7,44.8-28.7c3.6-1.8,5.9-5.4,5.9-9.4V83.5c0-3-1.5-5.9-4-7.5c-1.6-1.1-2.5-2.8-2.5-4.7V38.5
                                                                        c0-11.2,9.1-20.4,20.4-20.4h7c11.2,0,20.4,9.2,20.4,20.4v32.7c0,1.9-1,3.7-2.5,4.7c-2.5,1.7-4,4.5-4,7.5v31.5c0,4,2.3,7.7,5.9,9.5
                                                                        c4.1,2,25.2,12.7,44.8,28.7c1.6,1.3,2.5,3.2,2.5,5.2v22.4C314.95,185.9,318.95,189.9,323.95,189.9z"/>
                                                                </g>
                                                            </g>
                                                            </svg>

                                                </div>
                                                <div class="content">
                                                    <p class="name-client"><?=$user['username']?></p>
                                                    <small class="level-info lead">Ваш уровень:<span class="level"><?=$level?></span></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="wrap bonus-info">
                                                <div class="money">
                                                    <span class="cur">$</span><span class="balance h2"><?=$profit?></span>
                                                </div>
                                                <div class="dop-info">
                                                    <small class="lead">Поступит на основной счет по окончании недели</small>
                                                    <!--                        <small class="small">Оставлось 7 дней, 8 часов </small>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <section class="wrap card-user lvl1">
                                                <small class="lead">Левое плечо: </small>
                                                <?
                                                $i1 = 0;
                                                foreach ($shoulder1 as $item1) {
                                                    $user1 = User::findOne($item1['user_id']);
                                                    ?>
                                                    <?if($user1['newmatrix'] == 1){
                                                        if($user1['time_personal'] >= $prom['start'] and $user1['time_personal'] < $prom['end']){
                                                            $i1++;
                                                            ?>
                                                            <div class="item">
                                                                <div class="text-block">
                                                                    <div class="icon user"></div>
                                                                    <span class="user-name"><?=$user1['username']?> (Personal)</span>
                                                                </div>
                                                                <div class="bar">
                                                                    <span class="lead"><?=date('d.m.Y H:i',$user1['time_personal'])?></span>
                                                                </div>
                                                            </div>
                                                        <?}}?>
                                                    <?if($user1['global'] == 1){
                                                        if($user1['time_global'] >= $prom['start'] and $user1['time_global'] < $prom['end']){
                                                            $i1++;
                                                            ?>
                                                            <div class="item">
                                                                <div class="text-block">
                                                                    <div class="icon user"></div>
                                                                    <span class="user-name"><?=$user1['username']?> (Global)</span>
                                                                </div>
                                                                <div class="bar">
                                                                    <span class="lead"><?=date('d.m.Y H:i',$user1['time_global'])?></span>
                                                                </div>
                                                            </div>
                                                        <?}}?>
                                                <?}?>
                                                <small class="lead">Всего: <?=$i1?></small>
                                            </section>
                                        </div>
                                        <div class="col-lg-6">
                                            <section class="wrap card-user lvl1">
                                                <small class="lead">Правое плечо: </small>
                                                <?
                                                $i2 = 0;
                                                foreach ($shoulder2 as $item2) {
                                                    $user2 = User::findOne($item2['user_id']);
                                                    ?>
                                                    <?if($user2['newmatrix'] == 1){
                                                        if($user2['time_personal'] >= $prom['start'] and $user2['time_personal'] < $prom['end']){
                                                            $i2++;
                                                            ?>
                                                            <div class="item">
                                                                <div class="text-block">
                                                                    <div class="icon user"></div>
                                                                    <span class="user-name"><?=$user2['username']?> (Personal)</span>
                                                                </div>
                                                                <div class="bar">
                                                                    <span class="lead"><?=date('d.m.Y H:i',$user2['time_personal'])?></span>
                                                                </div>
                                                            </div>
                                                        <?}}?>
                                                    <?if($user2['global'] == 1){
                                                        if($user2['time_global'] >= $prom['start'] and $user2['time_global'] < $prom['end']){
                                                            $i2++;
                                                            ?>
                                                            <div class="item">
                                                                <div class="text-block">
                                                                    <div class="icon user"></div>
                                                                    <span class="user-name"><?=$user2['username']?> (Global)</span>
                                                                </div>
                                                                <div class="bar">
                                                                    <span class="lead"><?=date('d.m.Y H:i',$user2['time_global'])?></span>
                                                                </div>
                                                            </div>
                                                        <?}}?>
                                                <?}?>
                                                <small class="lead">Всего: <?=$i2?></small>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?}?>
            </div>
        </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>