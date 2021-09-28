<?

use common\models\MatrixRef;
use common\models\User;
use common\models\UserPlatforms;
if(Yii::$app->user->isGuest){
    $link = '/profile';
}else{
    //$user_lesson = \common\models\UserLessons::find()->where(['user_id'=>Yii::$app->user->id,'status'=>3])->orderBy('id desc')->one();
    $link = '/courses';
}

$menu = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;
if(Yii::$app->session->has('theme')){
    $currTheme = Yii::$app->session->get('theme');
}else{
    $currTheme = 0;
}
$user_db = User::findOne(Yii::$app->user->identity['id']);
$time = time();
$date = date('d.m.Y',$time);
$today = strtotime($date);
$yesterday = $today - 24*60*60;


$actions_today = \common\models\Actions::find()->where(['user_id'=>$user_db['id'],'view'=>2])->orWhere(['type'=>104,'status'=>1,'view'=>2])->andWhere(['>','time',$today])->orderBy('time desc')->all();
$actions_yesterday = \common\models\Actions::find()->where(['user_id'=>$user_db['id'],'view'=>2])->andWhere(['>','time',$yesterday])->orWhere(['type'=>104,'status'=>1,'view'=>2])->andWhere(['<','time',$today])->orderBy('time desc')->all();
$actions = \common\models\Actions::find()->where(['user_id'=>$user_db['id'],'view'=>2])->orWhere(['type'=>104,'status'=>1,'view'=>2])->andWhere(['<','time',$yesterday])->orderBy('time desc')->all();

$count = count($actions) + count($actions_yesterday) + count($actions_today);

$activ = null;
$refmat = null;
$global = null;
$start = null;
$is_agent = null;
$agent_status = null;
if(!empty($user_db)){
    if($user_db['activ'] == 1){
        $activ = true;
    }
    if($user_db['newmatrix'] == 1){
        $refmat = true;
    }
    if($user_db['global'] == 1){
        $global = true;
    }
    if($user_db['start'] == 1){
        $start = true;
    }
    if ($user_db['is_agent'] == 1){
        $is_agent = true;
        $agent_status = $user_db['agent_status'];
    }

}

$this->registerJs('
$("#closeBanner").click(function(e){
        $(".dop-panel").hide();
        $(".header-obs").removeClass("header-obs");
        
});
$(function(){
        var navbarOpen = true;
        $(".navbar-burger").click(function(e) {
            $(".navbar-layer").show();
            $(document).mouseup(function (e){ // событие клика по веб-документу
                let div = $(".navbar-side"); // тут указываем ID элемента
                if (!div.is(e.target) // если клик был не по нашему блоку
                    && div.has(e.target).length === 0) { // и не по его дочерним элементам
                    $(".navbar-layer").fadeOut(200); // скрываем его
                }
            });
        });
        $(".exit-btn").click(function(e) {
            $(".navbar-layer").fadeOut(100);
        });
    });
   
    
    $(function(){
        $("#exit-notification-mobile").click(function(e) {
            $(".account-notifications-block").fadeOut(200);
            $(".account-notifications-icon").removeClass("open"); 
        });
        
        $("#notification-cta").click(function(e) {
            
            $(".new-message").hide();
            $(".account-notifications-icon").addClass("open");
            $(".account-notifications-block").fadeIn(100);
                        
            let scrollMessage = document.getElementById("test");
            scrollMessage.scrollTop = scrollMessage.scrollHeight;

            $(document).mouseup(function (e){ // событие клика по веб-документу
                let div = $(".account-notifications-block"); // тут указываем ID элемента
                if (!div.is(e.target) // если клик был не по нашему блоку
                    && div.has(e.target).length === 0) { // и не по его дочерним элементам
                    div.fadeOut(200); // скрываем его
                    $(".account-notifications-icon").removeClass("open"); // скрываем его
                }
            });
        });
    }); 
');

$this->registerJsFile('/js/fa-fa.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/js/mobile.js',['depends'=>'yii\web\JqueryAsset']);

$user = User::findOne(['id' => Yii::$app->user->id]);
$is_insured = \common\models\Insurances::findOne(['user_id' => $user->id]) ? true : false;
$insurance_banner = \common\models\Insurances::findOne(['user_id' => $user->id]);
$showInsuranceBanner = true;
if(!empty($insurance_banner) && ($insurance_banner->status == \common\models\Insurances::STATUS_APPROVED || $insurance_banner->status == \common\models\Insurances::STATUS_MODERATION || $insurance_banner->status == \common\models\Insurances::STATUS_DECLINED)){
    $showInsuranceBanner = false;
}
if($user->time_personal < 1627776001){
    $showInsuranceBanner = false;
}
$premium = \common\models\Premiums::findOne(['user_id' => $user->id]);
$showBanner = false;
$expires_time = 0;
$day = 0;
$hour = 0;
if($premium){

    if($premium->is_active == 0){
        $expires = "Истек";
    } else if($premium->tariff_id == 6){
        $expires = "Навсегда";
    } else{
        $expires = "до: " . date("d.m.Y", $premium->time + $premium->expires_at);
    }
    if($premium->tariff_id == 7){
        $showBanner = true;
        $endTime = 1629093600;
        $expires_time = $endTime - time();
        $day = intval($expires_time / 24 / 3600);
        $hour = intval($expires_time / 3600 - $day * 24);
    }

}

$username = Yii::$app->user->identity['username'];

$urlGrc = 'https://walletgrc.com/tokens/get?promo='.$username."_shanyrak";




//$activ = 0;
?>
<style>

    .header-obs{
        margin-top: 50px;
    }
    .header-panel{
        padding: 7px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .text-otp{
        margin-right: 80px;
    }
    .dop-panel{
        width: 100%;
        padding-left: 100px;
        position: fixed;
        z-index: 2;
    }
    .header-panel p{
        width: 250px;
        display: flex;
        align-items: center;
        color: #fff;
        margin-bottom: 0;
        line-height: 16px;
    }
    .header-premium-btn{
        width: 162px;
        height: 40px;
        display: flex;
        align-items: center;
        margin-right: 1rem;
        background: #FFE7AA;
        border-radius: 8px;
        padding: 8px;
    }
    .bab{
        display: flex;
        align-items: center;
    }
    .bab i{
        min-width: 2.75rem;
        min-height: 2.75rem;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 1rem;
        color: #838383;
        font-size: 1.3rem;
    }
    #yt-widget.yt-state_mobile .yt-listbox__text{
        text-align: center;
    }
    header #yt-widget .yt-listbox {
        position: fixed;
    }
    @media (max-width: 900px){
        .text-otp{
        margin-right: 30px;
        }
    }
    @media (max-width: 575px){
        .dop-panel{
            padding-left: 0;
        }
    }
    @media (max-width: 665px){
        .dop-panel .btn{
            display: none;
        }
    }
    @media (max-width: 750px){
        .text-group-heder{
            flex-direction: column;
        }
    }
    @media (max-width: 710px){
        .yt-button_type_left{
            display: none!important;
        }
    }
    @media (max-width: 575px){
        #ytWidgetDesk{
            display: none;
        }
        .yt-button_type_left{
            display: inline-block!important;
        }
    }

</style>

<header class="header" style="margin-bottom: 70px">
    <?if($showInsuranceBanner){?>
        <div class="str-panel">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-9">
                        <div class="insurance_banner" >
                            <img src="../img/insurance.svg" alt="">
                            <div class=" block">
                                <div>
                                    <span class="btn__small">Страховка в подарок</span>
                                    <span class="yellow h6">на</span>
                                    <span class="yellow h4">1 год</span>
                                </div>
                                <a class="btn btn__blue" href="\profile\insurance">Подать заявку на страхование</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?}?>
    <nav class="navbar navbar-expand-lg" style="border: none">
        <div class="container">
            <div class="sidebar" style="transition: 0.3s; z-index: 3; <?=($showInsuranceBanner)?'top: 70px;':'top: 0px;'?>">
                <ul class="nav__list">
                    <li class="nav__list-item">
                        <a href="/profile" class="block__logo">
                            <div class="logo__content pr-0">
                                <img src="/img/logo__circle.svg" alt="">
                            </div>
                            <span class="nav__text-item mt-2"><img class="text__logo" src="/img/Shanyrak +.svg" alt=""></span>
                        </a>
                    </li>

                    <!-- ======== NAV ======== -->
                    <nav class="nav__body" style="height: auto;">
                        <ul class="nav__list mb-4">
                            <?if($activ){?>
                                <li class="nav__list-item">
                                    <a href="/profile" class="<?=(Yii::$app->requestedRoute=='profile')?'active':'';?>">
                                        <i class="fas fa-user icon__gray icon__normal"></i>
                                        <span class="nav__text-item">Профиль</span>
                                    </a>
                                </li>
                            <?}?>
<!--                            <li class="nav__list-item">-->
<!--                                <a href="/library" class="--><?//=(Yii::$app->requestedRoute=='library')?'active':'';?><!--">-->
<!--                                    <i class="fa fa-book icon__gray icon__normal"></i>-->
<!--                                    <span class="nav__text-item">Библиотека</span>-->
<!--                                </a>-->
<!--                            </li>-->
                            <?if($activ){?>
                                <li class="nav__list-item">
                                    <a href="/profile/structure" class="<?=(Yii::$app->requestedRoute=='profile/structure')?'active':'';?>">
                                        <i class="fas fa-network-wired icon__gray icon__normal"></i>
                                        <span class="nav__text-item">Матрица</span>
                                    </a>
                                </li>
                                <li class="nav__list-item">
                                    <a href="/profile/children" class="<?=(Yii::$app->requestedRoute=='profile/children')?'active':'';?>">
                                        <i class="fas fa-user-friends icon__gray icon__normal"></i>
                                        <span class="nav__text-item">Личники</span>
                                    </a>
                                </li>
                                <li class="nav__list-item">
                                    <a href="/profile/statistic" class="<?=(Yii::$app->requestedRoute=='profile/statistic')?'active':'';?>">
                                        <i class="fas fa-chart-bar icon__gray icon__normal"></i>
                                        <span class="nav__text-item">Статистика</span>
                                    </a>
                                </li>
                            <?}?>
                            <?if($is_agent && $agent_status == \backend\models\AgentForm::ACTIVE):?>
                            <li class="nav__list-item">
                                <a href="/profile/transfer" class="<?=(Yii::$app->requestedRoute=='profile/transfer')?'active':'';?>">
                                    <i class="fa fa-exchange" aria-hidden="true"></i>
                                    <span class="nav__text-item">Переводы</span>

                                </a>
                            </li>
                                <li class="nav__list-item">
                                    <a href="/profile/actions" class="<?=(Yii::$app->requestedRoute=='profile/actions')?'active':'';?>">
                                        <i class="fa fa-align-left" aria-hidden="true"></i>
                                        <span class="nav__text-item">Активности</span>

                                    </a>
                                </li>
                            <?endif;?>
                            <li class="nav__list-item">
                                <a href="/news" class="<?=(Yii::$app->requestedRoute=='news/index')?'active':'';?>">
                                    <i class="fas fa-newspaper icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Cобытия</span>
                                </a>
                            </li>
                            

                            <?if($activ){?>
                                <li class="nav__list-item">
                                    <a href="/" class="<?=(Yii::$app->requestedRoute=='courses')?'active':'';?>">
                                        <i class="fas fa-play-circle icon__gray icon__normal"></i>
                                        <span class="nav__text-item">Онлайн-обучение</span>
                                    </a>
                                </li>
                            <?}?>
                            <li class="nav__list-item">
                                <a href="/news" class="<?=(Yii::$app->requestedRoute=='news/index')?'active':'';?>">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="nav__text-item">Besroit</span>
                                </a>
                            </li>
                            <li class="nav__list-item">
                                <a href="/news" class="<?=(Yii::$app->requestedRoute=='news/index')?'active':'';?>">
                                    <i class="fa fa-buysellads"></i>
                                    <span class="nav__text-item">GreenSwop</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav__list">
                            <?if($activ){?>
                                <li class="nav__list-item">
                                    <a href="<?=$urlGrc;?>">
                                        <i class="fas fa-wallet icon__gray icon__normal"></i>
                                        <span class="nav__text-item">Кошелек</span>
                                    </a>
                                </li>
                            <?}?>
                            <li class="nav__list-item">
                                <a href="/profile/documents" class="<?=(Yii::$app->requestedRoute=='profile/documents')?'active':'';?>">
                                    <i class="fas fa-folder-open icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Документы</span>
                                </a>
                            </li>
                            <li class="nav__list-item">
                                <a href="/profile/tickets" class="<?=(Yii::$app->requestedRoute=='profile/tickets')?'active':'';?>">
                                    <i class="fas fa-cog icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Тех поддержка</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
            </div>

            <div class="main__block">
                <div class="top__bar">
                    <!-- ======== HEADER ======== -->
                    <div class="navbar-container">
                        <header class="<?=($showInsuranceBanner)?'header-obs':''?>" style="<?=($showInsuranceBanner)?'top: 20px;':'top: 0px;'?>">
                            <div class="bab">
                                <div id="ytWidgetDesk">

                                </div>
                            </div>

                            <div class="icons" >
                                <?if($premium):?>
                                    <div class="mr-4">
                                        <a data-method="POST" class="header__btn-icon" href="/profile/theme/<?=$currTheme;?>"><img src="<?=($currTheme==1)?'/img/sun.svg':'/img/moon.png';?>" alt=""/></a>
                                    </div>
                                <?endif;?>

                                <div class="account-notifications" style="padding: 0">
                                    <button class="account-notifications-icon header__btn-icon" style="background: #5460F5" id="notification-cta">
                                        <i class="fas fa-bell icon__white icon__normal"></i>
                                        <?if($count>0){?>
                                            <div class="new-message small"><?=$count?></div>
                                        <?}?>

                                        </svg>
                                    </button>
                                    <div class="account-notifications-block">
                                        <div class="exit-block">
                                            <button class="exit-btn" id="exit-notification-mobile"></button>
                                        </div>
                                        <div class="notifications-body" id="test">
                                            <?if($count>0){?>
                                                <?if(!empty($actions)){?>
                                                    <? foreach ($actions as $action) {
                                                        $type = \common\models\ActionTypes::findOne($action['type']);
                                                        ?>
                                                        <div class="notifications-item <?if($type['minus'] == 1){echo "from";}else{echo "to";}?>">
                                                            <div class="message">
                                                                <div class="message-time">
                                                                    <span class="time"><?=date('d.m.Y H:i',$action['time'])?></span>
                                                                </div>
                                                                <div class="message-content">
                                                                    <div class="message-block-body">
                                                                        <p>
                                                                            <?
                                                                            echo $action['title'];
                                                                            if($action['type'] == 3){
                                                                                echo " пользователю ".\common\models\User::findOne($action['user2_id'])['username'];
                                                                            }elseif($action['type'] == 4){
                                                                                echo " от пользователя ".\common\models\User::findOne($action['user2_id'])['username'];
                                                                            }
                                                                            ?>

                                                                            <?
                                                                            if(!empty($action['sum'])){?>
                                                                                <br>Сумма:
                                                                                <? if($type['minus'] == 1){?>
                                                                                    <span style="color: red">- <?=$action['sum']?></span>
                                                                                <?}else{?>
                                                                                    <span style="color: green"> <?=$action['sum']?></span>
                                                                                <?}}?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="message-block-footer">
                                                                        <a href="<?=$action['comment']?>" class="btn btn-link p-0">Подробнее</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?
                                                        $action->view = 1;
                                                        $action->save();
                                                    }?>
                                                <?}?>
                                                <?if(!empty($actions_yesterday)){?>
                                                    <div class="notifications-item title">
                                                        <span>Вчера</span>
                                                    </div>
                                                    <? foreach ($actions_yesterday as $action) {
                                                        $type = \common\models\ActionTypes::findOne($action['type']);
                                                        ?>
                                                        <div class="notifications-item <?if($type['minus'] == 1){echo "from";}else{echo "to";}?>">
                                                            <div class="message">
                                                                <div class="message-time">
                                                                    <span class="time"><?=date('H:i',$action['time'])?></span>
                                                                </div>
                                                                <div class="message-content">
                                                                    <div class="message-block-body">
                                                                        <p>
                                                                            <?
                                                                            echo $action['title'];
                                                                            if($action['type'] == 3){
                                                                                echo " пользователю ".\common\models\User::findOne($action['user2_id'])['username'];
                                                                            }elseif($action['type'] == 4){
                                                                                echo " от пользователя ".\common\models\User::findOne($action['user2_id'])['username'];
                                                                            }
                                                                            ?>

                                                                            <?
                                                                            if(!empty($action['sum'])){?>
                                                                                <br>Сумма:
                                                                                <? if($type['minus'] == 1){?>
                                                                                    <span style="color: red">- <?=$action['sum']?></span>
                                                                                <?}else{?>
                                                                                    <span style="color: green"> <?=$action['sum']?></span>
                                                                                <?}}?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="message-block-footer">
                                                                        <a href="" class="btn btn-link p-0">Подробнее</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?
                                                        $action->view = 1;
                                                        $action->save();
                                                    }?>
                                                <?}?>
                                                <?if(!empty($actions_today)){?>
                                                    <div class="notifications-item title">
                                                        <span>Сегодня</span>
                                                    </div>
                                                    <? foreach ($actions_today as $action) {
                                                        $type = \common\models\ActionTypes::findOne($action['type']);
                                                        ?>
                                                        <div class="notifications-item <?if($type['minus'] == 1){echo "from";}else{echo "to";}?>">
                                                            <div class="message">
                                                                <div class="message-time">
                                                                    <span class="time"><?=date('H:i',$action['time'])?></span>
                                                                </div>
                                                                <div class="message-content">
                                                                    <div class="message-block-body">
                                                                        <p>
                                                                            <?
                                                                            echo $action['title'];
                                                                            if($action['type'] == 3){
                                                                                echo " пользователю ".\common\models\User::findOne($action['user2_id'])['username'];
                                                                            }elseif($action['type'] == 4){
                                                                                echo " от пользователя ".\common\models\User::findOne($action['user2_id'])['username'];
                                                                            }
                                                                            ?>

                                                                            <?
                                                                            if(!empty($action['sum'])){?>
                                                                                <br>Сумма:
                                                                                <? if($type['minus'] == 1){?>
                                                                                    <span style="color: red">- <?=$action['sum']?></span>
                                                                                <?}else{?>
                                                                                    <span style="color: green"> <?=$action['sum']?></span>
                                                                                <?}}?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="message-block-footer">
                                                                        <a href="" class="btn btn-link p-0">Подробнее</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?
                                                        $action->view = 1;
                                                        $action->save();
                                                    }?>
                                                <?}?>
                                            <?}else{?>
                                                <div class="notifications-item justify-content-center">
                                                    <p class="lead ">
                                                        У вас нет новых уведомлений
                                                    </p>
                                                </div>
                                            <?}?>
                                        </div>
                                        <div class="notifications-footer">
                                            <a href="/profile/actions" class="lead">Посмотреть все</a>
                                        </div>
                                    </div>

                                </div>
                                <a href="/profile/settings">
                                    <?if($premium && $premium->img_source != null):?>
                                        <button class="header__btn-icon" style="background:url('<?=$premium->img_source?>') no-repeat;background-size: cover">
                                        </button>
                                    <?else:?>
                                        <button class="header__btn-icon">
                                            <i class="fas fa-user icon__white icon__normal"></i>
                                        </button>
                                    <?endif;?>
                                </a>
                            </div>
                            <a href="/profile/settings" style="text-decoration: none">
                                <div class="header__field-user">
                                    <p><?=$user->username?></p>
                                    <?if($premium){?>
                                        <small style="color: #ECE000">Premium: <?=$expires?></small>
                                        <?if($premium->tariff_id != 6){?>
                                            <a style="color: #ECE000" href="/premium">Продлить</a>
                                        <?}?>
                                    <?}else{?>
                                        <small>Базовый аккаунт</small>
                                    <?}?>
                                </div>
                            </a>
                            <a href="/site/logout" data-method="POST" style="text-decoration: none">
                                <div class="">
                                    <img class="mr-3" src="/img/header_exit.svg" alt="">
                                </div>
                            </a>
                            <!--                            --><?php //echo \yii\helpers\Html::a('Выйти','/site/logout',['data-method'=>'POST','class'=>'dropdown-item']); ?>
                        </header>

                        <!-- ======= NAV_BOTTOM ====== -->
                        <div class="nav__bottom ">
                            <nav class="nav__bottom-body">
                                <ul class="nav__bottom-list ">
                                    <li class="nav__list-item">
                                        <a href="/profile" class="<?=(Yii::$app->requestedRoute=='profile')?'active':'';?>">
                                            <i class="fas fa-user icon__gray icon__normal"></i>
                                        </a>
                                    </li>
                                    <?if($activ):?>
                                        <li class="nav__list-item">
                                            <a href="/profile/structure" class="<?=(Yii::$app->requestedRoute=='profile/structure')?'active':'';?>">
                                                <i class="fas fa-network-wired"></i>
                                            </a>
                                        </li>

                                        <li class="nav__list-item">
                                            <a href="/profile/children" class="<?=(Yii::$app->requestedRoute=='profile/children')?'active':'';?>">
                                                <i class="fas fa-user-friends"></i>
                                            </a>
                                        </li>
                                        <li class="nav__list-item">
                                            <a href="/profile/statistic" class="<?=(Yii::$app->requestedRoute=='profile/statistic')?'active':'';?>">
                                                <i class="fas fa-chart-bar"></i>
                                            </a>
                                        </li>
                                    <?endif;?>
                                    <ul class="block__icon nav__list" >
<!--                                        <li class="nav__list-item">-->
<!--                                            <a href="/library" class="--><?//=(Yii::$app->requestedRoute=='library')?'active':'';?><!--">-->
<!--                                                <i class="fas fa-book icon__gray icon__normal"></i>-->
<!--                                                <span class="nav__text-item">Библиотека</span>-->
<!--                                            </a>-->
<!--                                        </li>-->
                                        <li class="nav__list-item">
                                            <a href="/news" class="<?=(Yii::$app->requestedRoute=='news')?'active':'';?>">
                                                <i class="fas fa-newspaper"></i>
                                                <span class="nav__text-item">События</span>
                                            </a>
                                        </li>
                                        <?if($activ):?>
                                            <li class="nav__list-item">
                                                <a href="">
                                                    <i class="fas fa-wallet icon__gray icon__normal"></i>
                                                    <span class="nav__text-item">Кошелек</span>
                                                </a>
                                            </li>
                                        <?endif;?>
                                        <li class="nav__list-item">
                                            <a href="/profile/documents" class="<?=(Yii::$app->requestedRoute=='profile/documents')?'active':'';?>">
                                                <i class="fas fa-folder-open"></i>
                                                <span class="nav__text-item">Документы</span>
                                            </a>
                                        </li>
                                        <li class="nav__list-item">
                                            <a href="/profile/tickets" class="<?=(Yii::$app->requestedRoute=='profile/tickets')?'active':'';?>">
                                                <i class="fas fa-cog icon__gray icon__normal"></i>
                                                <span class="nav__text-item">Тех поддержка</span>
                                            </a>
                                        </li>
                                        <?if($activ){?>
                                            <li class="nav__list-item">
                                                <a href="/" class="<?=(Yii::$app->requestedRoute=='courses')?'active':'';?>">
                                                    <i class="fas fa-play-circle icon__gray icon__normal"></i>
                                                    <span class="nav__text-item">Онлайн-обучение</span>
                                                </a>
                                            </li>
                                        <?}?>
                                        <li class="nav__list-item">
                                            <div class="bab">
                                                <i class="fas fa-globe"></i>
                                                <div id="ytWidgetDeskMobile">

                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <li class="nav__list-item focus" attr-show = "0">
                                        <a href="" data-toggle="tab">
                                            <i class="fas fa-th"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </nav>
</header>