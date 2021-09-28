<?php

/* @var $this yii\web\View */

$this->title = "Новости";

?>
    <main class="event">
        <h1 class="h1 ml-3 text-left">Новости и мероприятия</h1>
        <div class="container-fluid">
            <div class="row mt-5 flex-wrap-reverse">
                <div class="col-lg-5">
                    <ul class="left__box-list nav">
                        <?$counter = 0;?>
                        <? foreach ($news as $item):?>
                        <li class="left__box-item col-12"><a class="<?=($counter == 0)?'active':'';?>" href="#news-<?=$item['id'];?>" data-toggle="tab">
                                <img src="<?=$item['link'];?>" alt="" style="width: 30%">
                                <div class="text__group">
                                    <p class="text__middle w5"><?=$item['title'];?></p>
                                    <span class=""><?=date("d.m.Y H:i", $item['time'])?></span>
                                </div>
                            </a>
                        </li>
                        <?$counter++;?>
                        <?endforeach;?>
                    </ul>
                </div>


                <div class="col-lg-7">
                    <div class="box__right">
                        <div class="tab-content">
                            <?$counter = 0;?>
                            <?foreach ($news as $item):?>
                                <div class="tab-pane <?=($counter == 0)?'active':'';?>" id="news-<?=$item['id'];?>">
                                    <img class="col-12" src="<?=$item['link'];?>">
                                    <div class="col-12 row my-3">
                                        <p><?=date("d.m.Y H:i", $item['time'])?></p>
                                        <h3 class="col-xl-12  mb-3"><?=$item['title'];?></h3>
                                        <p><?=$item['text'];?></p>
                                    </div>
                                </div>
                                <?$counter++;?>
                            <?endforeach;?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>