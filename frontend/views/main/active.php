<?php
/* @var $this yii\web\View*/
/* @var $user common\models\User*/
/* @var $events common\models\Events[]*/
?>

<main class="before d-flex">
    <div class="main-content">

        <h4 class="w7 margin-bot-24">Добро пожаловать в BRI Club</h4>
        <h6 class="txt-A4A mb-4">“Выбирая цель, цельтесь выше,  не боясь попасть в звезды.”</h6>

        <div class="row cards between margin-bot-50">
            <div class="col">
                <div class="cards-block">
                    <h5 class="mb-3">Подписка нужен текст</h5>
                    <h6 class="txt-A4A margin-bot-32">“Выбирая цель, цельтесь выше,  не боясь попасть в звезды.”</h6>
                </div>
            </div>
            <div class="col">
                <div class="cards-block">
                    <h2>$ 80 000</h2>
                    <p class="txt-mini txt-99A1 margin-bot-32">Средний доход в клубе</p>
                    <img src="/img/before/lins.svg" alt="">
                </div>
            </div>
        </div>

        <h5 class="w7 mb-4">Что дает BRI CLUB ?</h5>
        <div class="row cards">
            <div class="col">
                <ul>
                    <li class="d-flex align-items-center">
                        <img src="/img/before/health.svg" alt="">
                        <p class="txt-mini w4">Физическое здоровье</p>
                    </li>
                    <li class="d-flex align-items-center">
                        <img src="/img/before/finance.svg" alt="">
                        <p class="txt-mini w4">Финансовую стабильность</p>
                    </li>
                    <li class="d-flex align-items-center">
                        <img src="/img/before/network.svg" alt="">
                        <p class="txt-mini w4">Разносторонний нетворкинг</p>
                    </li>
                </ul>
            </div>
            <div class="col">
                <ul>
                    <li class="d-flex align-items-center">
                        <img src="/img/before/friends.svg" alt="">
                        <p class="txt-mini w4">Достойное окружение</p>
                    </li>
                    <li class="d-flex align-items-center">
                        <img src="/img/before/myself.svg" alt="">
                        <p class="txt-mini w4">Высокое саморазвитие</p>
                    </li>
                    <li class="d-flex align-items-center">
                        <img src="/img/before/altruizm.svg" alt="">
                        <p class="txt-mini w4">Альтруизм и филантропию</p>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <aside class="fon-main px-3">


        <div class="margin-bot-32">
            <h6 class="mb-2">Ближайшие мероприятия</h6>
            <?foreach ($events as $event):?>
                <?$date = $event->getStartDate();?>
                <div class="cardes fon-gray-300 between">
                    <div class="d-flex align-items-center">
                        <div class="circle fon-gray-200 me-2 mb-1"></div>
                        <h6><?=$event->getTypeName();?></h6>
                    </div>
                    <div class="date center rows">
                        <h6><?=$date['dayMonth'];?></h6>
                        <h6><?=$date['time'];?></h6>
                    </div>
                </div>
            <?endforeach;?>


        </div>


    </aside>
</main>
