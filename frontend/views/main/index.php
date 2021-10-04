<?php
/* @var $this yii\web\View*/
/* @var $user common\models\User*/
/* @var $selRank common\models\UserRank*/
/* @var $rankList common\models\UserRank[]*/
/* @var $events common\models\Events[]*/
/* @var $userEvents common\models\Events[]*/

$this->title = 'BRIClub';
$url = 'https://briclub.com';
$this->registerJsFile('https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js');
$this->registerJsFile('https://yastatic.net/share2/share.js');
//$selRank это таб на главном чей ранг он смотрит
//$futureSel - для определения открыт ли непрошедший ранг
if($selRank->id > $user->rank_id){
    $futureSel = true;
}else{
    $futureSel=false;
}
?>
<main class="d-flex">
        <div class="main-content" id="main">
            <div class="main-block">

                <div class="main-list txt-5F7">
                    <?foreach ($rankList as $rank):?>
                        <div class="list-item">
                            <h6><a href="/?selRankId=<?=$rank->id?>" class="txt-5F7 <?=($rank->id == $selRank->id)?'active':'';?>"><?=$rank->title;?></a></h6>
                            <div class="list-item_line <?=($user->rank_id < $rank->id && $selRank->id >= $rank->id)?'blue':'';?> <?=($user->rank_id >= $rank->id)?'green':'';?>"></div>
                        </div>
                    <?endforeach;?>
                </div>

                <div class="scroll">
                    <?if($selRank->id <= $user->rank_id):?>
                        <div class="row mb-4">
                        <div class="col mb-3">
                            <div class="cards mx-auto">
                                <h6 class="mb-3">Баланс</h6>
                                <div class="center-line mb-3">
                                    <div class="block-CV center">
                                        <h4 class="w7">PV <span class="ml-2"><?=$user->p_balans;?></span></h4>
                                    </div>
                                    <div class="block-PV center ml-3">
                                        <h4 class="w7">CV</h4>
                                    </div>
                                </div>
                                <a href="/profile/balance" class="fon-gray-800 py-2 px-4 text-white" style="border-radius: 4px;">Мои балансы</a>
                            </div>
                        </div>

                        <div class="col">
                            <div class="cards mx-auto">
                                <h6 class="mb-3">Капитал в <span class="w7">клубе</span></h6>

                                <div class="fon-main between p-3 item-card">
                                    <img src="img/main/cards-img.svg" alt="">
                                    <div class="rows">
                                        <p class="txt-mini">Капитал с системы</p>
                                        <h4 class="w5">pv <?=$selRank->fund;?></h4>
                                    </div>
                                </div>

                                <div class="fon-main between p-3 mt-3 item-card mb-4">
                                    <img src="img/main/cards-img.svg" alt="">
                                    <div class="rows <?=($selRank->dividends > 0)?'':'txt-A78B'?>">
                                        <p class="txt-mini"><?=($selRank->dividends > 0)?'Дивиденты в год':'Дивиденты не доступны'?></p>
                                        <h4 class="w5">pv <?=$selRank->dividends;?></h4>
                                    </div>
                                </div>
                                <a href="#" class="fon-btn-blue-100 py-2 px-4 text-white" style="border-radius: 4px;" data-toggle="modal" data-target="#staticBackdrop">Пригласить в клуб</a>
                            </div>
                        </div>
                    </div>
                    <?else:?>
                        <div class="row mb-4">
                        <div class="col">
                            <div class="cards mx-auto">
                                <h5 class="mb-3">Средний капитал <span style="color: #02A651;"><?=$selRank->title_rod;?></span></h5>
                                <div class="line item-card flex-line">
                                    <img src="/img/main/main-line.svg" alt="">
                                    <div class="rows ms-3">
                                        <p class="txt mini">Капитал с системы</p>
                                        <h4 class="w7">PV <?=$selRank->fund;?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col margin-top-38">
                            <div class="cards mx-auto">
                                <div class="line item-card flex-line">
                                    <img src="/img/main/main-line.svg" alt="">
                                    <div class="rows ms-3 <?=($selRank->dividends > 0)?'':'txt-A78B'?>">
                                        <p class="txt-mini"><?=($selRank->dividends > 0)?'Дивиденты в год':'Дивиденты не доступны'?></p>
                                        <h4 class="w5">pv <?=$selRank->dividends;?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?endif;?>

                    <div class="row">
                        <div class="col">
                            <div class="cards mx-auto">
                                <h5 class="mb-3"><?=($futureSel)?'Количество акции <span style="color: #02A651;">'.$selRank->title_rod.'</span>':'Мои акции';?></h5>
                                <div class="banner item-card">
                                    <div class="banner-header between">
                                        <div class="d-flex">
                                            <img src="img/main/banner-bri.svg" alt="">
                                            <h5 class="w7 center ml-2">BRI</h5>
                                        </div>
                                        <p class="text-mini">1 BRI = 2$</p>
                                    </div>
                                    <?if($futureSel):?>
                                        <h4 class="w7"><?=number_format($selRank->bri_tokens,2,'.', '');?> BRI</h4>
                                        <h5 class="w5 txt-AAA"><?=$selRank->bri_tokens*2;?> $</h5>
                                    <?else:?>
                                        <h4 class="w7"><?=number_format($user->getBalance('bri'),2,'.', '');?> BRI</h4>
                                        <h5 class="w5 txt-AAA"><?=$user->getBalance('bri')*2;?> $</h5>
                                    <?endif;?>
                                </div>
                            </div>
                        </div>

                        <div class="col margin-top-38">
                            <div class="cards mx-auto">
                                <div class="banner banner-2 item-card">
                                    <div class="banner-header between">
                                        <div class="d-flex">
                                            <img src="img/main/banner-grc.svg" alt="">
                                            <h5 class="w7 center ml-2">GRC</h5>
                                        </div>
                                        <p class="text-mini">1 GRC = 10$</p>
                                    </div>
                                    <?if($futureSel):?>
                                        <h4 class="w7"><?=number_format($selRank->grc_tokens,2,'.', '');?> GRC</h4>
                                        <h5 class="w5 txt-AAA"><?=$selRank->grc_tokens*10;?> $</h5>
                                    <?else:?>
                                        <h4 class="w7"><?=number_format($user->getBalance('grc'),2,'.', '');?> GRC</h4>
                                        <h5 class="w5 txt-AAA"><?=$user->getBalance('grc') * 10;?> $</h5>
                                    <?endif;?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <?if(!$futureSel):?>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="w5 activity-title">Мероприятия</h6>
                        </div>
                        <?foreach ($events as $event):?>
                            <?$date = $event->getStartDate();?>
                            <div class="col-6 mb-4">
                                <div class="cards mx-auto">
                                    <div class="activity fon-main one" style="background: url('<?=$event->getImg();?>') no-repeat center; background-size: contain;">
                                        <div class="activity-block">
                                            <div class="rows mb-3">
                                                <h4 class="w7"><?=$date['day'];?></h4>
                                                <h6><?=$date['monthRus'];?></h6>
                                            </div>
                                            <div class="rows">
                                                <h6 class="txt-green"><?=$event->getTypeName();?></h6>
                                                <h5 class="w7"><?=$event->title?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                    <?endif;?>

                </div>
            </div>
        </div>
        <aside class="fon-main px-3" id="aside">
            <div>
                <h6 class="mb-2">Доступно мне</h6>
                <?foreach ($userEvents as $event):?>
                    <?$date = $event->getStartDate();?>
                    <div class="cardes fon-gray-300 between">
                        <div class="d-flex align-iteml-center">
                            <div class="circle fon-gray-200 mr-2 mb-1"></div>
                            <h6><?=$event->getTypeName();?></h6>
                        </div>
                        <div class="date center rows">
                            <h6><?=$date['dayMonth'];?></h6>
                            <h6><?=$date['time'];?></h6>
                            <div class="circle center">1</div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>

            <div class="mt-5 margin-bot-32">

                <h6 class="mb-3">Мои подарки</h6>

                <div class="cardes fon-aside-card-100 between">
                    <div>
                        <h6>Страховка Каско</h6>
                    </div>
                    <div class="date center">
                        <p>1</p>
                    </div>
                </div>

                <div class="cardes fon-green-400 between">
                    <div>
                        <h6>Портфель акции</h6>
                    </div>
                    <div class="date center">
                        <p>2</p>
                    </div>
                </div>
            </div>

            <div>

<!--                <h6 class="mb-3">Календарь событий</h6>-->


            </div>

        </aside>
</main>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <span class="modal-title w5" id="staticBackdropLabel">Поделиться реферальной ссылкой</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <a class="referal-link" id="referalLink"><?=$url?></a>
                <div class="">
                    <i class="fa fa-copy" aria-hidden="true"></i>
                    <button class="btn btn-link" onclick="copy('referalLink')">Копировать</button>
                </div>
                </p>


                <div class="btn__group">
                    <p class="w5 text-dark">Поделиться в:</p>
                    <div class="ya-share2" data-title="Реферальная ссылка Briclub.com" data-url="<?=$url?>" data-services="vkontakte,facebook,twitter,viber,whatsapp,skype,telegram"></div>
                </div>
            </div>
        </div>
    </div>
</div>