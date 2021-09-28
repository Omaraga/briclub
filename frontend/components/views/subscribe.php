<?php
$this->registerJs(
    '$("document").ready(function(){
            $("#sub").on("pjax:end", function() {
            $("#subsuccess").css("display","block");
        });
    });
    '

);
?>
<h3 class="h3">Подпишитесь на новости</h3>
<p class="p"><?=$text?></p>
<div class="subs">
    <?php \yii\widgets\Pjax::begin(['id' => 'sub']) ?>
    <?
    $form = \yii\bootstrap\ActiveForm::begin(['id'=>'sub_form','options' => ['data-pjax' => true,'class'=>'input-group form','role'=>'form'],
        'fieldConfig' => [
            'options' => [
                'tag' => false,
            ],
        ],]);
    ?>
    <input type="text" name="Beds6[email]" required class="inptSub" placeholder="E-mail"><button class="btn btn-primary btnFooter" role="button">Подписаться</button>
    <?
    \yii\bootstrap\ActiveForm::end();
    ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>
<p class="p" id="subsuccess" style="display: none">Спасибо! Вы подписались на новости!</p>