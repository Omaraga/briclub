<?php

/* @var $this yii\web\View */

$this->title = "Библиотека";
use common\models\Audios;
use common\models\User;

$user_db = User::findOne(Yii::$app->user->identity['id']);
$active = false;
$is_agent = false;
if ($user_db['activ'] == 1){
    $active = true;
}
if ($user_db['is_agent'] == 1){
    $is_agent = true;
}
?>
    <style>
        ::-webkit-scrollbar {
            display: none;
        }

    </style>
    <main class="event">
        <div class="container-fluid">
            <div class="">
                <h1 class="h1 text-left">Онлайн обучение в Shanyrak Plus</h1>
            </div>
            <div class="row mt-5">
                <div class="col-lg-4" style="height: 70vh" id="row">
                    <ul class="left__box-list nav" style="overflow-y: scroll; height: 100%;display: block;">
                        <?$counter = 0;?>
                        <?foreach ($books as $course):?>
                            <li class="left__box-item col-12" attr-id-tab = "book-<?=$course['id'];?>"><a class="<?=($counter==0)?'active':'';?>" href="#book-<?=$course['id'];?>" data-toggle="tab">
                                    <img src="<?=$course['link2'];?>" alt="" style="width: 30%">
                                    <div class="text__group">
                                        <p class="text__middle w5"><?=$course['title'];?></p>
                                    </div>
                                </a>
                            </li>
                            <?$counter++;?>
                        <?endforeach;?>
                    </ul>
                </div>

                <div class="col-lg-8">
                    <div class="box__right">
                        <div class="row">
                            <div class="tab-content col-lg-9">
                                <?$counter = 0;?>
                                <?foreach ($books as $course):?>
                                    <?
                                    $audios = Audios::find()->where(['lib_id'=>$course['id']])->count();
                                    if($audios>0){
                                        $but = "Слушать";
                                        $link = "/library/".$course['id'];
                                    }else{
                                        $but = "Скачать";
                                        $link = $course['link'];
                                    }

                                    ?>
                                    <div class="tab-pane <?=($counter==0)?'active':'';?>" id="book-<?=$course['id'];?>">
                                        <img class="col-12" src="<?=$course['link2'];?>" style="width: 100%">
                                        <div class="col-12 row my-3">
                                            <div class="handbut col-12">
                                                <h2 class="col-xl-12 h2 mb-3 " ><?=$course['title'];?></h2>
                                            </div>
                                            <div class="col-8 col-sm-5 mb-5 mt-2">
                                                <?if($active):?>
                                                    <a href="<?=$link;?>" class="btn__blue btn__normal" type="button"><?=$but;?></a>

                                                <?elseif(!$is_agent):?>
                                                    <a href="/profile/get-course?program=2" class="btn__blue btn__normal" type="button">Купить курс</a>
                                                <?endif;?>
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
    </main>
<?
echo \frontend\components\LoginWidget::widget();
?>