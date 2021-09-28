<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Перевод токенов";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
    $tokens = \common\models\Tokens::findOne(['user_id' => $user->id]);
}

$this->registerJs('
    $("#tokentransfersform-sum").keyup(function(){
        percent = parseFloat($("#commission-percent").text());
        tokens = parseFloat($("#tokentransfersform-sum").val());
        tokensPercent = tokens * (percent / 100);
        sum = tokens + tokensPercent;
        $("#final").html(sum);
        $("#tokens-percent").html(tokensPercent);
    });
');
?>
<?
$flashes = Yii::$app->session->allFlashes;
if(!empty($flashes)){
    foreach ($flashes as $key => $flash) {?>
        <div class="alert alert-<?=$key?> alert-dismissible fade show" role="alert">
            <?=$flash?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?}}
?>
    <main class="transaction">
        <div class="container">
            <div class="hgroup">
                <h1 class="h1">Перевод токенов</h1>
                <small style="color: #ff7601;">
                    Уважаемый пользователь! Компания предостерегает,
                    в случае перевода средств с аккаунта на аккаунт для вывода,<br>
                    каждый пользователь несет личную ответственность.
                    Берегитесь несанкционированного доступа к вашему аккаунту.
                </small>
                <div class="hline"></div>
            </div>

            <div class="row">
                <div class="col-lg-10">
                    <p>Количество Ваших токенов: <?=$tokens['balans']?></p>
                    <?if(!empty($pretrans)){

                        ?>
                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-withdraw']); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mt-3">
                                    <p>Код подтверждения перевода был отправлен на вашу электронную почту</p>
                                    <p>Сумма <?=$pretrans['sum']?></p>
                                </div>
                                <div class="form-group">
                                    <?= $form->field($code, 'code')->textInput(['placeholder'=>'Введите код'])->label(false); ?>
                                </div>
                                <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" value="<?=Yii::t('users', 'Подтвердить')?>">
                            </div>
                        </div>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                    <?}else{?>
                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-withdraw']); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mt-3">
                                    <?= $form->field($model, 'username')->textInput(['placeholder'=>'Введите логин получателя'])->label(false); ?>
                                </div>
                                <div class="form-group">
                                    <?= $form->field($model, 'sum')->textInput(['placeholder'=>'Введите количество токенов'])->label(false); ?>
                                </div>
                                <div class="form-group">
                                    <p>Комиссия при переводе: <span id="commission-percent">0.3</span>%</p>
                                    <p>Комиссия: <span id="tokens-percent">0</span></p>
                                    <p>Итого: <span id="final">0</span></p>
                                </div>
                                <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" value="<?=Yii::t('users', 'Перевод')?>">
                            </div>
                        </div>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                    <?}?>

                </div>
            </div>

        </div>
    </main>

<?
$this->registerJs('
    $(window).bind("beforeunload", function(){
             $(".btn").attr("disabled", true);
        });
');
echo \frontend\components\LoginWidget::widget();
?>