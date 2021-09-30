<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('users', 'REQUEST_PASSWORD_RESET');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="register-center-block">

    <div class="register-content fon-main txt-white">
        <div class="register-name center">
            <h4>Восстановление пароля</h4>
        </div>
        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
            <?= $form->field($model, 'email',['options'=> ['class'=>'field mt-5']])->textInput(['placeholder'=>'E-mail', 'class'=>'form-control'])->label(false) ?>
            <div class="">
                <div class="checkbox__group mt-4">
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-5">{image}</div><div class="col-7">{input}</div></div>',
                    ])->label(false) ?>
                </div>

                <div class="d-flex align-items-center my-4">
                    <img src="/img/info-reg.svg" alt="">
                    <span class="w5 ml-3">Введите email который указали при регистрации</span>
                </div>

                <div class=" mt-1">
                    <input class="btn fon-btn-green btn__small" type="submit" value="<?=Yii::t('users', 'SEND')?>" style="width: 100%;">
                </div>

                <div class="center-line mt-4">
                    <p class="txt-A3D1">У вас есть аккаунт? <a class="txt-green w4" href="/site/login">Войти</a></p>
                </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>

