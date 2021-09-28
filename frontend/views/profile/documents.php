<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Документы";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$docs = \common\models\Documents::find()->where(['type'=>1,'status'=>1])->orderBy('order asc')->all();
$promos = \common\models\Documents::find()->where(['type'=>2,'status'=>1])->orderBy('order asc')->all();
$referal_link = 'https://shanyrakplus.com/site/signup?referal=' . $user->username;
$tg_bot_link = '@brdi_bot';
$extensions = ['png', 'jpg','JPG','PNG', 'jpeg','JPEG'];
?>
    <main class="event">
        <div class="container-fluid">
            <div class="btn__group mt-5">
                <ul class="nav" id="myTab" role="tablist">
                    <li class="" role="presentation">
                        <a class="btn__tabs active" id="home-tab" data-toggle="tab" href="#document" role="tab" aria-controls="home" aria-selected="true">Документы</a>
                    </li>
                    <li class="" role="presentation">
                        <a class="btn__tabs" id="profile-tab" data-toggle="tab" href="#documentpromo" role="tab" aria-controls="profile" aria-selected="false">Промо материалы</a>
                    </li>
                </ul>
            </div>
            <!-- ====== TAB_CONTENT ===== -->
            <div class="tab-content">
                <div class="tab-pane <?if(!$promo){echo "active";}?>" id="document" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="container-fluid">
                        <div class="">
                            <h1 class="h1 ml-3">Документы</h1>
                            <div class="row mt-5">
                                <?foreach ($docs as $doc):?>
                                    <div class="col-sm-7 line col-12">
                                        <span><?=$doc['title'];?></span>
                                        <a href="<?=isset($doc['link2']) ? $doc['link2'] : $doc['link']?>">
                                            <img src="/img/dowload.svg" alt="">
                                        </a>
                                    </div>
                                <?endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane <?if($promo){echo "active";}?>" id="documentpromo" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container-fluid">
                        <h1 class="h1 ml-3">Промо материалы</h1>

                        <div class="row mt-5">
                            <div class="col-lg-5">
                                <ul class="left__box-list nav">
                                    <?$counter = 0;?>
                                    <?foreach ($promos as $doc):?>
                                        <li class="left__box-item col-12"><a class="<?=($counter==0)?'active':'';?>" href="#doc-<?=$doc['id'];?>" data-toggle="tab">

                                                <div class="text__group">
                                                    <span class=" text__middle w5"><?=$doc['title'];?></span>
                                                </div>
                                            </a>
                                        </li>
                                        <?$counter ++;?>
                                    <?endforeach;?>
                                </ul>
                            </div>


                            <div class="col-lg-7">
                                <div class="box__right">
                                    <div class="tab-content">
                                        <? $counter = 0;?>
                                        <?foreach ($promos as $doc):?>
                                            <div class="tab-pane <?=($counter == 0)?'active':'';?>" id="doc-<?=$doc['id'];?>">
                                                <div class="col-12 row my-3">

                                                    <h2 class="col-xl-12 h2 mb-3"><?=$doc['title']?></h2>
                                                    <?
                                                    $link = $doc->link;
                                                    $strings = explode('.', $link);
                                                    $count = count($strings);
                                                    $extension = $strings[$count - 1];
                                                    if(in_array($extension,$extensions)){?>
                                                        <img src="<?=$doc['link']?>" class="" style="width: 100%">
                                                    <?}elseif($extension == 'pdf'){?>
                                                        <embed src="<?=$doc['link']?>#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf">
                                                    <? } ?>
                                                    <br>
                                                    <div class="col-12 my-4">
                                                        <a href="<?=isset($doc['link2']) ? $doc['link2'] : $doc['link']?>" class="btn btn-link">Скачать</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?$counter++;?>
                                        <?endforeach;?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </main>


<?
echo \frontend\components\LoginWidget::widget();
?>