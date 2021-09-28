<?php
/* @var $this yii\web\View */
/* @var $model \frontend\models\forms\ConvertForm */

$this->title = "Конвертация";

$user = \common\models\User::findOne(Yii::$app->user->id);
$balance = $user['w_balans'];
$p_balance = $user['p_balans'];
?>

<main class="replenish">
    <div class="container-fluid ml-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-9 mt-4">
                    <div class="ml-3 mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="\img/payment/exchange.svg" alt="">
                            <h1 class="h1 w7 text ml-3">Конвертация в CV</h1>
                        </div>
                        <small>
                            Уважаемый пользователь! Вывод средств осуществляется
                            <br>только на верифицированные платежные системы.
                        </small>

                        <p class="mt-5 text__middle">Курс баланса: <span class="w7 h5">1 PV = 1 CV</span></p>
                    </div>

                    <div class="col-lg-9 mt-4">
                        <div class="block__fon block-pv">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <img src="\img/main/bPV.svg" alt="">
                                    <span class="text-white w5">Баланс</span>
                                </div>
                                <img src="\img/replenish/PV.svg" alt="">
                            </div>
                            <div class="mt-5">
                                <span class="h4 w7 text-white mr-2">PV</span>
                                <span class="h2 w7 text-white"><?= $p_balance ?></span>
                            </div>
                        </div>
                        <div class="px-3">
                            <div>
                                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-convert']); ?>
                                <p class="text__middle mt-5">Введите сумму конвертации</p>
                                <div>
                                    <?= $form->field($model, 'sum')->textInput(['placeholder'=>'PV'])->label(false); ?>
                                </div>
                                <button class="btn__small btn__blue button mt-4 col-md-8 mb-5">Конвертировать</button>
                                <?php \yii\widgets\ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

