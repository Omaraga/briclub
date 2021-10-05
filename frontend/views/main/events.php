<?php

/* @var $events common\models\Events[] */
/* @var $user common\models\User */


?>


<main class="merop blocks-top">

    <div class="merop-content">
        <h4 class="w7 mb-4">Мероприятия</h4>

        <div class="around flex-wrap">
            <?foreach($events as $event):?>
                <?$date = $event->getStartDate();?>
                <a href="/main/events?id=<?=$event->id?>">
                    <div class="cards one">
                        <div class="info fon-<?=($event->getEventAccessColor($user));?> center">
                            <?if ($event->start_time < time()):?>
                                Прошедший
                            <?elseif($event->isEventAccess($user)):?>
                                Доступно
                            <?else:?>
                                Недоступно
                            <?endif;?>
                        </div>
                        <div class="content rows">
                            <div class="rows">
                                <h4 class="w7 mb-1"><?=$date['day'];?></h4>
                                <h6><?=$date['monthRus'];?></h6>
                            </div>
                            <div class="mt-3">
                                <h6 class="txt-green-100"><?=$event->getTypeName();?></h6>
                                <h5 class="w7"><?=$event['title']?></h5>
                            </div>
                        </div>
                    </div>
                </a>
            <?endforeach;?>


        </div>
    </div>

</main>