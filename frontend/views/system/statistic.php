<?php
/* @var $this yii\web\View */
?>
<main class="statistic">
    <h4 class="w7 mb-4">Статистика</h4>

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Капитал</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Основатели</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Система</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="block">
                <div class="between">
                    <div class="rows">
                        <h3 class="w7 mb-2">PV <span><?=$actions?></span></h3>
                        <h5>Капитал с системы</h5>
                        <h5><?=$user->id?></h5>
                    </div>
                    <img src="/img/statistic/card-img.svg" alt="">
                </div>
                <div class="info mt-5">
                    <p class="txt-mini mb-2">Ожидаемый капитал с системы: <span class="txt-mini w7  txt-green-100"><?=$nextrank->title?></span></p>
                    <div class="progres mb-3"><div class="bar"></div></div>
                    <div class="between">
                        <p class="txt-mini">1/7</p>
                        <h6 class="mr-3">PV <?=$rank->fund?></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

        </div>
        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
    </div>
</main>
