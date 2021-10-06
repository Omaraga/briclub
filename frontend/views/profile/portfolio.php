<?php
use common\models\User;
$this->title = "Портфель";
$sum = (10*$grc) + (2*$bri);
?>
<main class="profil">
    <h4 class="w7 mb-4">Портфель</h4>

    <div class="cards between portfolio-block">
        <div class="rows">
            <h6>Общий капитал с акции</h6>
            <h3 class="w7">$ <?=$sum?></h3>
        </div>
        <img src="/img/profile/main-img.svg" alt="">
    </div>

    <h6 class="mb-2">Доступные акции</h6>
    <div class="rows">
        <div class="line around mb-3 portfolio-block">
            <div class="flex-line">
                <img src="/img/profile/bri_logo.svg" alt="">
                <h5 class="w7 ml-2">BRI</h5>
            </div>
            <p class="txt-mini">1 BRI = 2 $</p>
            <h5 class="w7"><?=$bri?> BRI <span class="w4 txt-blue-100">($ <?=(2*$bri)?>)</span></h5>
        </div>
        <div class="line around portfolio-block">
            <div class="flex-line">
                <img src="/img/profile/grc_logo.svg" alt="">
                <h5 class="w7 ml-2">GRC</h5>
            </div>
            <p class="txt-mini">1 GRC = 10 $</p>
            <h5 class="w7"><?=$grc?> BRI <span class="w4 txt-blue-100">($ <?=(10*$grc)?>)</span></h5>
        </div>
    </div>

    <div class="between">
        <h6 class="mb-2" style="margin-top: 80px;">Доступные акции</h6>
    </div>
    <?if($rank['fund']==0):?>
    <div class="line around">
        <?if($rank->id>0):?>
            <p class="txt-mini">Дивиденды доступно в титуле <span class="ml-3 txt-green-100"><?=$rank['title']?></span></p>
            <h6 class="txt-gray-100">$ <?=$rank['dividends']?> в год</h6>
        <?else:?>
            <p class="txt-mini">Дивиденд не доступен</p>
        <?endif;?>
    </div>
    <?else:?>

    <? $i=['01','02','03','04','05','06','07','08','09','10','11','12']; ?>
    <div class="between block">
    <?foreach ($i as $m):?>

        <div class="banner center portfolio-block">
            <div class="rows">
                <img src="/img/profile/green.svg" alt="" class="mb-3">
                <p class="txt-mini"><?=$m?>.2021</p>
                <p class="txt-mini">$ <?=$rank['fund']?></p>
            </div>
        </div>
        <?endforeach;?>


    </div>
    <?endif;?>
</main>

