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
}

$this->registerJs('
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

$user = User::findOne(['id' => Yii::$app->user->id]);

$premium = \common\models\Premiums::findOne(['user_id' => $user->id]);
 ?>
<header class="header" style="background: transparent">
    <nav class="navbar navbar-expand-lg" style="border: none">
        <div class="container">

            <div class="sidebar" style="transition: 0.2s">
                <ul class="nav__list">
                    <li class="nav__list-item">
                        <a href="" class="block__logo">
                            <div class="logo__content pr-0">
                                <img src="./img/logo__circle.svg" alt="">
                            </div>
                            <span class="nav__text-item mt-1"><img class="text__logo" src="./img/Shanyrak +.svg" alt=""></span>
                        </a>
                    </li>

                    <!-- ======== NAV ======== -->
                    <nav class="nav__body">
                        <ul class="nav__list">
                            <li class="nav__list-item">
                                <a href="">
                                    <i class="fas fa-user icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Профиль</span>
                                </a>
                            </li>
                            <li class="nav__list-item">
                                <a href="">
                                    <i class="fas fa-play-circle icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Онлайн обучение</span>
                                </a>
                            </li>
                            <li class="nav__list-item">
                                <a href="">
                                    </i><i class="fas fa-newspaper icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Cобытия</span>
                                </a>
                            </li>
                            <li class="nav__list-item">
                                <a href="">
                                    <i class="fas fa-user icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Документация/Промо</span>
                                </a>
                            </li>
                            <li class="nav__list-item">
                                <a href="">
                                    <i class="fas fa-user icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Документация/Промо</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav__list">
                            <li class="nav__list-item">
                                <a href="">
                                    <i class="fas fa-wallet icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Кошелек</span>
                                </a>
                            </li>
                            <li class="nav__list-item">
                                <a href="">
                                    <i class="fas fa-folder-open icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Документы/Промо</span>
                                </a>
                            </li>
                            <li class="nav__list-item">
                                <a href="">
                                    <i class="fas fa-cog icon__gray icon__normal"></i>
                                    <span class="nav__text-item">Настройки</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
            </div>

            <div class="main__block">
                <div class="top__bar">
                    <!-- ======== HEADER ======== -->
                    <div class="navbar-container">
                        <header>
                            <div class="icons">
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
                                    <button class="header__btn-icon"><i class="fas fa-user icon__white icon__normal"></i></button>
                                </a>
                            </div>
                            <a href="/profile/settings" style="text-decoration: none">
                                <div class="header__field-user">
                                    <p><?=$user->username?></p>
                                    <?if($premium){?>
                                        <small style="color: #ECE000">Premium</small>
                                    <?}else{?>
                                        <small>Базовый аккаунт</small>
                                    <?}?>
                                </div>
                            </a>
<!--                            --><?php //echo \yii\helpers\Html::a('Выйти','/site/logout',['data-method'=>'POST','class'=>'dropdown-item']); ?>
                        </header>
                    </div>
                </div>
<!--            <div class="navbar-layer active">-->
<!--                <aside class="navbar-side">-->
<!--                    <section class="navbar-main-content">-->
<!--                        <div class="navbar-side-header">-->
<!--                            <div class="exit-block">-->
<!--                                <button class="exit-btn"></button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="navbar-side-body">-->
<!--                            <ul class="navbar-nav">-->
<!--                                <li class="nav-item --><?//if($menu == 'index' and $controller == 'profile'){echo "active";}?><!--">-->
<!--                                    <a class="nav-link" href="/profile">-->
<!--                                        <img src="/img/nav/user.svg" alt="">-->
<!--                                        Профиль-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                --><?//if($activ){?>
<!--                                <li class="nav-item --><?//if($menu == 'library'){echo "active";}?><!--">-->
<!--                                    <a class="nav-link" href="/library">-->
<!--                                        <img src="/img/nav/open-book.svg" alt="">-->
<!--                                        Библиотека-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                    <li class="nav-item">-->
<!--                                        <img src="/img/nav/shopping-cart.svg" alt="">-->
<!--                                        <a class="nav-link" href="https://besroit.com">Интернет магазин</a>-->
<!--                                    </li>-->
<!--								<li class="nav-item">-->
<!--                                        <img src="/img/nav/advertisement.svg" alt="">-->
<!--                                        <a class="nav-link" href="https://greenswop.com">Доска объявлений</a>-->
<!--                                    </li>-->
<!--                                <li class="nav-item --><?//if( $menu == 'structure' or $menu == 'children' or $menu == 'start' or $menu == 'global'){echo "active";}?><!--">-->
<!--                                <span class="nav-link">-->
<!--                                    <img src="/img/nav/people.svg" alt="">-->
<!--                                    Партнерская программа-->
<!--                                </span>-->
<!--                                    <ul class="submenu list-unstyled">-->
<!--                                        <li class="sub-item lead"><a class="sub-link" href="/profile/structure">Shanyrak+</a></li>-->
<!--                                        <li class="sub-item lead"><a class="sub-link" href="/profile/children">My referrals</a></li>-->
<!--                                    </ul>-->
<!---->
<!--                                </li>-->
<!--                                <li class="nav-item --><?//if($menu == 'actions'){echo "active";}?><!--">-->
<!--                                    <a class="nav-link" href="/profile/actions">-->
<!--                                        <img src="/img/nav/exchange.svg" alt="">-->
<!--                                        Активность-->
<!--                                    </a>-->
<!--                                    <ul class="submenu list-unstyled">-->
<!--                                        <li class="sub-item lead"><a class="sub-link" href="/news">-->
<!--                                                Новости</a>-->
<!--                                        </li>-->
<!--                                    </ul>-->
<!--                                </li>-->
<!--                                --><?//}?>
<!--								<li class="nav-item --><?//if($menu == 'events'){echo "active";}?><!--">-->
<!--                                    <img src="/img/nav/event.svg" alt="">-->
<!--									<a class="nav-link" href="/events">Мероприятия</a>-->
<!--								</li>-->
<!--                                <li class="nav-item --><?//if($menu == 'documents'){echo "active";}?><!--">-->
<!--                                    <a class="nav-link" href="/profile/documents">-->
<!--                                        <img src="/img/nav/file.svg" alt="">-->
<!--                                        Документы-->
<!--                                    </a>-->
<!--                                    <ul class="submenu list-unstyled">-->
<!--                                        <li class="sub-item lead"><a class="sub-link" href="/profile/documents?type=promo">Промо материалы</a></li>-->
<!--                                    </ul>-->
<!--                                </li>-->
<!--                                <li class="nav-item --><?//if($menu == 'tickets'){echo "active";}?><!--">-->
<!--                                    <a class="nav-link" href="/profile/tickets">-->
<!--                                        <img src="/img/nav/technical-support.svg" alt="">-->
<!--                                        Тех поддержка-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item --><?//if($menu == 'user-bills'){echo "active";}?><!--">-->
<!--                                    <a class="nav-link" href="/profile/user-bills">-->
<!--                                        <img src="/img/nav/credit-card.svg" alt="">-->
<!--                                        Ваши счета-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </section>-->
<!---->
<!--                    <div class="navbar-side-footer">-->
<!--                        <div class="coursmoney">-->
<!--                            <p class="h5">Курс валют:</p>-->
<!--                            <ul class="coursmoney-list list-unstyled">-->
<!--                                --><?//
//                                    $tg = \common\models\Changes::findOne(1)['cur2'];
//                                    $rub = \common\models\Changes::findOne(2)['cur2'];
//                                ?>
<!--                                <li><span class="flag dollar">us</span><span class="money">1 US</span> <span class="smooth">=</span> <span class="flag tenge">&#8376;</span><span class="money">--><?//=$tg?><!-- KZT</span></li>-->
<!--                                <li><span class="flag dollar">us</span><span class="money">1 US</span> <span class="smooth">=</span> <span class="flag ruble">&#8381;</span><span class="money">--><?//=$rub?><!-- RUB</span></li>-->
<!--                                                                    <li><span class="flag dollar">&#36;</span><span class="money">1 USD</span> <span class="smooth">=</span> <span class="flag tenge">&#8376;</span><span class="money">417.4 KZT</span></li>-->-->
<!--                                                                    <li><span class="flag euro">&#128;</span><span class="money">1 EUR</span> <span class="smooth">=</span> <span class="flag tenge">&#8376;</span><span class="money">460 KZT</span></li>-->-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                        <div id="ytWidgetMobile"></div><script src="https://translate.yandex.net/website-widget/v1/widget.js?widgetId=ytWidgetMobile&pageLang=ru&widgetTheme=light&autoMode=false" type="text/javascript"></script>-->
<!---->
<!--                    </div>-->
<!--                </aside>-->
<!--            </div>-->

            </div>
        </div>
    </nav>
</header>