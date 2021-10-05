<?php
/* @var $this yii\web\View */

$script = <<<JS
$(".statisticSelector").change(function (e){
        let tab = $(this).attr('attr-tab')
        let type = $(this).val()
        window.location= '/system/statistic?type='+type +'&currTab='+ tab
    })
JS;
$this->registerJs($script);

?>

<main class="statistic">
    <h4 class="w7 mb-4">Статистика</h4>

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link  <?=($currTab==1)?'active':''?>" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
               aria-controls="pills-home" aria-selected="true">Капитал</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?=($currTab==2)?'active':''?>" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
               aria-controls="pills-profile" aria-selected="false">Основатели</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?=($currTab==3)?'active':''?>" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab"
               aria-controls="pills-contact" aria-selected="false">Система</a>
        </li>
    </ul>
    <div class="tab-content mt-5" id="pills-tabContent">
        <div class="tab-pane fade show <?=($currTab==1)?'active':''?>" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="block">
                <div class="between">
                    <div class="rows">
                        <h3 class="w7 mb-2">PV <span><?= $actions ?></span></h3>
                        <h5>Капитал с системы</h5>
                        <h5><?= $user->id ?></h5>
                    </div>
                    <img src="/img/statistic/card-img.svg" alt="">
                </div>
                <div class="info mt-5">
                    <p class="txt-mini mb-2">Ожидаемый капитал с системы: <span
                                class="txt-mini w7  txt-green-100"><?= $nextrank->title ?></span></p>
                    <div class="progres mb-3">
                        <div class="bar"></div>
                    </div>
                    <div class="between">
                        <p class="txt-mini">1/7</p>
                        <h6 class="mr-3">PV <?= $rank->fund ?></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade <?=($currTab==2)?'active':''?>" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="flex-line flex-wrap">
                <div class="banner center mr-3 mb-4">
                    <div class="rows">
                        <h2 class="w7 mb2">PV <?= $structureTotalProfit ?></h2>
                        <h6>Общий объем заработка структуры</h6>
                    </div>
                </div>

                <div class="banner center mr-3">
                    <div class="rows">
                        <h2 class="w7 mb2">PV <?= $structureOwnProfit ?></h2>
                        <h6>Общий объем заработка Основателей</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show <?=($currTab==3)?'active':''?>" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
            <div class="block-main">
                <div class="between">
                    <h5 class="w5">Людей добавлено в систему за период</h5>
                    <div class="col-5 col-md-3 d-flex">
                        <select name="" id="reloade" class="statisticSelector form-control" attr-tab="3">
                            <option value="year" <?=($type=='year')?'selected':'';?>>Год</option>
                            <option value="month" <?=($type=='month')?'selected':'';?>>Месяц</option>
                            <option value="week" <?=($type=='week')?'selected':'';?>>Неделя</option>
                        </select>
                    </div>
                </div>
                <h4 class="txt-green-100 w7">1250</h4>
                <div class="block-content d-flexalign-itens-flex-end">
                    <ul>
                        <li class="item">
                            <p>100</p>
                        </li>
                        <li class="item">
                            <p>50</p>
                        </li>
                        <li class="item">
                            <p>10</p>
                        </li>
                        <li class="item">
                            <p>0</p>
                        </li>
                    </ul>
                    <ul class="bar-block">
                        <?foreach ($statisticModel->statisticArray as $key => $value):?>
                            <?if($type == 'year'):?>
                                <?if ($key > $thisMonth){
                                    break;
                                }?>
                            <?elseif($type == 'month'):?>
                                <?if($value[2] < 1) continue?>
                            <?endif;?>
                            <li class="bars">
                                <div class="bars-color" style="height: <?=\frontend\models\StatisticModel::getHeight($value[2])?>%;">
                                    <div class="bars-box center" >
                                        <span class="txt-mini"><?=$value[2]?></span>
                                    </div>
                                </div>
                                <p class="txt-mini center"><?=$value[0]?></p>
                            </li>
                        <?endforeach;?>

                    </ul>
                </div>
            </div>
            <div class="mt-3 flex-line flex-wrap">
                <div class="img flex-line">
                    <img src="/img/statistic/arrow.svg" alt="">
                    <div class="rows ml-3">
                        <h4 class="w5">1250</h4>
                        <p class="txt-mini">Людей в системе</p>
                    </div>
                </div>

                <div class="img flex-line">
                    <img src="/img/statistic/arrow.svg" alt="">
                    <div class="rows ml-3">
                        <h4 class="w5">12</h4>
                        <p class="txt-mini">Основатели</p>
                    </div>
                </div>

                <div class="img flex-line">
                    <img src="/img/statistic/arrow.svg" alt="">
                    <div class="rows ml-3">
                        <h4 class="w5">3</h4>
                        <p class="txt-mini">Выкупленные места/p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

