<?php
/* @var $this yii\web\View*/
/* @var $userParent common\models\User*/
/* @var $this yii\web\View*/

$this->registerJsFile('https://translate.yandex.net/website-widget/v1/widget.js?widgetId=ytWidgetDesk&pageLang=ru&widgetTheme=dark&autoMode=false');
?>
<main class="center invate fon-page">
    <div class="invate-block">
        <h6 class="mb-2">Вас приглашает</h6>
        <h2 class="w3 margin-bot-50"><?=$userParent['fio'];?></h2>
        <hr class="margin-bot-50">
        <h4 class="w3 margin-bot-32">в закрытый <span class="w5">Бизнес - клуб </span> премиального уровня</h4>
        <img src="/img/invate/logo.svg" alt="" class="invate-logo margin-bot-50">
        <div>
            <a href="/site/signup?referal=<?=$userParent['username'];?>" class="invate-btn fon-green-500">Зарегистрироваться</a>
        </div>
    </div>
</main>
<img src="/img/invate/main-img-bottom.svg" alt="" class="img-bottom">
<img src="/img/invate/main-img-top.svg" alt="" class="img-top">


