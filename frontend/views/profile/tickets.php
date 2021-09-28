<?php

/* @var $this yii\web\View */
use common\models\MatrixRef;
use common\models\UserPlatforms;
use yii\httpclient\Client;


$this->title = "Тех поддержка";
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$tickets = \common\models\Tickets::find()->where(['user_id'=>$user['id']])->orderBy('id desc')->all();
?>
<?
$flashes = Yii::$app->session->allFlashes;
if(!empty($flashes)){
    foreach ($flashes as $key => $flash) {?>
        <div class="alert alert-<?=$key?> alert-dismissible fade show" role="alert">
            <?=$flash?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?}}
?>
    <style>
        ::-webkit-scrollbar {
            display: none;
        }
        body {
            background: #E5E5E5;
        }
    </style>
    <!-- ======== CONTENT ======== -->
    <main class="teh">
        <div class="container-fluid">
            <h1 class="h1 w5">Тех поддержка</h1>
            <div class="row flex-wrap-reverse justify-content-center">
                <div class="box__left box__left col-12 col-lg-4">
                    <a class="btn btn__blue col-12" href="/profile/tickets/new">
                        <img src="./img/main/plus.svg" alt="">
                        Создать новый запрос
                    </a>
                    <div style="height: 50%;">
                        <ul class="nav nav-pills teh__left-list mb-3" id="pills-tab" role="tablist" style="overflow-y: scroll; max-height: 100%;display: block;">
                            <?foreach ($tickets as $ticket):?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link teh__left-item" href="/profile/tickets/<?=$ticket['id']?>" >
                                        <div class="">
                                            <div class="d-flex justify-content-between">
                                                <div class="">
                                                    <p class="w5 text-dark">Номер: <?=$ticket['id'];?></p>
                                                    <p class="text__small"><?=date('d.m.Y H:i',$ticket['time'])?></p>
                                                </div>
                                                <?if($ticket['status']==3):?>
                                                    <span class="span__yallow">В Обработке</span>
                                                <?elseif ($ticket['status']==2):?>
                                                    <span class="span__blue">Отвечен</span>
                                                <?elseif($ticket['status']==1):?>
                                                    <span class="span__green">Завершен</span>
                                                <?endif;?>

                                            </div>
                                            <span>Тема: <?=$ticket['title']?></span>
                                        </div>
                                    </a>
                                </li>
                            <?endforeach;?>


                        </ul>
                    </div>
                </div>
    </main>


<?
echo \frontend\components\LoginWidget::widget();
?>