<?php
$this->registerJs(
    '$("document").ready(function(){
            $("#comment").on("pjax:end", function() {
            $("#comment1").css("display","none");
            $("#comment2").css("display","none");
            $("#commenttext").css("display","none");
            $("#commentsuccess").css("display","block");
        });
    });
    '

);
?>
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="row">
                    <div class="col-xs-12">
                        <h5 class="modal-title h2" id="exampleModalLabel">Оставить отзыв</h5>
                        <p class="p" id="commenttext">Заполните форму и наш менеджер свяжется с вами</p>

                    </div>
                </div>
                <div class="row" id="commentsuccess" style="display:none;padding: 50px 0;">
                    <div class="col-xs-12">
                        <p class="text">
                            Спасибо, Ваш отзыв принят!
                        </p>
                    </div>
                </div>
            </div>
            <?php \yii\widgets\Pjax::begin(['id' => 'comment']) ?>
            <?
            $form = \yii\bootstrap\ActiveForm::begin(['id'=>'comment_form','options' => ['data-pjax' => true,'class'=>'modalForm','role'=>'form'],
                'fieldConfig' => [
                    'options' => [
                        'tag' => false,
                    ],
                ],]);
            ?>
            <div class="modal-body" id="comment1">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <input type="text" class="inpt form-control" required name="Beds2[title]" placeholder="Введите имя">
                            <input type="text" class="inpt form-control" required name="Beds2[text]" placeholder="Ваш отзыв">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="comment2">
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-default" >Оставить отзыв</button>
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
