<?php

/* @var $this yii\web\View */

use yii\httpclient\Client;
use yii\web\View;

$this->title = "Вывод средств";

?>
<style>
    @media screen and (max-width: 430px) {
        .form-control{
            width: 100%!important;
        }
    }
</style>
<main class="payment">
        <div class="fon-gray-300 box">
            <div class="banner center">
                <div class="rows text-center">
                    <h5 class="mb-2">Вывод средсв</h5>
                    <h3>PV <?= $user['p_balans']; ?></h3>
                </div>
            </div>
            <p class="txt-mini txt-A3D1 margin-bot-50">Уважаемый пользователь! Вывод средств осуществляется только на верифицированные платежные системы.
                Компания предостерегает, в случае перевода средств с аккаунта на аккаунт для вывода, каждый пользователь несет личную ответственность.</p>

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Perfect Money</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Payeer</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <h5 class="w7 margin-top-38">Введите сумму вывода</h5>
                    <p class="txt-mini margin-bot-24">Комиссия 3%</p>

                    <?
                    if (!empty($error)) {
                        ?>
                        <p class="alert-danger"><?= $error ?></p>
                    <? } ?>
                    <?
                    if (!empty($success)) {
                        ?>
                        <p class="alert-success"><?= $success ?></p>
                    <? } ?>
                    <? if (!empty($pretrans)) {

                        ?>
                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-withdraw']); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mt-3">
                                    <p class="txt-mini">Код подтверждения перевода был отправлен на вашу электронную почту</p>
                                    <p>Сумма <?= $pretrans['sum'] ?></p>
                                </div>
                                <div class="form-group">
                                    <?= $form->field($code, 'code')->textInput(['placeholder' => 'Введите код'])->label(false); ?>
                                </div>
                                <input class="btn btn__small btn__blue button mt-4 col-md-8 mb-5" type="submit"
                                       value="<?= Yii::t('users', 'Подтвердить') ?>">
                            </div>
                        </div>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                    <? } else { ?>
                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-withdraw-perfect']); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mt-3">
                                    <?= $form->field($model, 'account')->textInput(['placeholder' => 'Введите счет'])->widget(\yii\widgets\MaskedInput::className(), [
                                        'mask' => 'U9999999[9]',
                                    ])->label(false); ?>
                                </div>
                                <div class="form-group">
                                    <?= $form->field($model, 'sum')->textInput(['placeholder' => 'Введите сумму'])->label(false); ?>
                                </div>
                                <?= $form->field($model, 'system_id')->hiddenInput(['value' => 2])->label(false); ?>
                                <button type="submit">Вывести</button>
                            </div>
                        </div>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                    <? } ?>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <h5 class="w7 margin-top-38">Введите сумму вывода</h5>
                    <p class="txt-mini margin-bot-24">Комиссия 3%</p>
                    <?
                    if (!empty($error)) {
                        ?>
                        <p class="alert-danger"><?= $error ?></p>
                    <? } ?>
                    <?
                    if (!empty($success)) {
                        ?>
                        <p class="alert-success"><?= $success ?></p>
                    <? } ?>
                    <? if (!empty($pretrans)) {

                        ?>
                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-withdraw']); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mt-3">
                                    <p class="txt-mini">Код подтверждения перевода был отправлен на вашу электронную почту</p>
                                    <p>Сумма <?= $pretrans['sum'] ?></p>
                                </div>
                                <div class="form-group">
                                    <?= $form->field($code, 'code')->textInput(['placeholder' => 'Введите код'])->label(false); ?>
                                </div>
                                <input class="btn btn__small btn__blue button mt-4 col-md-8 mb-5" type="submit"
                                       value="<?= Yii::t('users', 'Подтвердить') ?>">
                            </div>
                        </div>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                    <? } else { ?>
                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-withdraw-perfect']); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mt-3">
                                    <?= $form->field($model, 'account')->textInput(['placeholder' => 'Введите счет'])->widget(\yii\widgets\MaskedInput::className(), [
                                        'mask' => 'U9999999[9]',
                                    ])->label(false); ?>
                                </div>
                                <div class="form-group">
                                    <?= $form->field($model, 'sum')->textInput(['placeholder' => 'Введите сумму. Не меньше $10.'])->label(false); ?>
                                </div>
                                <?= $form->field($model, 'system_id')->hiddenInput(['value' => 2])->label(false); ?>
                                <button type="submit">Вывести</button>
                            </div>
                        </div>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                    <? } ?>
                </div>
            </div>
        </div>
    </main>

