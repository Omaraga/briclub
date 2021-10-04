<?php
if ($userEvents )
$access = 'green-200';
$access = 'gray-500';
$access = 'red-100';


?>


<main class="merop blocks-top">

    <div class="merop-content">
        <h4 class="w7 mb-4">Мероприятия</h4>

        <div class="around flex-wrap">
            <?foreach($events as $event):?>
                <?$date = $event->getStartDate();?>
                <a href="#">
                <div class="cards one">
                    <div class="info fon-green-200 center"><?=$access?></div>
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

    <aside class="fon-main px-3" id="aside">
        <div>
            <h6 class="mb-2">Доступно мне</h6>

            <div class="cardes fon-gray-300 between">
                <div class="d-flex align-items-center">
                    <div class="circle fon-gray-200 me-2 mb-1"></div>
                    <h6>Онлайн мастер класс</h6>
                </div>
                <div class="date center rows">
                    <h6>22.09</h6>
                    <h6>18:00</h6>
                    <div class="circle center">1</div>
                </div>
            </div>

            <div class="cardes fon-gray-300 between">
                <div class="d-flex align-items-center">
                    <div class="circle fon-green-200 me-2 mb-1"></div>
                    <h6>Онлайн мастер класс</h6>
                </div>
                <div class="date center rows">
                    <h6>22.09</h6>
                    <h6>18:00</h6>
                    <div class="circle center">1</div>
                </div>
            </div>

            <div class="cardes fon-gray-300 between">
                <div class="d-flex align-items-center">
                    <div class="circle fon-green-200 me-2 mb-1"></div>
                    <h6>Онлайн мастер класс</h6>
                </div>
                <div class="date center rows">
                    <h6>22.09</h6>
                    <h6>18:00</h6>
                    <div class="circle center">1</div>
                </div>
            </div>

            <div class="cardes fon-gray-300 between">
                <div class="d-flex align-items-center">
                    <div class="circle fon-green-200 me-2 mb-1"></div>
                    <h6>Онлайн мастер класс</h6>
                </div>
                <div class="date center rows">
                    <h6>22.09</h6>
                    <h6>18:00</h6>
                    <div class="circle center">1</div>
                </div>
            </div>
        </div>

    </aside>
</main>