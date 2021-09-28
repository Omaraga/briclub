<?php

/* @var $this yii\web\View */

use common\models\Lessons;
use common\models\UserLessons;
use yii\httpclient\Client;


$this->title = "Профиль пользователя";
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$level = null;
$profit = null;
$url = null;
$binar_link = '/profile';
$all_children1 = array();
$all_children2 = array();
if($user['global'] == 1 or $user['newmatrix'] == 1){
    $prom = \common\models\PromotionRef::find()->where(['status'=>1])->one();

    $all_children1_personal = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'shoulder'=>1])->andWhere(['>=','time_personal',$prom->start])->andWhere(['<','time_personal',$prom->end])->all();
    $all_children1_global = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'shoulder'=>1])->andWhere(['>=','time_global',$prom->start])->andWhere(['<','time_global',$prom->end])->all();
    $all_children2_personal = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'shoulder'=>2])->andWhere(['>=','time_personal',$prom->start])->andWhere(['<','time_personal',$prom->end])->all();
    $all_children2_global = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'shoulder'=>2])->andWhere(['>=','time_global',$prom->start])->andWhere(['<','time_global',$prom->end])->all();

    $all_children1 = array_merge($all_children1_personal,$all_children1_global);
    $all_children2 = array_merge($all_children2_personal,$all_children2_global);

    $profit = 0;

    $user_pr = \common\models\UserPromotionsRef::find()->where(['user_id'=>$user['id'],'pr_id'=>$prom['id']])->orderBy('id desc')->one();
    if(!empty($user_pr)){
        $profit = $user_pr['sum'];
    }
    $binar_link = '/profile/bonus';
}

$children = \common\models\User::find()->where(['parent_id'=>$user['id'],'activ'=>1])->all();


$username = Yii::$app->user->identity['username'];
if($activ){
    $url = 'https://shanyrakplus.com/site/signup?referal='.$username;
    $url2 = 'https://shanyrakplus.com/tokens/get?promo='.$username;
}
$url3 = 'https://walletgrc.com/tokens/get?promo='.$username."_shanyrak";
$name = $username;
if(!empty(Yii::$app->user->identity['firstname'])){
    $name = Yii::$app->user->identity['firstname'];
}
$tokens = \common\models\Tokens::findOne(['user_id'=>$user['id'], 'wallet_type' => 7]);
$last_users = \common\models\Actions::find()->where(['type'=>[1,8,9,10,11]])->orderBy(new \yii\db\Expression('rand()'))->limit(12)->all();
$last_users2 = \common\models\Actions::find()->where(['type'=>[1,8,9,10,11]])->orderBy(new \yii\db\Expression('rand()'))->limit(12)->all();
$last_users3 = \common\models\Actions::find()->where(['type'=>[1,8,9,10,11]])->orderBy(new \yii\db\Expression('rand()'))->limit(12)->all();

/*echo "<pre>";
var_dump($last_users);
exit;*/
$script = <<< JS
$(document).ready(function() {
    setInterval(function(){
        $("#partners").hide();
        $("#partners2").hide();
        $("#partners3").show();
        }, 12000);
    setInterval(function(){
        $("#partners").hide();
        $("#partners3").hide();
        $("#partners2").show();
        }, 24000);
     setInterval(function(){
        $("#partners").hide();
        $("#partners2").hide();
        $("#partners3").show();
        }, 36000);
});
JS;
$this->registerJs($script);
$this->registerJs('
$(\'.carousel\').carousel({
  interval: 5000
})
$(\'#alertModal\').modal("show");
 /*var now = new Date();
 var hours = now.getHours()
 var divider = ":";
 if (now.getMinutes()<10) divider = ":0";
 var time = ""+hours + divider + now.getMinutes();

 if(hours<5 || hours>19){
    $(\'#hello_text\').html("Доброй ночи");
   
 }else if (hours<11){
 $(\'#hello_text\').html("Доброе утро");
     
 }else{
 $(\'#hello_text\').html("Добрый день");
    
 } */
 
');
$this->registerJsFile('/js/clipboard.js');
$this->registerJsFile('https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js');
$this->registerJsFile('https://yastatic.net/share2/share.js');
$startDate = time();
/*$this->registerJs('
var startDay = 1613822400000;

var endDate = 1615622400000 - startDay;

    var target_date = startDay + endDate; // установить дату обратного отсчета
var days, hours, minutes, seconds; // переменные для единиц времени
 
var countdown = document.getElementById("tiles"); // получить элемент тега
 
getCountdown();
 
setInterval(function () { getCountdown(); }, 1000);
 
function getCountdown(){
 
    var current_date = new Date().getTime();
    var seconds_left = (target_date - current_date) / 1000;
 
    days = pad( parseInt(seconds_left / 86400) );
    seconds_left = seconds_left % 86400;
          
    hours = pad( parseInt(seconds_left / 3600) );
    seconds_left = seconds_left % 3600;
           
    minutes = pad( parseInt(seconds_left / 60) );
    seconds = pad( parseInt( seconds_left % 60 ) );
 
    // строка обратного отсчета  + значение тега
    countdown.innerHTML = "<span>" + days + "</span><span>" + hours + "</span><span>" + minutes + "</span><span>" + seconds + "</span>"; 
}
 
function pad(n) {
    return (n < 10 ? "0" : "") + n;
}
');*/

$nodeQuery = \common\models\TokenNodesQueries::findOne(['user_id' => $user->id, 'status' => 1]);
$isTokenVisible = true;//!empty(\common\models\TokenUsers::findOne(['user_id' => $user->id]));

$bonusWallet = \common\models\Tokens::findOne(['user_id' => $user->id, 'wallet_type' => 8]);
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
    <main class="home">
        <style>
            #countdown {
                width: 220px;
                height: 70px;
                text-align: center;
                background: #222;
                background-image: -webkit-linear-gradient(top, #222, #333, #333, #222);
                background-image: -moz-linear-gradient(top, #222, #333, #333, #222);
                background-image: -ms-linear-gradient(top, #222, #333, #333, #222);
                background-image: -o-linear-gradient(top, #222, #333, #333, #222);
                border: 1px solid #111;
                border-radius: 5px;
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
                margin: auto;
                padding: 24px 0;
                position: absolute;
                top: 180px;
                bottom: 0;
                left: 0;
                right: 0
            }

            #countdown:before {
                content: "";
                width: 8px;
                height: 65px;
                background: #444;
                background-image: -webkit-linear-gradient(top, #555, #444, #444, #555);
                background-image: -moz-linear-gradient(top, #555, #444, #444, #555);
                background-image: -ms-linear-gradient(top, #555, #444, #444, #555);
                background-image: -o-linear-gradient(top, #555, #444, #444, #555);
                border: 1px solid #111;
                border-top-left-radius: 6px;
                border-bottom-left-radius: 6px;
                display: block;
                position: absolute;
                top: 48px;
                left: -10px
            }

            #countdown:after {
                content: "";
                width: 8px;
                height: 65px;
                background: #444;
                background-image: -webkit-linear-gradient(top, #555, #444, #444, #555);
                background-image: -moz-linear-gradient(top, #555, #444, #444, #555);
                background-image: -ms-linear-gradient(top, #555, #444, #444, #555);
                background-image: -o-linear-gradient(top, #555, #444, #444, #555);
                border: 1px solid #111;
                border-top-right-radius: 6px;
                border-bottom-right-radius: 6px;
                display: block;
                position: absolute;
                top: 48px;
                right: -10px
            }

            #countdown #tiles {
                position: relative;
                z-index: 1
            }

            #countdown #tiles>span {
                width: 30px;
                max-width: 30px;
                font: bold 20px 'Droid Sans', Arial, sans-serif;
                text-align: center;
                color: #111;
                background-color: #ddd;
                background-image: -webkit-linear-gradient(top, #bbb, #eee);
                background-image: -moz-linear-gradient(top, #bbb, #eee);
                background-image: -ms-linear-gradient(top, #bbb, #eee);
                background-image: -o-linear-gradient(top, #bbb, #eee);
                border-top: 1px solid #fff;
                border-radius: 3px;
                box-shadow: 0 0 12px rgba(0, 0, 0, 0.7);
                margin: 0 7px;
                padding: 3px 0;
                display: inline-block;
                position: relative;
                top: -15px;
            }

            #countdown #tiles>span:before {
                content: "";
                width: 100%;
                height: 13px;
                background: #111;
                display: block;
                padding: 0 3px;
                position: absolute;
                top: 41%;
                left: -3px;
                z-index: -1
            }

            #countdown #tiles>span:after {
                content: "";
                width: 100%;
                height: 1px;
                background: #eee;
                border-top: 1px solid #333;
                display: block;
                position: absolute;
                top: 48%;
                left: 0
            }

            #countdown .labels {
                width: 100%;
                height: 25px;
                text-align: center;
                position: absolute;
                bottom: 8px
            }

            #countdown .labels li {
                width: 40px;
                font: bold 9px 'Droid Sans', Arial, sans-serif;
                /*color: #f47321;*/
                color: #fff;
                text-shadow: 1px 1px 0 #000;
                text-align: center;
                text-transform: uppercase;
                display: inline-block
            }
        </style>
        <div class="container">
            <div class="hgroup pt-3">
                <h3 class="h3">Программа «Shanyrak+»</h3>
                <h5 class="h5"> Предназначена для тех, кто умеет работать онлайн, обладает лидерскими качествами и готов вместе с нами заработать на собственную недвижимость, на свои ежедневные потребности, для реализации мечты своей семьи!  «Shanyrak+» имеет ценную партнерскую программу, которая дает всем равные возможности.</h5>
                <div class="hline"></div>
            </div>
            <div class="card-group">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card main-balans">
                            <div class="card-header">
                                <div class="title-card">
                                    <div class="icon"><div class="main-balans-icon"></div></div>
                                    <span class="lead">Баланс</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="main-text">
                                    <span class="current">US</span>
                                    <span class="big-text"><?=$user['w_balans']?></span><br>
                                    <?if($user['b_balans']>0){?>
                                        <span class="current" style="font-size: 1rem;color: #ff8100;">US</span>
                                        <span class="big-text" style="font-size: 1rem;margin-left: 1.25rem;color: #ff8100;"><?=$user['b_balans']?></span>

                                        <span title="Пригласите двух личников чтобы снять эту сумму">
                                            <svg  style="color: #ff8100;margin-bottom: 4px;" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
                                            <circle cx="8" cy="4.5" r="1"/>
                                        </svg>
                                        </span>
                                    <?}?>

                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="/profile/perfect" class="btn btn-link">Пополнить</a>
                                <?
                                if($activ){?>
                                    <a href="/profile/withdraw" class="btn btn-link">Вывести</a>
                                <?}else{?>

                                <?}?>
                                <?
                                if($activ){?>
                                    <a href="/profile/transfer" class="btn btn-link">Перевести</a>
                                <?}else{?>

                                <?}?>
                            </div>
                        </div>
                        <?if($isTokenVisible){?>
                            <div class="card shakyrak">
                                <div class="card-header">
                                    <div class="title-card">
                                        <div class="icon"><div class="shakyrak-icon"></div></div>
                                        <span class="lead">Токены</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="main-text">
                                        <span class="small">Текущий баланс: <? if(!empty($tokens)){echo $tokens['balans'];}else{echo "0.00";}?></span>
                                        <span class="small">GRС</span>

                                        <br>

                                        
                                        <?if(!empty($bonusWallet)){?>
                                            <span class="small">Бонусные токены: <?=$bonusWallet['balans']?> GRC</span>
                                            <br>
                                        <?}?>

                                        <?if(!empty($nodeQuery)){?>
                                            <span class="small">Занесено в ноду: <? if(!empty($nodeQuery)){echo $nodeQuery['tokens_count'];}else{echo "0.00";}?></span>
                                            <span class="small">GRС</span>

                                            <br>

                                            <span class="small">Итого: <? if(empty($tokens)){$tokens['balans'] = 0;} if(empty($nodeQuery)){$nodeQuery['tokens_count'] = 0;} echo $tokens['balans'] + $nodeQuery['tokens_count'];?></span>
                                            <span class="small">GRС</span>
											<br>
                                        <?}?>
										<a href="#" style="color: #fff" data-toggle="modal" data-target="#promoGrcModal">Получить ссылку на GRC+</a>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="/tokens/get" class="btn btn-link">Пополнить</a>

                                    <a href="/tokens/token-transfer" class="btn btn-link">Перевести</a>


                                    <a href="/tokens" class="btn btn-link">Подробнее</a>

                                </div>
                            </div>
                        <?}?>
                    </div>
                    <?if($activ){?>
                        <div class="col-lg-4">
                            <div class="card matrix">
                                <div class="card-header">
                                    <div class="title-card">
                                        <div class="icon"><div class="matrix-icon"></div></div>
                                        <span class="lead">Партнерская программа</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="card-col" style="width: 290px;">
                                        <div class="main-text">
                                            <?
                                            if($activ){?>
                                                <span class="regular-text"><a style="padding: 0; color: #fff" href="/profile/structure">Shanyrak+</a>
                                                    <span style="float: right;">
                                                        <?
                                                        if($user['newmatrix'] == 1){
                                                            $personal = \common\models\MatrixRef::find()->where(['user_id'=>$user['id']])->orderBy('platform_id desc')->one();
                                                            if(!empty($personal)){
                                                                echo $personal['platform_id'];
                                                            }
                                                        }else{
                                                            echo 0;
                                                        }
                                                        ?>
                                                        уровень</span>
                                                </span>
                                                <br>
                                            <?}else{?>
                                                <p><span class="regular-text">Вас нет в матрице</span></p>
                                            <?}?>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <?if($activ){?>
                                        <a href="/profile/children" class="btn btn-link">My referrals: </a>
                                        <a href="#" data-toggle="modal" data-target="#refModal" class="btn btn-link">Реферальная ссылка</a>
                                    <?}?>
                                </div>
                            </div>

                            <a href="/news/17" >
                                <div class="card shakyrak">
                                    <div class="card-body" style="background: url(/docs/1239972.png); background-size: cover; height: 10.75rem">
<!--                                        <img class="img" style="width: 100%; margin-bottom: -14px;" src="">-->
                                    </div>
                                    <div class="card-footer" style="background-color:#453c3e!important;">
                                        <div style="padding-right: 90px" class="timer-word">
                                            <a href="#" style="color: #fff" data-toggle="modal" data-target="#promoModal">Получить ссылку на участие</a>
                                        </div>
                                        <!--div id="countdown">
                                            <div id="tiles"></div>
                                            <div class="labels">
                                                <li>Дней</li>
                                                <li>Часов</li>
                                                <li>Минут</li>
                                                <li>Секунд</li>
                                            </div>
                                        </div-->
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?}?>
                    <div class="col-lg-4">
                        <!--<div class="card bonus-program">
                            <div class="card-header">
                                <div class="title-card">
                                    <div class="icon"><div class="bonus-program-icon"></div></div>
                                    <span class="lead">Библиотека</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-col">
                                    <?/*if($activ){*/?>
                                        <div class="main-text">
                                            <a href="/library" class="btn btn-primary"><span class="small">Перейти к библиотеке<b></b></span></a>
                                        </div>
                                    <?/*}else{*/?>
                                        <p><a href="/profile/get-course?program=2" class="btn btn-primary">Подписаться</a></p>
                                    <?/*}*/?>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="small"></div>
                                <a href="" class="btn btn-link">Подробнее</a>
                            </div>
                        </div>-->
                        <?if($activ){?>
                            <div id="carouselExampleControls" class="card learn-card carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <?
                                    $books = \common\models\Library::find()->where(['status'=>1])->orderBy('order asc')->all();
                                    $c = 0;
                                    foreach ($books as $book) {
                                        $c++;
                                        ?>
                                        <div class="carousel-item <?if($c ==1){echo "active";}?>">
                                            <div class="video position-relative" style="background: url(<?=$book['link2']?>) no-repeat center; background-size: cover;">
                                            </div>
                                            <div class="learn-info">
                                                <div class="card-header">
                                                    <div class="titles">
                                                        <h4 class="h4"><?=$book['title']?></h4>
                                                        <!--<p class="lead">Пройдено <?/*=count($user_lessons_db)*/?> из <?/*=count($all_lessons)*/?> уроков</p>-->
                                                    </div>
                                                    <div class="progress-bar"></div>
                                                </div>
                                            </div>
                                        </div>

                                    <?}?>

                                </div>
                                <div class="card-footer available" style="background-color: #02A651;"><!-- homework, available, lock  -->
                                    <div class="timer-word">
                                        <a href="/library" style="color: #1989F8"><span style="color: #fff; font-weight: bold" class="timer-text lead">Перейти к библиотеке</span></a> <!-- lock  -->
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true">
                                    </span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true">
                                    </span>
                                    <span class="sr-only">Next</span>
                                </a>

                            </div>
                        <?}else{
                            $first = \common\models\Library::findOne(8);
                            ?>
                            <div class="card learn-card">
                                <div class="video position-relative" style="background: url(<?=$first['link2']?>) no-repeat center; background-size: cover;"></div>
                                <div class="learn-info">
                                    <div class="card-header">
                                        <div class="titles">
                                            <h4 class="h4"><?=$first['title']?></h4>
                                        </div>
                                        <div class="progress-bar"></div>
                                    </div>
                                    <div class="item position-relative">
                                        <a href="/profile/get-course?program=2" class="btn btn-primary">Оплатить подписку</a>
                                    </div>
                                    <div class="card-footer duration"><!-- duration, available, lock  -->
                                        <div class="timer-word">
                                            <div class="icon-timer"></div>
                                            <span class="timer-text lead">Оплатите подписку чтобы получить полный доступ к библиотеке </span> <!-- lock  -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?}?>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <div class="modal fade" id="refModal" tabindex="-1" role="dialog" aria-labelledby="refModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Поделиться реферальной ссылкой
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <span id="p1"><?=$url?></span>
                            <button class="btn btn-link" onclick="copy('p1')">Копировать</button>
                            <h4>Поделиться в:</h4>
                            <div class="ya-share2" data-title="Реферальная ссылка Shanyrakplus.com" data-url="<?=$url?>" data-services="vkontakte,facebook,twitter,viber,whatsapp,skype,telegram"></div>
                            <br>
                            <br>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="promoModal" tabindex="-1" role="dialog" aria-labelledby="promoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Поделиться ссылкой на продажу токенов
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <span id="p2"><??></span>
                            <button class="btn btn-link" onclick="copy('p2')">Копировать</button>
                            <h4>Поделиться в:</h4>
                            <div class="ya-share2" data-title="Промо ссылка Shanyrakplus.com" data-url="<?//$url2?>" data-services="vkontakte,facebook,twitter,viber,whatsapp,skype,telegram"></div>
                            <br>
                            <br>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="promoGrcModal" tabindex="-1" role="dialog" aria-labelledby="promoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Поделиться ссылкой на GRC+
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <span id="p3"><?=$url3?></span>
                            <button class="btn btn-link" onclick="copy('p3')">Копировать</button>
                            <h4>Поделиться в:</h4>
                            <div class="ya-share2" data-title="Промо ссылка Walletgrc.com" data-url="<?=$url3?>" data-services="vkontakte,facebook,twitter,viber,whatsapp,skype,telegram"></div>
                            <br>
                            <br>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?
$event = \common\models\News::find()->where(['slider'=>1,'status'=>1])->orderBy('order asc')->one();
if(!empty($event)){?>
    <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <a href="/news/<?=$event['id']?>"><img src="<?=$event['link']?>" width="100%" style="height: auto" class="image"  alt=""></a>
                </div>

            </div>
        </div>
    </div>
<?}
?>


<?
echo \frontend\components\LoginWidget::widget();
?>