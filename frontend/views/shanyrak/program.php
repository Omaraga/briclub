<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = "Shanyrak";
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$user_pays = \common\models\ShanyrakUserPays::find()->where(['user_id'=>$user['id'],'program_id'=>$program])->all();

$years = array();
foreach ($user_pays as $user_pay) {
    $years[] = date("Y",$user_pay['time_need']);
}

$cur_payment = \common\models\ShanyrakUserPays::find()->where(['user_id'=>$user['id'],'program_id'=>$program,'status'=>2])->orderBy('id asc')->one();
$years = array_unique($years);
$year = date("Y",$user_pays[0]['time_need']);

/*$all_users = \common\models\ShanyrakUser::find()->where(['program_id'=>$program])->all();

foreach ($all_users as $all_user) {
    $user_pay = \common\models\ShanyrakUserPays::find()->where(['user_id'=>$all_user['id'],'program_id'=>$program,'status'=>1])->all();

}

echo "<pre>";
var_dump($all_users);
exit;*/
     ?>
    <?
    $flashes = Yii::$app->session->allFlashes;
    if(!empty($flashes)){
        foreach ($flashes as $key => $flash) {?>
            <div class="alert alert-<?=$key?> alert-dismissible fade show" role="alert">
                <?=$flash?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?}}
    ?>
    <main class="shanyrak-calendar">
        <div class="container">
            <!--
                    <div class="hgroup">
                        <h1 class="h1">Shanyrak</h1>
                        <div class="hline"></div>
                    </div>
            -->


            <section class="calendar-wrap">
                <div class="row">
                    <div class="col-lg-9 offset-lg-3">
                        <h1 class="h2">Календарь Платежей</h1>
                    </div>
                    <div class="col-lg-3">
                        <!--<div class="monthly alert">
                            <div class="monthly-home card mb-3">
                                <div class="card-header">
                                    Ежемесячный Платеж
                                </div>
                                <div class="card-body">
                                    <h2 class="card-title"><span class="cur">$</span>200</h2>
                                    <p class="card-text">Оплатите до 16 августа</p>
                                </div>
                                <div class="card-footer">
                                    <a href="" class="btn btn-outline-light">Оплатить</a>
                                </div>
                            </div>
                        </div>-->

                        <div class="monthly success">
                            <div class="monthly-home card mb-3">
                                <div class="card-header">
                                    Ежемесячный Платеж
                                </div>
                                <div class="card-body">
                                    <h2 class="card-title"><span class="cur">$</span><?=$cur_payment['sum_need']?></h2>
                                    <p class="card-text">Оплатить до <?=date('d.m.Y',$cur_payment['time_need'])?></p>
                                </div>
                                <div class="card-footer">
                                    <a href="/shanyrak/payment?pay_id=<?=$cur_payment['id']?>" class="btn btn-outline-light">Оплатить</a>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-9">
                        <section class="shanyrak-calendar-content">
                            <ul class="nav nav-tabs" id="shanyrak-tab" role="tablist">
                                <?
                                $i = 0;
                                foreach ($years as $year1) {
                                    $i++;?>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link <?if($i == 1){echo "active";}?>" id="year<?=$year1?>" data-toggle="tab" href="#tab<?=$year1?>" role="tab" aria-controls="home" aria-selected="true"><?=$year1?></a>
                                    </li>
                                <?}?>

                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tab<?=$year?>" role="tabpanel" aria-labelledby="year<?=$year?>">
                                    <?
                                    $i = 0;
                                    foreach ($user_pays as $user_pay) {
                                        $i++;
                                        $month = date("m",$user_pay['time_need']);
                                        $year = date("Y",$user_pay['time_need']);
                                        $month_n = date("m",$user_pay['time_need']);
                                        if($month == 1){
                                            $month = "Январь";
                                        }elseif($month == 2){
                                            $month = "Февраль";
                                        }elseif($month == 3){
                                            $month = "Март";
                                        }elseif($month == 4){
                                            $month = "Апрель";
                                        }elseif($month == 5){
                                            $month = "Май";
                                        }elseif($month == 6){
                                            $month = "Июнь";
                                        }elseif($month == 7){
                                            $month = "Июль";
                                        }elseif($month == 8){
                                            $month = "Август";
                                        }elseif($month == 9){
                                            $month = "Сентябрь";
                                        }elseif($month == 10){
                                            $month = "Октябрь";
                                        }elseif($month == 11){
                                            $month = "Ноябрь";
                                        }elseif($month == 12){
                                            $month = "Декабрь";
                                        }
                                        ?>
                                        <div class="card card-month">
                                            <div class="card-header">
                                                <span class="month-name"><?=$month?></span>
                                            </div>
                                            <div class="card-body">
                                                <div class="icon-block <?if($user_pay['status'] == 1){echo "check";}elseif ($user_pay['status'] == 2){echo "pending";}?> "> <!-- Статусы: check, uncheck, pending -->
                                                    <span class="day"><?=date("d",$user_pay['time_need'])?></span>
                                                    <div class="icon"></div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="#" class="count-pay stretched-link">$ <?if($user_pay['status'] == 1){echo $user_pay['sum_pay'];}else{echo $user_pay['sum_need'];}?></a>
                                            </div>
                                        </div>
                                        <?if($month_n%12 == 0){?>
                                            </div>
                                            <div class="tab-pane fade" id="tab<?=$year+1?>" role="tabpanel" aria-labelledby="year<?=$year+1?>">
                                        <?}?>
                                    <?}?>

                                </div>
                            </div>
                        </section>

                        <section class="card-user order">
                            <h3 class="h4">Очередь</h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="item">
                                        <div class="text-block">
                                            <div class="icon user"></div>
                                            <span class="user-name">loginuser***</span>
                                        </div>
                                        <div class="bar">
                                            <span class="lead">22.06.2020</span>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="text-block">
                                            <div class="icon user"></div>
                                            <span class="user-name">loginuser***</span>
                                        </div>
                                        <div class="bar">
                                            <span class="lead">22.06.2020</span>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="text-block">
                                            <div class="icon user"></div>
                                            <span class="user-name">loginuser***</span>
                                        </div>
                                        <div class="bar">
                                            <span class="lead">22.06.2020</span>

                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="text-block">
                                            <div class="icon user"></div>
                                            <span class="user-name">loginuser***</span>
                                        </div>
                                        <div class="bar">
                                            <div class="coutry-icon"></div>
                                            <span class="lead">KZ</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="item">
                                        <div class="text-block">
                                            <div class="icon user"></div>
                                            <span class="user-name">loginuser***</span>
                                        </div>
                                        <div class="bar">
                                            <span class="lead">22.06.2020</span>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="text-block">
                                            <div class="icon user"></div>
                                            <span class="user-name">loginuser***</span>
                                        </div>
                                        <div class="bar">
                                            <span class="lead">22.06.2020</span>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="text-block">
                                            <div class="icon user"></div>
                                            <span class="user-name">loginuser***</span>
                                        </div>
                                        <div class="bar">
                                            <span class="lead">22.06.2020</span>

                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="text-block">
                                            <div class="icon user"></div>
                                            <span class="user-name">loginuser***</span>
                                        </div>
                                        <div class="bar">
                                            <div class="coutry-icon"></div>
                                            <span class="lead">KZ</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </section>
                    </div>
                </div>
            </section>





        </div>
    </main>


<?
echo \frontend\components\LoginWidget::widget();
?>