<?$date = $event->getStartDate();?>

<main class="merop blocks-top">

    <div class="merop-content">
        <h4 class="w7 mb-4">Мероприятия</h4>

        <div class="mb-4">
            <img class="main-img" src="<?=$event->link?>" alt="">
            <!-- <div class="main-img"></div> -->
        </div>
        <div class="info fon-<?=($event->getEventAccessColor($user));?>  center mb-3">
            <?if ($event->start_time < time()):?>
                Прошедший
            <?elseif($event->isEventAccess($user)):?>
                Доступно
            <?else:?>
                Недоступно
            <?endif;?>
        </div>
        <div class="rows mb-4">
            <h6 class="txt-green-100"><?=$event->getTypeName();?></h6>
            <h5 class="w7 mb-3 mt-1"><?=$event->title?></h5>
            <p class="text txt-B8B6"><?=$event->text?></p>
        </div>
        <div class="banner-block">
            <div class="banner banner-one">
                <div class="d-flex align-items-end mb-3">
                    <h4 class="mr-2"><?=$date['day'];?></h4>
                    <p class="mr-2"><?=$date['monthRus'];?></p>
                    <p class="mr-3"><?=$date['year'];?></p>
                    <p>18:00</p>
                </div>
                <div class="d-flex align-items-end">
                    <p class="mr-2">Место:</p>
                    <p class="mr-3">ZOOM (374387dfv89)</p>
                </div>
            </div>
            <div class="banner banner-one center">
                    <img src="<?=$event->getSpikers()->img_url;?>" alt="">
                <div class="rows ml-3">
                    <h5 class="w7">Спикер</h5>
                        <h6><?=$event->getSpikers()->fio?></h6>
                </div>
            </div>
        </div>
    </div>

</main>
