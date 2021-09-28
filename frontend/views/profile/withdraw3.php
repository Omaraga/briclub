<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;
use yii\web\View;
use common\models\Actions;
use common\models\Withdraws;


$this->title = "Вывод средств";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$user = \common\models\User::findOne(Yii::$app->user->identity['id']);
$this->registerJs('
   
');

$userIncome = Actions::find()->where(['user_id' => $user['id']])->andWhere(['type' => [7, 105]])->sum('sum');
$userWithdraw = Withdraws::find()->where(['user_id' => $user['id']])->andWhere(['status' => [1,4]])->sum('sum');
if (!$userWithdraw){
    $userWithdraw = 0;
}
$availableWithdraw = $userIncome - $userWithdraw;

//$this->registerJsFile('/js/jquery.maskedinput.min.js',['depends'=>'yii\web\JqueryAsset']);

?>


    <main class="replenish">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-9 mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="/img/payment/output.svg" alt="">
                            <h1 class="h1 w7 text ml-3">Вывод средств</h1>
                        </div>
                        <small>
                            Уважаемый пользователь! Вывод средств осуществляется только на верифицированные платежные системы.
                            <br>
                            Компания предостерегает, в случае перевода средств с аккаунта на аккаунт для вывода,<br>
                            каждый пользователь несет личную ответственность.
                        </small>
                    </div>

                    <div class="col-lg-9 mt-4">
                        <div class="block__fon block-pv mb-4">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <img src="\img/main/bPV.svg" alt="">
                                    <span class="text-white w5">Агентский баланс</span>
                                </div>
                                <img src="\img/replenish/PV.svg" alt="">
                            </div>
                            <div class="mt-5">
                                <span class="h4 w7 text-white mr-2">PV</span>
                                <span class="h2 w7 text-white"><?= $user['p_balans']; ?></span>
                            </div>
                        </div>
                        <!--<div class="block__fon">
                            <div class="">
                                <img src="/img/dollar.svg" alt="">
                                <span class="text-white">Баланс</span>
                            </div>
                            <div class="">
                                <span class="h4 w7 text-white">CV</span>
                                <span class="h3 w7 text-white"><?/*=$user['w_balans'];*/?></span>
                            </div>
                            <br>
                            <div>
                                <span class="h5 w7 text-white">Ваша прибыль: <?/*=$userIncome;*/?> CV</span><br>
                                <span class="h5 w7 text-white">Всего выведено: <?/*=$userWithdraw;*/?> CV</span><br>
                                <span class="h5 w7 text-white">Лимит на вывод средств: <span style="color: yellow"><?/*=$availableWithdraw;*/?> CV</span></span>
                            </div>
                        </div>-->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link " id="home-tab"  href="/profile/withdraw?system=2" role="tab" aria-controls="home" aria-selected="true">PerfectMoney</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Payeer</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h2 class="h2 mb-0">Payeer</h2>
                                <p>Комиссия: 3%</p>
                                <?
                                if(!empty($error)){?>
                                    <p class="alert-danger"><?=$error?></p>
                                <?}?>
                                <?
                                if(!empty($success)){?>
                                    <p class="alert-success"><?=$success?></p>
                                <?}?>
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
                                    <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-withdraw-advcash']); ?>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mt-3">
                                                <?= $form->field($model, 'account')->textInput(['placeholder'=>'Введите счет'])->label(false); ?>
                                            </div>
                                            <div class="form-group">
                                                <?= $form->field($model, 'sum')->textInput(['placeholder'=>'Введите сумму. Не меньше $10.'])->label(false); ?>
                                            </div>
                                            <?= $form->field($model, 'system_id')->hiddenInput(['value'=>1])->label(false); ?>
                                            <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" value="<?=Yii::t('users', 'Вывод')?>">
                                        </div>
                                    </div>
                                    <?php \yii\widgets\ActiveForm::end(); ?>
                                <?}?>


                            </div>
                        </div>

                    </div>
                </div>

            </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>