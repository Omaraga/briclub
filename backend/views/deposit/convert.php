<?php
/* @var $this yii\web\View */
/* @var $model \backend\models\forms\ConvertPVForm */

$this->title = "Конвертация";
$username = Yii::$app->request->get('username');
$user = \common\models\User::find()->where(['username'=>$username])->one();
$balance = $user['w_balans'];
$p_balance = $user['p_balans'];
?>

<main class="replenish" xmlns="http://www.w3.org/1999/html">
    <div class="container-fluid ml-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-9 mt-4">
                    <div class="ml-3 mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="\img/payment/exchange.svg" alt="">
                            <h1 class="h1 w7 text ml-3">Конвертация в PV</h1>
                        </div>
                        <small>
                            Уважаемый пользователь! Вывод средств осуществляется
                            <br>только на верифицированные платежные системы.
                        </small>

                        <p class="mt-5 text__middle">Курс баланса: <span class="w7 h5">1 CV = 1 PV</span></p>
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
                                <span class="h4 w7 text-white mr-2">CV</span>
                                <span class="h2 w7 text-white"><?= $balance ?></span>
                                </br>
                                <span class="h4 w7 text-white mr-2">PV</span>
                                <span class="h2 w7 text-white"><?= $p_balance ?></span>
                            </div>
                        </div>
                        <div class="px-3">
                            <div>
                                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-convertpv']); ?>
                                <p class="text__middle mt-5">Введите сумму конвертации</p>
                                <div>
                                    <?= $form->field($model, 'sum')->textInput(['placeholder'=>'CV'])->label(false); ?>
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

