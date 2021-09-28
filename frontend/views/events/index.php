<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;
use yii\web\View;


$this->title = $news['title'];
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$img = $news['link'];
$ticket_types = \common\models\EventTicketTypes::find()->all();

$tickets = \common\models\EventTickets::find()->where(['user_id'=>$user['id']])->all();

?>
    <main class="cours">
        <div class="container">
            <div class="row">
                <main role="main" class="col-12">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center hgroup">
                        <h1 class="h1"><?=$this->title?></h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="text">Дата начала: <?=date('d.m.Y',strtotime($news['start_date']))?></p>
                            <p class="text">Дата окончания: <?=date('d.m.Y',strtotime($news['end_date']))?></p>
                            <div class="row">
                                <div class="col-lg-6">
                                    <img  src="<?=$img?>" style="border-radius: 10px;" class="card-img-top" alt="...">
                                </div>
                                <div class="col-lg-6" style="margin-top: -87px;">

                                    <h3 class="h3">Продажа окончена</h3>
                                    <?
                                    foreach ($ticket_types as $ticket_type) {?>
                                        <div class="row mt-2">
                                            <div class="col-lg-4"><img width="150" src="<?=$ticket_type['link']?>" alt="" class="img"></div>
                                            <div class="col-lg-3">
                                                <p class="text-info">Стоимость:<br> <?=round($ticket_type['price'])?> GRC</p>
                                            </div>
                                            <?if($ticket_type['count']>0){?>
                                            <div class="col-lg-3">
                                                <p >Осталось билетов: <?=$ticket_type['count']?></p>
                                            </div>
                                            <div class="col-lg-2"><a href="/tokens/get?ticket=<?=$ticket_type['id']?>"  class="btn btn-primary">Купить</a></div>
                                            <?}?>
                                        </div>
                                    <?}?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-4">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="perfect-tab" data-toggle="tab" href="#perfect" role="tab" aria-controls="home" aria-selected="true">Описание</a>
                                        </li>
                                        <?if(!empty($tickets)){?>
                                            <li class="nav-item">
                                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Мои билеты</a>
                                            </li>
                                        <?}?>


                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade in active show" id="perfect" role="tabpanel" aria-labelledby="perfect-tab">
                                            <p><?=$news['text']?></p>
                                        </div>
                                        <?if(!empty($tickets)){?>
                                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                <table class="table">
                                                    <thead class="thead-dark">
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Стоимость</th>
                                                        <th scope="col">Дата покупки</th>
                                                        <th scope="col">Скачать</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?
                                                    $i = 0;
                                                    foreach ($tickets as $ticket) {
                                                        $i++;
                                                        ?>
                                                        <tr>
                                                            <th scope="row"><?=$i?></th>
                                                            <td><?=\common\models\EventTicketTypes::findOne($ticket['type_id'])['price']?></td>
                                                            <td><?=date('d.m.Y H:i',$ticket['time'])?></td>
                                                            <td><a href="<?=$ticket['link']?>" target="_blank" class="btn">Скачать</a></td>
                                                        </tr>
                                                        <? } ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        <?}?>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </main>

            </div>
        </div>



    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>