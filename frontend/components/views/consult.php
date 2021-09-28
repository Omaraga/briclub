<?php
$this->registerJs(
    '$("document").ready(function(){
            $("#consult").on("pjax:end", function() {
            $("#consult1").css("display","none");
            $("#consult2").css("display","none");
            $("#consulttext").css("display","none");
            $("#consultsuccess").css("display","block");
        });
    });
    '

);
?>
<div class="modal fade" id="consultModal" tabindex="-1" role="dialog" aria-labelledby="consultModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="row">
                    <div class="col-xs-12">
                        <h5 class="modal-title h2" id="exampleModalLabel">Получить консультацию</h5>
                        <p class="p" id="consulttext">Заполните форму и наш менеджер свяжется с вами</p>

                    </div>
                </div>
                <div class="row" id="consultsuccess" style="display:none;padding: 50px 0;">
                    <div class="col-xs-12">
                        <p class="text">
                            Спасибо, Ваша заявка принята! С Вами обязательно свяжутся в ближайшее время!
                        </p>
                    </div>
                </div>
            </div>
            <?php \yii\widgets\Pjax::begin(['id' => 'consult']) ?>
            <?
            $form = \yii\bootstrap\ActiveForm::begin(['id'=>'consult_form','options' => ['data-pjax' => true,'class'=>'modalForm','role'=>'form'],
                'fieldConfig' => [
                    'options' => [
                        'tag' => false,
                    ],
                ],]);
            ?>
            <div class="modal-body" id="consult1">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <input type="text" class="inpt form-control" required name="Beds4[title]" placeholder="Введите имя">
                            <input type="text" class="inpt form-control" required name="Beds4[tel]" placeholder="Телефон">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="consult2">
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-default" >Получить консультацию</button>
                    </div>
                </div>
            </div>
            <?
            \yii\bootstrap\ActiveForm::end();
            ?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>
