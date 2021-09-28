<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Перевод средств";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$user_tokens = \common\models\Tokens::findOne(['user_id'=>$user['id']]);
$tokens = 0;
if(!empty($user_tokens)){
    $tokens = $user_tokens['balans'];
}
$fee = \common\models\TokenFees::findOne(1)['fee_percent'];
$course = \common\models\Changes::findOne(3)['cur2'];
$this->registerJs('
    $("#transfersform-sum").keyup(function(){
        percent = '.$fee.';
        usd = parseFloat($("#transfersform-sum").val());
        tokensPercent = usd * (percent / 100);
        sum = tokensPercent;
        res = sum/'.$course.';
        res = res.toFixed(8);
        $("#transfersform-fee").val(res);
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

    <main class="replenish">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-9 mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="\img/payment/exchange_yellow.svg" alt="">
                            <h1 class="h1 w7 text ml-3">Перевод средств</h1>
                        </div>
                        <small>
                            Уважаемый пользователь! Компания предостерегает,
                            в случае перевода средств с аккаунта на аккаунт для вывода,<br>
                            каждый пользователь несет личную ответственность.
                            Берегитесь несанкционированного доступа к вашему аккаунту.
                        </small>
                    </div>

                    <div class="col-lg-9 mt-4">
                        <div class="block__fon block-cv">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <img src="\img/main/bCV.svg" alt="">
                                    <span class="w5">Баланс</span>
                                </div>
                                <img src="\img/replenish/CV.svg" alt="">
                            </div>
                            <div class="mt-5">
                                <span class="h2 w7">CV</span>
                                <span class="h1 w7 "><?=$user['w_balans'];?></span>
                            </div>
                        </div>
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
                                    <input class="btn btn__small btn__blue button mt-4 col-md-8 mb-5" type="submit" value="<?=Yii::t('users', 'Подтвердить')?>">
                                </div>
                            </div>
                            <?php \yii\widgets\ActiveForm::end(); ?>
                        <?}else{?>
                            <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-withdraw']); ?>
                            <div class="row">
                                <div class="col-lg-6 mt-5">
                                    <div class="form-group mt-3">
                                        <?= $form->field($model, 'username')->textInput(['placeholder'=>'Введите логин получателя'])->label(false); ?>
                                    </div>
                                    <div class="form-group mt-5">
                                        <?= $form->field($model, 'sum')->textInput(['placeholder'=>'Введите сумму перевода'])->label(false); ?>
                                    </div>
<!--                                    <div class="form-group">-->
<!--                                        --><?//= $form->field($model, 'fee')->textInput(['readonly'=>'readonly']); ?>
<!--                                    </div>-->
                                    <input class="btn btn__small btn__blue button mt-4 col-md-8 mb-5" type="submit" value="<?=Yii::t('users', 'Перевод')?>">
                                </div>
                            </div>
                            <?php \yii\widgets\ActiveForm::end(); ?>
                        <?}?>
                    </div>
                </div>
            </div>
    </main>

<?
$this->registerJs('$(window).bind("beforeunload", function(){
    $(".btn").attr("disabled", true);
});
');
echo \frontend\components\LoginWidget::widget();
?>