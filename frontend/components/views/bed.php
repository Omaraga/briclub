<?php
$this->registerJsFile('https://widget.cloudpayments.ru/bundles/cloudpayments');

$this->registerJs(
    '$("document").ready(function(){
            $("#bed-p").on("pjax:end", function() {
           
            $("#bed-text").css("display","none");
            $("#bed-p").css("display","none");
            $("#bed-success").css("display","block"); 
        });
    });
    
    
    '

);
?>
<div class="modal fade" id="bedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bedModalLabel">Купить курс</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                    if($mes == false){?>
                        <?php \yii\widgets\Pjax::begin(['id'=>'bed-p']);?>
                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'bed-form','options'=>['data-pjax'=>true]]); ?>
                        <?= $form->field($model, 'title',['options'=>['class'=>'form-group']])->textInput(['value'=>$user['fio'],'placeholder'=>'Ваше имя',['options'=>['class'=>'form-control']]])->label(false) ?>
                        <?= $form->field($model, 'email',['options'=>['class'=>'form-group']])->textInput(['value'=>$user['email'],'placeholder'=>'E-mail',['options'=>['class'=>'form-control']]])->label(false) ?>
                        <?= $form->field($model, 'tel',['options'=>['class'=>'form-group']])->textInput(['value'=>$user['phone'],'placeholder'=>'Телефон',['options'=>['class'=>'form-control']]])->label(false) ?>
                        <button  type="submit" class="btn btn-primary float-left">Оставить запрос</button>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                        <?php \yii\widgets\Pjax::end();?>
                    <?}else{?>
                        <p>Ваша заявка принята! С Вами свяжутся в ближайшее время!</p>
                    <?}
                ?>
                <button  type="submit" class="btn btn-link float-right" <?if(Yii::$app->user->isGuest){?>data-target="#letauthModal" data-toggle="modal" <?}else{?>id="checkout" <?}?>data-dismiss="modal" >Оплатить картой</button>
                <p id="bed-success" style="display: none;">
                    Ваша заявка принята! С Вами свяжутся в ближайшее время!
                </p>
            </div>
        </div>
    </div>
</div>