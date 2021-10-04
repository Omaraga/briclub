<?php
/* @var $this yii\web\View */
/* @var $tickets common\models\Tickets[] */
use common\models\Tickets;
$this->title = 'Техподдержка';
?>
<main class="support">
    <h4 class="w7 mb-5">Тех поддержка</h4>

    <a href="/support/create"><button class="fon-btn-blue-100 mb-4 text-white">Создать новый запрос</button></a>

    <?foreach ($tickets as $ticket):?>
        <a class="line txt-mini row" href="/support/view?id=<?=$ticket->id;?>" style="color: #fff;">
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
            <p class="col">Номер: <?=$ticket->id;?></p>
            <p class="col">Тема: <?=$ticket->title;?></p>
            <p class="col"><?=date('d.m.Y H:i', $ticket->time)?></p>
            </a>
        </a>
    <?endforeach;?>
</main>
