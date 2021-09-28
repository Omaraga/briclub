<?php

/* @var $this yii\web\View */
/* @var $ticket common\models\Tickets */

use common\models\Tickets;

$this->title = "Просмотр запроса в техподдержку";
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css');
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$user = Yii::$app->user->identity;
$this->registerJs('
$(".file-upload input[type=file]").change(function(){
         let filename = $(this).val().replace(/.*\\\/, "");
         $(this).closest(".file-upload").find(".filename").val (filename);
      
       });
       $(".remove").click(function(){
         $(this).closest(".file-upload").find(".upload")[0].files.value = "";
         $(this).closest(".file-upload").find(".filename").val ("");
       })',  yii\web\View::POS_READY)


?>
    <style>
        ::-webkit-scrollbar {
            display: none;
        }
        .mb-5, .my-5 {
            margin-bottom: 3rem!important;
        }
        .mt-4, .my-4 {
            margin-top: 1.5rem!important;
        }
        .card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0,0,0,.125);
            border-radius: .25rem;
        }
        .card-header:first-child {
            border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
        }
        .card-header {
            padding: .75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0,0,0,.03);
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        .card-body {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            min-height: 1px;
            padding: 1.25rem;
        }
    </style>
    <!-- ======== CONTENT ======== -->
    <main class="teh">
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
        <div class="container-fluid">
            <h1 class="h1 w5">Тех поддержка</h1>
            <div class="row flex-wrap-reverse">
                <div class="box__left box__left__tickets col-12 col-lg-4">
                    <a class="btn btn__blue col-12" href="/profile/tickets/new">
                        <img src="/img/plus.svg" alt="">
                        Создать новый запрос
                    </a>

                    <div style="height: 80%;">
                        <ul class="nav nav-pills teh__left-list mb-3" id="pills-tab" role="tablist" style="overflow-y: scroll; max-height: 100%;display: block;}">
                            <?foreach ($tickets as $item):?>
                                <li class="nav-item left-nav-item" role="presentation">
                                    <a class="nav-link teh__left-item <?=($ticket['id']==$item['id'])?'active':'';?>" href="/profile/tickets/<?=$item['id']?>" >
                                        <div class="">
                                            <div class="d-flex justify-content-between">
                                                <div class="">
                                                    <p class="w5 title">Номер: <?=$item['id'];?></p>
                                                    <p class="text__small"><?=date('d.m.Y H:i',$item['time'])?></p>
                                                </div>
                                                <?if($item['status']==3):?>
                                                    <span class="span__yallow">В Обработке</span>
                                                <?elseif ($item['status']==2):?>
                                                    <span class="span__blue">Отвечен</span>
                                                <?elseif($item['status']==1):?>
                                                    <span class="span__green">Завершен</span>
                                                <?endif;?>

                                            </div>
                                            <span>Тема: <?=$item['title']?></span>
                                        </div>
                                    </a>
                                </li>
                            <?endforeach;?>
                        </ul>
                    </div>
                </div>

                <div class="box__right col-lg-6 col-12">
                    <div class="tab-content right-tab" id="pills-tabContent" style="">
                         <div class="tab-pane active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="d-block d-sm-none">
                                <a class="btn btn__blue" href="/profile/tickets" style="width: 100%; font-size: 1.2rem;"><i class="fa fa-chevron-left" aria-hidden="true" style="margin-right: .8rem;font-size: 1.3rem;"></i> Вернуться</a>
                            </div>
                            <div class="block__right">
                                <div class="row">
                                    <h2 class="h2"><?=$ticket['title'];?></h2>
                                    <div class="">
                                        <div class="group_text">
                                            <span class="w7 title">Номер: <?=$ticket['id'];?></span>
                                            <?if($ticket['status']==3):?>
                                                <span class="span__yallow">В Обработке</span>
                                            <?elseif ($ticket['status']==2):?>
                                                <span class="span__blue">Отвечен</span>
                                            <?elseif($ticket['status']==1):?>
                                                <span class="span__green">Завершен</span>
                                            <?endif;?>
                                        </div>
                                        <span><?=date('d.m.Y H:i',$ticket['time'])?></span>
                                    </div>
                                    <?foreach ($messages as $message):?>
                                        <div class="block_text">
                                            <div class="<?=($message['user_id']==1)?'block_text-header':'block_text-header2';?>">
                                                <span><?=($message['user_id']==1)?'Тех поддержка':$user['fio'];?></span>
                                                <span><?=date('d.m.Y H:i',$message['time'])?></span>
                                            </div>
                                            <div class="block_text-body">
                                                <p class="text__small">
                                                    <?=$message['text'];?>
                                                    <?if($message['is_payment']):?>
                                                        <?if ($ticket->payment_status == Tickets::PAYMENT_STATUS_PAYED):?>
                                                            <button class="btn btn-success">Оплачено</button>
                                                        <?elseif ($ticket->payment_status == Tickets::PAYMENT_STATUS_NEED_PAY):?>
                                                            <a href="/profile/pay-bill?code=<?=\common\models\Bills::findOne($ticket->bill_id)['code']?>" class="btn btn__blue">Оплатить</a>
                                                        <?endif;?>

                                                    <?endif;?>

                                                    <?if(!empty($message['link'])){?>
                                                            <p><a href="<?=$message['link']?>" class="btn btn__blue">Скачать файл</a></p>

                                                    <?}?>
                                                </p>
                                            </div>
                                        </div>
                                    <?endforeach;?>
                                    <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                                        <label for="basic-url" class="form-label text__small mt-3">Сообщение</label>
                                        <?= $form->field($messageForm, 'text',['options'=>['class'=>'form-floating p-0']])->textarea(['class'=>'form-control', 'id'=>'floatingTextarea'])->label(false); ?>
                                        <label for="basic-url" class="form-label text__small mt-3">Файл</label>
                                        <div class="mt-4 ">
                                            <div class="file-upload">
                                                <div class="mt-2 fild__name-group">
                                                    <input type="text" id="filenameed" class="filename fild__name-group" disabled>
                                                    <span class="remove">X</span>
                                                </div>
                                                <label>
                                                    <?= $form->field($messageForm, 'file')->fileInput(['class'=>'upload hide', 'id'=>'uploaded'])->label(false);?>
                                                    <label class="btn btn__blue" for="uploaded">Выбрать файл</label>
                                                </label>

                                            </div>
                                        </div>
                                        <div class="">
                                            <button class="btn btn__blue btn__small col-12 mt-4" type="submit">Отправить запрос</button>
                                        </div>
                                    <?php \yii\widgets\ActiveForm::end(); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>