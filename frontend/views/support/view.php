<?php
/* @var $this yii\web\View */
/* @var $ticket common\models\Tickets */
/* @var $messages common\models\Messages[] */
/* @var $messageForm frontend\models\forms\MessageForm */

use common\models\Tickets;

$this->title = $ticket->title;
?>

<main class="support">
    <h4 class="w7 mb-5">Тех поддержка</h4>

    <div class="block">
        <div class="between">
            <div class="d-flex txt-mini">
                <p class="mr-2">Тема:</p>
                <p><?=$ticket->title;?></p>
            </div>
            <?if($ticket->status == Tickets::STATUS_CLOSE):?>
                <div class="flex-line txt-green-200 col">
                    <div class="circle mr-2 fon-green-200"></div>
                    <p>Завершен</p>
                </div>
            <?elseif($ticket->status == Tickets::STATUS_ANSWERED):?>
                <div class="flex-line txt-blue col">
                    <div class="circle mr-2 fon-blue-100"></div>
                    <p>Отвечен</p>
                </div>
            <?else:?>
                <div class="flex-line txt-yallow col">
                    <div class="circle mr-2 fon-yallow-100"></div>
                    <p>В обработке</p>
                </div>
            <?endif;?>
        </div>
        <div class="between txt-blue-200 my-2">
            <p>Номер: <span class="ml-2"><?=$ticket->id;?></span></p>
            <p><?=date('d.m.Y H:i',$ticket->time);?></p>
        </div>
        <hr class="margin-bot-50" style="background: rgba(255, 255, 255, 0.25);">
        <div class="box rows between txt-mini" style="height: 30vh; overflow-y: scroll;">
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
        </div>
        <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <label for="basic-url" class="form-label text__small mt-3">Сообщение</label>
        <?= $form->field($messageForm, 'text',['options'=>['class'=>'form-floating p-0']])->textarea(['class'=>'form-control', 'id'=>'floatingTextarea'])->label(false); ?>
        <?= $form->field($messageForm, 'file')->fileInput(['class'=>'upload hide', 'id'=>'uploaded'])->label(false);?>
        <div class="">
            <button class="btn btn-primary" type="submit">Отправить сообщение</button>
        </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</main>


