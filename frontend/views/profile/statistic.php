<?php
/* @var $this yii\web\View */
/* @var $type string */
/* @var $currTab integer */
/* @var $statisticModel \frontend\models\StatisticModel */


$this->title = "Статистика";
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$premium = \common\models\Premiums::findOne(['user_id' => Yii::$app->user->id]);
$thisMonth = intval(date("m"));
/*JS для отображения графика*/
$this->registerJsFile('/js/statistic.js',['depends'=>'yii\web\JqueryAsset']);

?>
<style>
    .bar-chart .y-axis .label {
        margin: 0px auto -5px -47px;
    }
</style>

<main class="statistick">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="text-left">Статистика</h1>
            </div>
            <ul class="nav pl-2" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link btn btn__white <?=($currTab==1)?'active':''?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Доходы</a>
                </li>
                <li class="nav-item ml-4" role="presentation">
                    <a class="nav-link btn btn__white <?=($currTab==2)?'active':''?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Структура</a>
                </li>
                <li class="nav-item ml-4" role="presentation">
                    <a class="nav-link btn btn__white <?=($currTab==3)?'active':''?>" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Матрица</a>
                </li>
            </ul>

            <div class="tab-content mb-5" id="myTabContent" style="width: 100%">
                <div class="tab-pane fade show <?=($currTab==1)?'active':''?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <?if($premium && $premium->is_active == 1):?>
                            <figure id="money" class="bar-chart col-lg-7 col-11 pl-5">
                                <div class="d-flex justify-content-between">
                                    <div class="col-9 d-flex justify-content-between" style="margin-left: -4rem;">
                                        <div class="size__left">
                                            <p class="w7">Доход за период</p>
                                            <p class="text__blue w7">PV <?=$statisticModel->dohodPeriod;?></p>
                                        </div>
                                    </div>
                                    <div class="col-5 col-md-3 d-flex">

                                        <select name="" id="" class="statisticSelector form-control" attr-tab="1">
                                            <option value="year" <?=($type=='year')?'selected':'';?>>Год</option>
                                            <option value="month" <?=($type=='month')?'selected':'';?>>Месяц</option>
                                            <option value="week" <?=($type=='week')?'selected':'';?>>Неделя</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="row bars">
                                    <div class="y-axis">
                                        <div class="segment">
                                            <span class="label text__small">PV 5000</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">PV 1000</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">PV 500</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">PV 100</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label">0</span>
                                        </div>
                                    </div>
                                    <div class="x-axis">

                                        <?foreach ($statisticModel->statisticArray as $key => $value):?>
                                            <?if($type == 'year'):?>
                                                <?if ($key > $thisMonth){
                                                    break;
                                                }?>
                                            <?elseif($type == 'month'):?>
                                                <?if($value[1] < 1) continue?>
                                            <?endif;?>

                                            <div class="year wrap" attr-type="<?=$type;?>">
                                                <div class="col m-0 <?=$type;?>">
                                                    <span class="bar" attr-money="<?=$value[1];?>"><div class="info_sum"></div></span>
                                                </div>
                                                <span class="label mt-1"><?=$value[0];?></span>
                                            </div>
                                        <?endforeach;?>
                                    </div>
                                </div>

                            </figure>
                            <div class="col-12 col-lg-4">
                                <ul class="box__right-list">
                                    <li class="box__right-item">
                                        <img src="/img/main/1.svg" alt="" class="">
                                        <div class="">
                                            <p class="text__mini">Общий баланс</p>
                                            <span class="text__middle w7">CV <?=$user['w_balans'];?></span>
                                        </div>
                                    </li>
                                    <li class="box__right-item">
                                        <img src="/img/main/1.svg" alt="" class="">
                                        <div class="">
                                            <p class="text__mini">Общий доход</p>
                                            <span class="text__middle w7">PV <?=$statisticModel->dohodKurator + $statisticModel->dohodBonusPolz;?></span>
                                        </div>
                                    </li>
                                    <li class="box__right-item">
                                        <img src="/img/main/2.svg" alt="" class="">
                                        <div class="one__podbloc">
                                            <p class="text__mini">Кураторские</p>
                                            <div class="item__podbloc">
                                                <span class="text__middle w7">PV <?=$statisticModel->dohodKurator;?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="box__right-item">
                                        <img src="/img/main/2.svg" alt="" class="">
                                        <div class="one__podbloc">
                                            <p class="text__mini">Бонус за место</p>
                                            <div class="item__podbloc">
                                                <span class="text__middle w7">PV <?=$statisticModel->dohodBonusPolz;?></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        <?else:?>
                            <div class="info__block-fon">
                                <span>Статистика дохода доступно только для Premium</span>
                                <div class="btn btn__blue"><a href="/premium" style="color: #fff; text-decoration: none">Узнать больше</a></div>
                            </div>


                            <div style="width: 80%; height: 79vh; background: url('/img/blur1.jpg');background-size: contain; background-repeat: no-repeat;">
                            </div>
                        <?endif;?>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">

                        <?if($premium && $premium->is_active == 1):?>
                            <div class="col-12 col-md-5 col-lg-4 text-center statistick__block">
                                <div class="">
                                    <img class="mr-2" src="/img/main/3-1.svg" alt="">
                                    <span class="w5">Общий объем заработка структуры</span>
                                </div>
                                <div class="">
                                    <h1 class="w7 h1 text__big mt-5 text__blue"><?=$statisticModel::getMoneyFormat($statisticModel->childrenDohod);?></h1>
                                </div>
                                <div class="progress mt-4">
                                    <div class="progress-bar gray" role="progressbar" style="width: <?=$statisticModel::getProcent($statisticModel->childrenDohodKurator,$statisticModel->childrenDohod);?>%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"><?=$statisticModel::getProcent($statisticModel->childrenDohodKurator,$statisticModel->childrenDohod);?>%</div>
                                    <div class="progress-bar bg-success blue" role="progressbar" style="width: <?=$statisticModel::getProcent($statisticModel->childrenDohodBonus,$statisticModel->childrenDohod);?>%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"><?=$statisticModel::getProcent($statisticModel->childrenDohodBonus,$statisticModel->childrenDohod);?>%</div>
                                </div>
                                <div class="mt-5 d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="square gray"></div>
                                        <span>Доход с кураторских бонусов</span>
                                    </div>
                                    <span><?=$statisticModel::getMoneyFormat($statisticModel->childrenDohodKurator);?></span>
                                </div>
                                <div class="mt-3 d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="square blue"></div>
                                        <span>Доход с матрицы</span>
                                    </div>
                                    <span><?=$statisticModel::getMoneyFormat($statisticModel->childrenDohodBonus);?></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-5 col-lg-4 text-center statistick__block">
                                <div class="">
                                    <img class="mr-2" src="/img/main/3-2.svg">
                                    <span class="w5">Общий объем заработка Личников</span>
                                </div>
                                <div class="">
                                    <h1 class="w7 h1 text__big mt-5 text__blue"><?=$statisticModel::getMoneyFormat($statisticModel->lichnikDohod);?></h1>
                                </div>
                                <div class="progress mt-4">
                                    <div class="progress-bar green" role="progressbar" style="width: <?=$statisticModel::getProcent($statisticModel->lichnikDohodKurator,$statisticModel->lichnikDohod);?>%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"><?=$statisticModel::getProcent($statisticModel->lichnikDohodKurator,$statisticModel->lichnikDohod);?>%</div>
                                    <div class="progress-bar bg-success yallow" role="progressbar" style="width: <?=$statisticModel::getProcent($statisticModel->lichnikDohodBonus,$statisticModel->lichnikDohod);?>%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"><?=$statisticModel::getProcent($statisticModel->lichnikDohodBonus,$statisticModel->lichnikDohod);?>%</div>
                                </div>
                                <div class="mt-5 d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="square green"></div>
                                        <span>Доход с кураторских бонусов</span>
                                    </div>
                                    <span><?=$statisticModel::getMoneyFormat($statisticModel->lichnikDohodKurator);?></span>
                                </div>
                                <div class="mt-3 d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="square yallow"></div>
                                        <span>Доход с матрицы</span>
                                    </div>
                                    <span><?=$statisticModel::getMoneyFormat($statisticModel->lichnikDohodBonus);?></span>
                                </div>
                            </div>
                        <?else:?>
                            <div class="info__block-fon">
                                <span>Статистика структуры доступно только для Premium</span>
                                <div class="btn btn__blue"><a href="/premium" style="color: #fff; text-decoration: none">Узнать больше</a></div>
                            </div>


                            <div style="width: 80%; height: 79vh; background: url('/img/blur2.jpg');background-size: contain; background-repeat: no-repeat;">
                            </div>

                        <?endif;?>
                    </div>
                </div>
                <div class="tab-pane fade show <?=($currTab==3)?'active':''?>" id="contact" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <?if($premium && $premium->is_active == 1):?>
                            <figure id="matrix" class="bar-chart col-lg-7 col-11" style="overflow-x: scroll; padding-left: 4rem!important;">
                                <div class="d-flex justify-content-between">
                                    <div class="col-9 d-flex justify-content-between" style="margin-left: -4rem;">
                                        <div class="size__left">
                                            <p class="w7">Людей добавлено в матрицу за период</p>
                                            <p class="text__blue w7"><?=$statisticModel->childrenPeriodSize;?></p>
                                        </div>
                                    </div>
                                    <div class="col-5 col-md-3 d-flex">

                                        <select name="" id="" class="statisticSelector form-control" attr-tab="3">
                                            <option value="year" <?=($type=='year')?'selected':'';?> >Год</option>
                                            <option value="month" <?=($type=='month')?'selected':'';?>>Месяц</option>
                                            <option value="week" <?=($type=='week')?'selected':'';?>>Неделя</option>
                                        </select>

                                    </div>

                                </div>

                                <div class="row bars">
                                    <div class="y-axis">
                                        <div class="segment">
                                            <span class="label text__small">100</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">50</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">10</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label text__small">5</span>
                                        </div>
                                        <div class="segment">
                                            <span class="label">0</span>
                                        </div>
                                    </div>
                                    <div class="x-axis">
                                        <?foreach ($statisticModel->statisticArray as $key => $value):?>
                                            <?if($type == 'year'):?>
                                                <?if ($key > $thisMonth){
                                                    break;
                                                }?>
                                            <?elseif($type == 'month'):?>
                                                <?if($value[2] < 1) continue?>
                                            <?endif;?>
                                            <div class="year wrap" attr-type="<?=$type;?>">
                                                <div class="col m-0 <?=$type;?>">
                                                    <span class="bar" attr-child="<?=$value[2];?>"><div class="info_sum"></div></span>
                                                </div>
                                                <span class="label"><?=$value[0];?></span>
                                            </div>
                                        <?endforeach;?>

                                    </div>
                                </div>

                            </figure>
                            <div class="col-12 col-lg-4">
                                <ul class="box__right-list">
                                    <li class="box__right-item">
                                        <img src="/img/main/2-1.svg" alt="" class="">
                                        <div class="">
                                            <p class="text__mini">Людей в структуре</p>
                                            <span class="text__middle w7"><?=$statisticModel->childrenSize;?></span>
                                        </div>
                                    </li>
                                    <li class="box__right-item">
                                        <img src="/img/main/2-2.svg" alt="" class="">
                                        <div class="one__podbloc">
                                            <p class="text__mini">Личники</p>
                                            <div class="item__podbloc">
                                                <span class="text__middle w7"><?=$statisticModel->lichnikSize;?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="box__right-item">
                                        <img src="/img/main/2-3.svg" alt="" class="">
                                        <div class="one__podbloc">
                                            <p class="text__mini">Выкупленные места</p>
                                            <div class="item__podbloc">
                                                <span class="text__middle w7"><?=$statisticModel->boughtPlaces;?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="box__right-item">
                                        <img src="/img/main/2-4.svg" alt="" class="">
                                        <div class="one__podbloc">
                                            <p class="text__mini">Количество клонов</p>
                                            <div class="item__podbloc">
                                                <span class="text__middle w7"><?=$statisticModel->clonesSize;?></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        <?else:?>
                            <div class="info__block-fon">
                                <span>Статистика матрицы доступно только для Premium</span>
                                <div class="btn btn__blue"><a href="/premium" style="color: #fff; text-decoration: none">Узнать больше</a></div>
                            </div>


                            <div style="width: 80%; height: 79vh; background: url('/img/blur3.jpg');background-size: contain; background-repeat: no-repeat;">
                            </div>
                        <?endif;?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>

    </script>
</main>
