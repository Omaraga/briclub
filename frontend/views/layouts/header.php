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

$menuItems = [];
array_push($menuItems, ['name' => 'Профиль', 'icon' => '<i class="fa fa-home" aria-hidden="true"></i>', 'url' => '/', 'access' => false]);
array_push($menuItems, ['name' => 'Портфель', 'icon' => '<i class="fa fa-home" aria-hidden="true"></i>', 'url' => '/', 'access' => true]);
array_push($menuItems, ['name' => 'Подарки', 'icon' => '<i></i>', 'url' => '/', 'access' => true]);
array_push($menuItems, ['name' => 'Активность', 'icon' => '<i></i>', 'url' => '/', 'access' => true]);
array_push($menuItems, ['name' => 'Система', 'icon' => '<i></i>', 'url' => '/', 'access' => true]);
array_push($menuItems, ['name' => 'Основатели', 'icon' => '<i></i>', 'url' => '/', 'access' => true]);
array_push($menuItems, ['name' => 'Статистика', 'icon' => '<i></i>', 'url' => '/', 'access' => true]);
array_push($menuItems, ['name' => 'Мероприятия', 'icon' => '<i></i>', 'url' => '/', 'access' => false]);
array_push($menuItems, ['name' => 'Документы', 'icon' => '<i></i>', 'url' => '/', 'access' => false]);
array_push($menuItems, ['name' => 'Техподдержка', 'icon' => '<i></i>', 'url' => '/', 'access' => false]);
$this->registerJsFile('/js/mobile.js',['depends'=>'yii\web\JqueryAsset']);
?>
<header class="between">
    <div class="logo">
        <a href="/"><img src="/img/logo.svg" alt=""></a>
    </div>
    <div class="top center">
        <div class="top-item center mr-3"><img src="/img/setting.svg" alt=""></div>
        <div class="top-item center mr-3"><img src="/img/bell.svg" alt=""></div>
        <div class="top-item center">
            <form action="/site/logout" method="post">
                <button type="submit"><i class="fa fa-sign-out" aria-hidden="true"></i></button>
            </form>

        </div>
    </div>
</header>
<div class="d-flex">
    <div class="sidebar txt-white d-none d-lg-block">
        <div class="user-block">
            <div class="user-img center">
                <h3 class="w7"><?=mb_substr($user['firstname'],0 , 1)?></h3>
                <h3 class="w7"><?=mb_substr($user['lastname'], 0, 1)?></h3>
            </div>
            <h5 class="w7"><?=$user->fio;?></h5>
            <h5 class="w4"><?=$user->email;?></h5>
        </div>
        <nav>
            <ul class="list">
                <?foreach ($menuItems as $item):?>
                <?if(!$item['access'] || ($activated)):?>
                    <li class="list-item">
                        <a class="list-link" href="<?=$item['url'];?>">
                            <?=$item['icon'];?>
                            <h6><?=$item['name'];?></h6>
                        </a>
                        <div class="list-item-line"></div>
                    </li>
                <?endif;?>
                <?endforeach;?>
            </ul>
        </nav>
    </div>