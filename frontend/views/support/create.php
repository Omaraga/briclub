<?php

/* @var $this yii\web\View */
/* @var $ticketForm frontend\models\forms\TicketForm */
/* @var $ticketsType common\models\TicketTypes[] */

$this->title = 'Новый запрос в службу поддержки';
?>
<main class="support">
    <h4 class="w7 mb-5">Тех поддержка</h4>

    <div class="block">
        <h5 class="w7 mb-3">Новый запрос в службу поддержки</h5>
        <hr class="margin-bot-50" style="background: rgba(255, 255, 255, 0.25);">
        <div class="box rows between">
            <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($ticketForm, 'title',['options'=>['class'=>'input-form mb-3']])->textInput(['class'=>'form-control'])->label('Тема'); ?>
                <?= $form->field($ticketForm, 'category',['options'=>['class'=>'mb-3']])->dropDownList($ticketsType,['options'=> ['class' => 'form-control']])->label('Раздел'); ?>

                <?= $form->field($ticketForm, 'text',['options'=>['class'=>'form-floating p-0']])->textarea(['class'=>'form-control', 'id'=>'floatingTextarea'])->label('Сообщение'); ?>
                <?= $form->field($ticketForm, 'file')->fileInput(['class'=>'upload hide', 'id'=>'uploaded'])->label(false);?>

                <button class="btn btn-primary" type="submit">Отправить запрос</button>


            <?php \yii\widgets\ActiveForm::end(); ?>

        </div>
    </div>
</main>




