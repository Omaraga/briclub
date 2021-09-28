<?php
$this->registerJs(
    '$("document").ready(function(){
            $("#con").on("pjax:end", function() {
            $("#conn").css("display","none");
            $("#check").css("display","none");
            $("#consuccess").css("display","block");
        });
    });
    '

);
?>


<div class="formPackWrap pull-right">
    <?php \yii\widgets\Pjax::begin(['id' => 'con']) ?>
    <?
    $form = \yii\bootstrap\ActiveForm::begin(['id'=>'con_form','options' => ['data-pjax' => true,'class'=>'formPack','role'=>'form'],
        'fieldConfig' => [
            'options' => [
                'tag' => false,
            ],
        ],]);
    ?>

        <h2 class="h2">Записаться на курс </h2>
        <input type="text" name="Beds3[tel]" class="inpt" placeholder="Телефон">
        <div class="clearfix"></div>
        <label for="check" class="labelCkeck">
            <input type="checkbox" id="check" checked class="checkBox pull-left" value="a2">
            <span class="textCheck" id="conn">Я согласен с условиями обработки персональных данных</span>
            <span class="textCheck"  id="consuccess" style="display: none">Спасибо, Ваша заявка принята! С Вами обязательно свяжутся в ближайшее время!</span>
        </label>

        <button class="btn btn-default">Записаться</button>
    <?
    \yii\bootstrap\ActiveForm::end();
    ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>