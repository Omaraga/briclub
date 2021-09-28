<?php

/* @var $this yii\web\View */



$this->title = "Новый запрос в службу поддержки";

$user = Yii::$app->user->identity;
$cats_db = \common\models\TicketTypes::find()->all();
$cats = array();
$fees = array();
foreach ($cats_db as $item) {
    $cats[$item['id']] = $item['title'];//." (Комиссия: ".$item['fee_token']." GRC)";
    $fees[$item['id']] = ['data-fee'=>$item['fee_token']];
}
$user_tokens = \common\models\Tokens::findOne(['user_id'=>$user['id']]);
$tokens = 0;
if(!empty($user_tokens)){
    $tokens = $user_tokens['balans'];
}
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css');
$this->registerJs('
$(".file-upload input[type=file]").change(function(){
         let filename = $(this).val().replace(/.*\\\/, "");
         $(this).closest(".file-upload").find(".filename").val (filename);
      
       });
       $(".remove").click(function(){
         $(this).closest(".file-upload").find(".upload")[0].files.value = "";
         $(this).closest(".file-upload").find(".filename").val ("");
       })',  yii\web\View::POS_READY);

$this->registerJs('
$( "#selectCategory" ).change(function() {
             
              fee = $(this).find(\':selected\').data(\'fee\')
              console.log(fee);
             
              $( "#ticketform-tokens" ).val(fee);
            
        });

');
?>
<style>
        ::-webkit-scrollbar {
            display: none;
        }
</style>
    <!-- ======== CONTENT ======== -->
<main class="teh">
    <div class="container-fluid">
        <h1 class="h1 w5">Тех поддержка</h1>
        <div class="row flex-wrap-reverse justify-content-center">
            <div class="box__left box__left col-12 col-lg-4">
                <a class="btn btn__blue col-12" href="/profile/tickets/new">
                    <img src="/img/plus.svg" alt="">
                    Создать новый запрос
                </a>

                <div style="height: 60%;">
                    <ul class="nav nav-pills teh__left-list mb-3" id="pills-tab" role="tablist" style="overflow-y: scroll; max-height: 100%;display: block;}">
                        <?foreach ($tickets as $item):?>
                            <li class="nav-item left-nav-item" role="presentation">
                                <a class="nav-link teh__left-item" href="/profile/tickets/<?=$item['id']?>" >
                                    <div class="">
                                        <div class="d-flex justify-content-between">
                                            <div class="">
                                                <p class="w5">Номер: <?=$item['id'];?></p>
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

            <div class="box__right col-lg-6 col-md-12 col-12">
                <div class="tab-content right-tab" id="pills-tabContent">
                    <div class="tab-pane show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="block__right">
                            <div class="row">
                                <h2 class="h2">Новый запрос в службу поддержки</h2>
<!--                                <p>Ваши токены: --><?//=$tokens?><!-- GRC <a href="/tokens/get" class="btn btn-link" target="_blank">Пополнить</a></p>-->
                                <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                                    <label for="basic-url" class="form-label text__small">Тема</label>
                                    <?= $form->field($ticketForm, 'title',['options'=>['class'=>'mb-3']])->textInput(['class'=>'form-control'])->label(false); ?>

                                    <label for="selectCategory" class="form-label text__small mt-3">Раздел</label>
                                    <?= $form->field($ticketForm, 'category',['options'=>['class'=>'mb-3']])->dropDownList($cats,[
                                        'options' => $fees,
                                        'prompt' => 'Выберите категорию',
                                        'class'=>'form-select',
                                        'id'=>'selectCategory',
                                    ])->label(false); ?>

<!--                                    --><?//= $form->field($ticketForm, 'tokens')->textInput(['readonly'=>'readonly', 'class'])->label('Комиссия',['class'=>'form-label text__small mt-3']); ?>

                                    <label for="basic-url" class="form-label text__small mt-3">Сообщение</label>
                                    <?= $form->field($ticketForm, 'text',['options'=>['class'=>'form-floating p-0']])->textarea(['class'=>'form-control', 'id'=>'floatingTextarea'])->label(false); ?>

                                    <label for="basic-url" class="form-label text__small mt-3">Файл</label>
                                    <div class="mt-4 ">
                                        <div class="file-upload">
                                            <div class="mt-2 fild__name-group">
                                                <input type="text" id="filenameed" class="filename fild__name-group" disabled>
                                                <span class="remove">X</span>
                                            </div>
                                            <label>
                                                <?= $form->field($ticketForm, 'file')->fileInput(['class'=>'upload hide', 'id'=>'uploaded'])->label(false);?>
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