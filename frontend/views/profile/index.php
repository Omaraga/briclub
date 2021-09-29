<div class="them dark light">
<?php

/* @var $this yii\web\View */
/* @var $statisticModel frontend\models\StatisticModel */
use common\models\Lessons;
use common\models\logic\PremiumsManager;
use common\models\UserLessons;
use yii\httpclient\Client;
use common\models\ActionTypes;
function getMoneyFormat($money){
    if ($money > 0){
        return '$'.$money;
    }else{
        return '0';
    }
}

$this->title = "Профиль пользователя";
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
if(Yii::$app->session->has('theme')){
    $currTheme = Yii::$app->session->get('theme');
}else{
    $currTheme = 0;
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
    $left_shoulder = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'shoulder'=>1])->count();
    $right_shoulder =\common\models\Referals::find()->where(['parent_id'=>$user['id'],'shoulder'=>2])->count();
    $personalMatrix = \common\models\MatrixRef::find()->where(['user_id'=>$user['id']])->orderBy('platform_id desc')->one();

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
$lastAddChildren = \common\models\User::find()->where(['parent_id'=>$user['id'],'activ'=>1])->orderBy('created_at desc')->limit(3)->all();

$username = Yii::$app->user->identity['username'];
if($activ){
    $url = 'http://'.$_SERVER['SERVER_NAME'].'/invite/'.$username;
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
$withdraws = \common\models\Actions::find()->where(['user_id'=>$user['id']])->orderBy('id desc')->limit(4)->all();
$premium = \common\models\Premiums::findOne(['user_id' => Yii::$app->user->id]);
$thisMonth = intval(date("m"));
$type = 'year';
$briTokens = \common\models\BriTokens::find()->where(['user_id' => $user->id])->one();
if($briTokens){
    $bri = $briTokens->balans;
}else{
    $bri = 0;
}
$grcTokens = \common\models\Tokens::find()->where(['user_id' => $user->id])->one();
if($grcTokens){
    $grc = $grcTokens->balans;
}else{
    $grc = 0;
}

//$this->registerJsFile('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js',['depends'=>'yii\web\JqueryAsset']);


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
$this->registerJsFile('/js/clipboard.js',['depends'=>'yii\web\JqueryAsset']);
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

function getWithdrawParam($withdraw){
    if($withdraw['type'] == 7 or $withdraw['type'] == 105){
        $curw = "PV";
    }else{
        $curw = "CV";
    }
    $sum = $withdraw['sum'];
    if($withdraw['type'] == 55 or $withdraw['type'] == 56 or $withdraw['type'] == 57 or $withdraw['type'] == 58 or $withdraw['type'] == 59 or $withdraw['type'] == 60 or $withdraw['type'] == 61 or $withdraw['type'] == 62 or $withdraw['type'] == 63){
        $curw = "GRC";
        $sum = $withdraw['tokens'];
    }
    $type = \common\models\ActionTypes::findOne($withdraw['type']);
    if($type['minus'] == 1){
        $img = "buy.svg";
    }elseif($type['id'] == 3){
        $img = "transfer.svg";
    }else{
        $img = "replenish.svg";
    }

    return [
        'curw'=> $curw,
        'sum' => $sum,
        'img' => $img
    ];

}
$this->registerJs('
    let referalLink = $("#referalLink").text();
    $("#referalCheckbox").click(function (){

        if ($(this).is(":checked")){
            $("#referalLink").text(referalLink+"/1")
        }else{
            $("#referalLink").text(referalLink)
        }
    })
');
$this->registerJs('

');
/*Доход структуры*/
if ($premium && $premium->is_active == 1){
    $children = \common\models\Referals::find()->select('user_id')->where(['parent_id'=>$user['id']])->all();
    $mapChildIds = [];
    foreach ($children as $child){
        array_push($mapChildIds, $child['user_id']);
    }
    $childrenDohodKurator = \common\models\Actions::find()->where(['user_id'=>$mapChildIds])->andWhere(['type'=> ActionTypes::KURORTNYI_BONUS])->sum('sum');;
    $childrenDohodBonus = \common\models\Actions::find()->where(['user_id'=>$mapChildIds])->andWhere(['type'=> ActionTypes::BONUS_ZA_POLZOVATELYA_SHANYRAK])->sum('sum');;
    $childrenDohod = $childrenDohodBonus + $childrenDohodKurator;
}

$showPremiumModal = 0;
if ($user){
    $premiumEvent = \common\models\PremiumEvent::find()->where(['user_id' => $user->id])->one();
    if ($premiumEvent && $premiumEvent->end_time > time() && $premium && $premium->tariff_id == 7){
        $showPremiumModal = 1;
    }
}
$jsModal = <<<JS
    $(document).ready(function(){
        let modal = $("#premiumModal");
        let isShow = $(modal).data("isshow");
        if(isShow == 1){
            $("#premiumModal").modal("show");   
        }
        $("#premiumModalClose").hide();
        setTimeout(function(){
            $("#premiumModalNext").hide();
            $("#premiumModalClose").show();
            
        }, 10000);
        
    });
    $("#premiumModalClose").click(function (){
        $("#premiumModal").modal("hide");  
    });
    $(document).mouseup(function (e){ // событие клика по веб-документу
        let div = $("#premiumModal .modal-body"); // тут указываем ID элемента
        if (!div.is(e.target) && div.has(e.target).length === 0) { 
            $("#premiumModal").modal("hide");            
        }
    });
JS;

$this->registerJs($jsModal);
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
    <style>
        .premiumModal p{
            color: #000;
        }
        .text-premium{
            padding-top: 45px;
        }
        .premium-text p{
            color: #fff;
        }
        .modal p{

        }
        .pt{
            margin: 20px auto;
        }
        .sell{
            position: absolute;
            left: -140px;
            top: -75px;
        }
        .arrow{
            position: absolute;
            left: -30px;
            top: -90px;
        }
        .sum-one{
            position: absolute;
            top: -100px;
            left: 143px;
            width: 100%;
        }
        .sum-two{
            width: 100%;
            margin-top: 20px;
            color: #FFD466;
            font-weight: 500;
            font-size: 24px;
        }
        @media (max-width: 575px){
            .premiumModal .close span{
                font-size: 24px;
            }
            .text-premium{
                padding-top: 55px;
            }
            .pt{
                margin-top: 5px;
            }
            .sell{
                left: -112px;
                top: -148px;
            }
            .arrow{
                left: -14px;
                top: -112px;
            }
            .sum-one{
                top: -65px;
                left: 86px;
                font-size: 17px;
            }
            .sum-one p{
                font-size: 17px!important;
            }
            .sum-two{
                font-size: 17px;
            }
        }
        }
    </style>
    <main class="main mt-5">
        <div class="container-fluid">
            <!--<div>
                <h1 class="h1 w5 text-left">Добро пожаловать в Shanyrak+</h1>
            </div>-->
            <div class="row justify-content-center">

                <div class="box__left col-lg-5 col-sm-11 col-xs-11 row" >
                <div class="info">
                    <span class="info__text w7">Статистика Баланса</span>
                    <span class="sum">PV <?=$statisticModel->dohodPeriod;?></span>
                </div>
                    <?if($premium && $premium->is_active == 1):?>
                        <div class="progress_block">
                            <figure id="one" class="bar-chart col-12">

                                <div class="row bars">
                                    <div class="y-axis">
                                        <div class="segment">
                                            <span class="label text__small">PV 5000</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">PV 1000</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">PV 500</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">PV 100</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label">0</span>
                                        </div>
                                    </div>
                                    <div class="x-axis">

                                        <?foreach ($statisticModel->statisticArray as $key => $value):?>
                                            <?if($type == 'year'):?>
                                                <?if ($key > $thisMonth){
                                                    break;
                                                }?>
                                            <?endif;?>
                                             <div class="year wrap" attr-type="<?="week";?>">
                                                <div class="col m-0">
                                                    <span class="bar" attr-money="<?=$value[1];?>"><div class="info_sum"></div></span>
                                                </div>
                                                <span class="label mt-1"><?=$value[0];?></span>
                                            </div>
                                        <?endforeach;?>
                                    </div>
                                </div>

                            </figure>
                        </div>
                    <?else:?>
                        <div class="text-left">
                            <div class="block__left-fon col-12">
                                <p class="w7 text-white">Приобретите Премиум аккаунт и откройте полный доступ ко всем новым возможностям Shanyrak+.</p>
                                <div class="mt-5">
                                    <button class="btn__yallow w7 text-dark">
                                        <img src="/img/main/coron.svg" alt="">
                                        Premium аккаунт
                                    </button>
									<a href="/premium"><button class="btn btn__blue w5 mt-3" >Узнать больше</button></a>
                                </div>
                            </div>
                        </div>
                    <?endif;?>


                    <ul class="main__left-list" style="width: 100%">
                        <li class="">
                            <p class="w5">Последние активности</p>
                        </li>
                        <?foreach ($withdraws as $withdraw):?>
                            <li class="main__left-item">
                                <div class="d-flex">
                                    <div class="img">
                                        <img src="/img/activity/<?=getWithdrawParam($withdraw)['img'];?>" alt="">
                                    </div>
                                    <div class="text__group">
                                        <span class="w5 text__small"><?=\common\models\ActionTypes::findOne($withdraw['type'])['title'];?></span>
                                        <span class="text__small">
                                            <?
                                            echo $withdraw['title'];
                                            if($withdraw['type'] == 3){
                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                            }elseif($withdraw['type'] == 4){
                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="w5 text__small">
                                        <?if(!empty(getWithdrawParam($withdraw)['sum'])):?>

                                            <?if(\common\models\ActionTypes::findOne($withdraw['type'])['minus'] == 1):?>
                                                <span>- <?=getWithdrawParam($withdraw)['sum']?> <?=getWithdrawParam($withdraw)['curw']?></span>
                                            <?else:?>
                                                <span> <?=getWithdrawParam($withdraw)['sum']?> <?=getWithdrawParam($withdraw)['curw']?></span>
                                            <?endif;?>


                                        <?endif;?>
                                    </span>
                                    <span class="text__small"><?=date("d.m.Y", $withdraw['time'])?></span>
                                </div>
                            </li>
                        <?endforeach;?>

                        <li>
                            <a href="/profile/actions" class="btn btn__blue" style="width: 100%;">Показать все</a>
                        </li>
                    </ul>

                </div>


                <div class="box__right col-lg-7 col-sm-11 col-xs-11">
                    <div class="block__right">
                        <div class="card__block">
                            <div class="left__fon mr-3">
                                <div class="block__top-one">
                                    <div class="">
                                        <img src="./img/main/bCV.svg" alt="">
                                        <span class="w5">Баланс</span>
                                    </div>
                                    <img src="./img/main/CV.svg" alt="">
                                </div>
                                <div class="block__center">
                                    <span class="h4 w7 mr-2">CV</span>
                                    <span class="h2 w7"><?=$user['w_balans']?></span>

                                </div>
                                <div class="block__center">
                                    <span class="h4 w7 mr-2">BRI</span>
                                    <span class="h2 w7"><?=$bri?></span>

                                </div>
                                <div class="block__center">
                                    <span class="h4 w7 mr-2">GRC</span>
                                    <span class="h2 w7"><?=$grc?></span>

                                </div>
                                <div class="block__bottom">
                                    <a href="/profile/perfect" class="">Купить CV</a>
                                    <?
                                    if($activ){?>
                                        <a href="/profile/transfer" class="">Перевести</a>
                                    <?}?>
                                </div>
                            </div>
							
							<div class="left__fon banner-pv">
                                <div class="block__top-one">
                                    <div class="">
                                        <img src="./img/main/bPV.svg" alt="">
                                        <span class="text-white w5">Агентский баланс</span>
                                    </div>
                                    <img src="./img/main/PV.svg" alt="">
                                </div>
                                <div class="block__center">
                                    <span class="h4 w7 text-white mr-2">PV</span>
                                    <span class="h2 w7 text-white"><?=$user['p_balans']?></span>
                                </div>
                                <div class="block__bottom">
                                    <?
                                    if($activ){?>
                                        <a href="/profile/withdraw" class="">Вывести</a>
                                        <a href="/profile/convert" class="">Конвертировать в CV</a>
                                    <?}?>
                                </div>
                            </div>
							
                            <!--<a class="bg_banner-besRoit card__block-item" style="display: block" href="https://besroit.com/ ">
                                <div class="shopRoit"></div>
                            </a>
                            <a class="bg_banner-greenSwop card__block-item" style="display:block;" href="https://greenswop.com/">
                                <div class="shopGreen"></div>
                            </a>-->
                        </div>


                        <div class="d-flex justify-content-between align-items-center my-4">
                            <span class="text__small text-white">Матрица</span>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn__blue btn__small" data-toggle="modal" data-target="#staticBackdrop">
                                Пригласить в матрицу
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <span class="modal-title w5" id="staticBackdropLabel">Поделиться реферальной ссылкой</span>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>

                                            <a class="referal-link" id="referalLink"><?=$url?></a>
                                            <div class="">
                                                <i class="fa fa-copy" aria-hidden="true"></i>
                                                <button class="btn btn-link" onclick="copy('referalLink')">Копировать</button>
                                            </div>
                                            </p>


                                            <div class="btn__group">
                                                <p class="w5 text-dark">Поделиться в:</p>
                                                <div class="ya-share2" data-title="Реферальная ссылка Shanyrakplus.com" data-url="<?=$url?>" data-services="vkontakte,facebook,twitter,viber,whatsapp,skype,telegram"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="card__block">
                            <div class="block__bottom-main">
                                <a href="/profile/structure?m=<?=$personalMatrix['platform_id'];?>" style="text-decoration: none;text-align: center;color: <?=($currTheme == 0)?'#000':'#fff';?> ">
                                    <?if(isset($personalMatrix)):?>
                                        <p class="text__middle text-center w7">Матрица №<?=$personalMatrix['platform_id'];?></p>
                                        <span class="text__middle text-center w2"><?=$personalMatrix['platform_id']?>/6 Стол</span>
                                    <?endif;?>
                                </a>
                            </div>
                            <div class="bg_banner-besRoit2 card__block-item2">
                                <h1 class="h1 text-white w7"><?=$left_shoulder;?></h1>
                                <span class="text-center">Левое плечо</span>
                            </div>
                            <div class="bg_banner-besRoit2 card__block-item2">
                                <h1 class="h1 text-white w7"><?=$right_shoulder;?></h1>
                                <span class="text-center">Правое плечо</span>
                            </div>
                        </div>

                        <ul class="bottom__list p-0">
                            <p class="text-white text__small">Последние присоединенные</p>
                            <?foreach ($lastAddChildren as $child):?>

                                <li class="bottom__item"><span><?=$child['username']?></span><span><?=$child['fio']?></span><span class="d-none d-md-block"><?=$child['phone'];?></span></li>
                            <?endforeach;?>

                        </ul>

                        <div class="d-grid gap-2 modale__body-button mt-3">
                            <a href="/profile/children" class="btn__blue btn btn__small" type="button" style="width: 100%;">Показать все</a>
                        </div>

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

    <div class="modal fade premiumModal" id="premiumModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-backdrop="static" data-isshow="<?=$showPremiumModal?>">
        <div class="modal-dialog" >
            <div class="modal-content" style="border-radius: 18px;">
                <div class="modal-body" id="slider" style="background: url(../img/modal/bg-main.jpg)no-repeat center center; border-radius: 16px;">

                    <span class="modal-title w5" id="staticBackdropLabel"></span>

                                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                   <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                   <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                   <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                   <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                                    </ol>
                    <div class="carousel-inner">

                        <div class="carousel-item active">
                            <div class="premium-img" style="width: 90%;
                              height: 200px;
                              background: url(../img/modal/one.svg)no-repeat center center;
                              background-size: contain;
                              margin: auto;
                              margin-bottom: 140px;
                              ">
                                <div class="d-flex text-premium" style="justify-content: center;">
                                    <img src="./img/modal/coro.svg" alt="">
                                    <h2 class="w7" style="margin:0 0 0 12px; color: #000;">Premium</h2>
                                </div>
                                <div class="pt" style="width: 70%; text-align: center;">
                                    <p>Только 4 дня скидка на Premium
                                        <span class="w5" style="color:#553153; font-weight: 700;">Успейте приобрести</span></p>
                                </div>
                            </div>

                            <div class="premium-content" style="width: 40%; margin: 0 auto; position:relative; margin-top: 115px;">
                                <div class="sell">
                                    <img src="./img/modal/sell.svg" alt="">
                                </div>
                                <div CLASS="arrow">
                                    <img src="./img/modal/arrow.svg" alt="">
                                </div>
                                <div class="sum-one">
                                </div>
                                <div  class="text-white w5" style="width: 250px; margin-bottom: 50px;">
                                    <a href="/premium" class="btn btn__blue" style="color:#fff; width: 100%;">Продлить со скидкой навсегда</a>
                                    <p class="sum-two" style="color:#FFD466;">100 CV НАВСЕГДА</p>
                                </div>
                            </div>
                        </div>

                                                   <div class="carousel-item">
                                                     <div class="premium-img">
                                                        <div style="display: flex;margin-bottom: 60px;justify-content: center;align-items: center; height: 100px;background: url(./img/modal/premium-t.svg)no-repeat center center; background-size: contain;">
                                                          <p>На <span style="color:#553153; font-weight: 700;">3 дня в Premium</span> вам доступно</p>
                                                        </div>
                                                      </div>

                                                      <div class="premium-content d-flex" style="height: 190px; margin: 30px auto; margin-bottom: 150px; align-items: center;">
                                                        <div class="img graphick">
                                                          <img src="./img/modal/premium-one.png" alt="" >
                                                        </div>
                                                        <div class="premium-text ml-4 my-4 text-white w5">
                                                          <p>Статистика Доходов – вы сможете отслеживать ежедневные показатели вашей прибыли и оценивать динамику их роста.</p>
                                                        </div>
                                                      </div>
                                                  </div>

                                                  <div class="carousel-item">
                                                    <div class="premium-img">
                                                      <div style="display: flex;margin-bottom: 60px;justify-content: center;align-items: center; height: 100px;background: url(./img/modal/premium-t.svg)no-repeat center center; background-size: contain;">
                                                        <p>На <span style="color:#553153; font-weight: 700;">3 дня в Premium</span> вам доступно</p>
                                                      </div>
                                                    </div>

                                                    <div class="premium-content d-flex" style="height: 190px; margin: 30px auto; margin-bottom: 150px; align-items: center;">
                                                      <div class="img graphick">
                                                        <img src="./img/modal/premium-two.svg" alt="" >
                                                      </div>
                                                      <div class="premium-text ml-4 my-4 text-white w5">
                                                        <p>В Статистике Матрицы – доступны показатели новых подключенных пользователей, а также динамичность их роста.</p>
                                                      </div>
                                                    </div>

                                                  </div>

                                                  <div class="carousel-item">
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true" class="text-white">x</span>
                                                      </button>
                                                    <div class="premium-img">
                                                      <div style="display: flex;margin-bottom: 60px;justify-content: center;align-items: center; height: 100px;background: url(./img/modal/premium-t.svg)no-repeat center center; background-size: contain;">
                                                        <p>На <span style="color:#553153; font-weight: 700;">3 дня в Premium</span> вам доступно</p>
                                                      </div>
                                                    </div>

                                                    <div class="premium-content d-flex" style="height: 190px; margin: 30px auto; margin-bottom: 150px; align-items: center;">
                                                      <div class="img graphick">
                                                        <img src="./img/modal/premium-three.svg" alt="" >
                                                      </div>
                                                      <div class="premium-text ml-4 my-4 text-white w5">
                                                        <p>В Статистике Структуры – отображены общие объемы заработка всей вашей структуры и непосредственно ваших личников.</p>
                                                      </div>
                                                    </div>

                                                  </div>

                    </div>

                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next" style="width: 25%;height: 87%;margin-top: 10%;align-items: flex-end; margin-bottom: 15px;">
                        <!--                    <span style="color:#FFD466;" id="premiumModalNext">Далее</span>-->
                        <span style="color:#FFD466;" id="premiumModalClose">Понятно</span>
                    </a>

                </div>
            </div>
        </div>
    </div>



<?
echo \frontend\components\LoginWidget::widget();
?>