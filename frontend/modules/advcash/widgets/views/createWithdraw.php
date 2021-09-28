<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->registerJs(
    '$("document").ready(function(){
        $("#advcash-withdraw").on("pjax:start", function() {
            var btn = $(this).find("form button");
            btn.data("old-name", btn.text); 
            btn.prop("disabled", true).text("Отправка запроса...");
        });
        $("#advcash-withdraw").on("pjax:end", function() {
            var btn = $(this).find("form button");
            $(this).find("form button").prop("disabled", false).text(btn.data("old-name"));
        });
        $("#advcashwithdraw-sum").change(function(){
            $("#advcash_comission > span").text($(this).val() * 0.97);
        });
    });'
);

Pjax::begin(['enablePushState' => false, 'id' => 'advcash-withdraw']);

if (!$result):
    $form = ActiveForm::begin([
        'action' => Url::to(['/advcash/advcash-api/create-withdraw']),
        'options' => ['data-pjax' => true]
    ]);
    ?>
        <?= $form->field($model, 'sum') ?>
        <?= $form->field($model, 'payment_mail') ?>
        
        <?php if (!empty($errors)) {
            echo "<div class='text-red'>";
            foreach ($errors as $key => $value) {
                if (is_array($value)) echo implode("<br>", $value);
                else echo $value;
                echo "<br>";
            }
            echo "</div>";
        }?>

        <p id="advcash_comission">Сумма с учетом комиссии: <span></span> Uc</p>

        <?= Html::submitButton('Вывести', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end();
else:?>
    <br>
    <p class="text-green">Заявка успешно создана</p>
<?php
endif;
Pjax::end(); ?>
