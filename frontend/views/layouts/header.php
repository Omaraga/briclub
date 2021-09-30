<?
use common\models\MatrixRef;
use common\models\User;
use common\models\UserPlatforms;

$menu = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;
$user = User::findOne(Yii::$app->user->identity['id']);

$activated = false;
if ($user->activ == 1){
    $activated = true;
}
$this->registerJsFile('/js/mobile.js',['depends'=>'yii\web\JqueryAsset']);
?>
<header class="between">
    <div class="logo">
        <img src="/img/logo.svg" alt="">
    </div>
    <div class="top center">
        <div class="top-item center me-3"><img src="/img/setting.svg" alt=""></div>
        <div class="top-item center"><img src="/img/bell.svg" alt=""></div>
    </div>
</header>
<div class="d-flex">
    <div class="sidebar txt-white d-none d-lg-block">
        <div class="user-block">
            <img class="user-img" src="img/user.svg" alt="фото пользователя">
            <h5 class="w7"><?=$user->fio;?></h5>
            <h5 class="w4"><?=$user->email;?></h5>
        </div>
        <nav>
            <ul class="list">
                <li class="list-item">
                    <a class="list-link" href="/">
                        <img src="/img/sidebar/house.svg" alt="">
                        <h6>Профиль</h6>
                    </a>
                    <div class="list-item-line"></div>
                </li>
                <li class="list-item">
                    <a class="list-link" href="#">
                        <img src="/img/sidebar/house.svg" alt="">
                        <h6>Портфель</h6>
                    </a>
                    <div class="list-item-line"></div>
                </li>
                <li class="list-item">
                    <a class="list-link" href="#">
                        <img src="./img/sidebar/house.svg" alt="">
                        <h6>Подарки</h6>
                    </a>
                    <div class="list-item-line"></div>
                </li>
                <li class="list-item">
                    <a class="list-link" href="#">
                        <img src="./img/sidebar/house.svg" alt="">
                        <h6>Активность</h6>

                    </a>
                    <div class="list-item-line"></div>
                </li>
                <li class="list-item">
                    <a class="list-link" href="#">
                        <img src="./img/sidebar/house.svg" alt="">
                        <h6>Матрица</h6>

                    </a>
                    <div class="list-item-line"></div>
                </li>
                <li class="list-item">
                    <a class="list-link" href="#">
                        <img src="./img/sidebar/house.svg" alt="">
                        <h6>Личники</h6>

                    </a>
                    <div class="list-item-line"></div>
                </li>
                <li class="list-item">
                    <a class="list-link" href="#">
                        <img src="./img/sidebar/house.svg" alt="">
                        <h6>Статистика</h6>

                    </a>
                    <div class="list-item-line"></div>
                </li>
                <li class="list-item">
                    <a class="list-link" href="#">
                        <img src="./img/sidebar/house.svg" alt="">
                        <h6>Мероприятия</h6>

                    </a>
                    <div class="list-item-line"></div>
                </li>
                <li class="list-item">
                    <a class="list-link" href="#">
                        <img src="./img/sidebar/house.svg" alt="">
                        <h6>Новости</h6>

                    </a>
                    <div class="list-item-line"></div>
                </li>
                <li class="list-item">
                    <a class="list-link" href="#">
                        <img src="./img/sidebar/house.svg" alt="">
                        <h6>Документы</h6>

                    </a>
                    <div class="list-item-line"></div>
                </li>
                <li class="list-item">
                    <a class="list-link" href="#">
                        <img src="./img/sidebar/house.svg" alt="">
                        <h6>Тех поддержка</h6>

                    </a>
                    <div class="list-item-line"></div>
                </li>
            </ul>
        </nav>
    </div>