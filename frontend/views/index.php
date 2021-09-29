<div class="them dark light">
<?php

/* @var $this yii\web\View */
/* @var $statisticModel \frontend\models\StatisticModel */
use common\models\Lessons;
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
    $curw = "CV";
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
    <main class="main">
        <div class="container-fluid">
            <div class="">
                <h1 class="h1 w5 text-left">Добро пожаловать в Shanyrak+</h1>

            </div>
            <div class="row justify-content-center">

                <div class="box__left col-lg-5 col-sm-11 col-xs-11 row" >
                <div class="info">
                    <span class="info__text w7">Статистика Баланса</span>
                    <span class="sum">CV <?=$statisticModel->dohodPeriod;?></span>
                </div>
                    <?if($premium && $premium->is_active == 1):?>
                        <div class="progress_block">
                            <figure id="one" class="bar-chart col-12">

                                <div class="row bars">
                                    <div class="y-axis">
                                        <div class="segment">
                                            <span class="label text__small">CV 5000</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">CV 1000</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">CV 500</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">CV 100</span>
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
                            <div class="left__fon">
                                <div class="block__top-one">
                                    <div class="">
                                        <img src="/img/main/dollar.svg" alt="">
                                        <span class="text-white">Баланс</span>
                                    </div>
                                </div>
                                <div class="block__center">
                                    <span class="h2 w7 text-white">CV</span>
                                    <span class="h1 w7 text-white"><?=$user['w_balans']?></span>
                                </div>
                                <div class="block__bottom">
                                    <a href="/profile/perfect" class="">Купить CV</a>
                                    <?
                                    if($activ){?>
                                        <a href="/profile/withdraw" class="">Вывести</a>
                                        <a href="/profile/transfer" class="">Перевести</a>
                                    <?}?>
                                </div>
                            </div>
                            <a class="bg_banner-besRoit card__block-item" style="display: block" href="https://besroit.com/ ">
                                <div class="shopRoit"></div>
                            </a>
                            <a class="bg_banner-greenSwop card__block-item" style="display:block;" href="https://greenswop.com/">
                                <div class="shopGreen"></div>
                            </a>
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

                                            <div style="background: #FFD466; border-radius: 8px; margin: 2rem 0;">
                                                <div class="block__top-yallow" >
                                                    <img src="/img/main/Vector.svg" alt="">
                                                    <span class="w7 ml-2">Доступно для Premium аккаунта</span></div>
                                                <div class="block__yallow">
                                                    <input class="mr-2 main__input" type="checkbox" id="referalCheckbox" <?=($premium && $premium->is_active == 1)?'':'disabled';?>>
                                                    <span>Поделиться статистикой доходов</span>
                                                </div>
                                            </div>

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
</div>


<?
echo \frontend\components\LoginWidget::widget();
?>