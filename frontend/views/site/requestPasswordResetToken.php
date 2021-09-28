<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('users', 'REQUEST_PASSWORD_RESET');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    body {
        background: #E5E5E5 !important;
    }
</style>
<div class="box__reg">
    <div class="fon__reg">
        <div class="reg__header">
            <h3 class="w7">Восстановление пароля</h3>
        </div>
        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
            <?= $form->field($model, 'email',['options'=> ['class'=>'field mt-5']])->textInput(['placeholder'=>'E-mail', 'class'=>'form-control'])->label(false) ?>
            <div class="">
                <div class="checkbox__group mt-4">
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-4">{image}</div><div class="col-6">{input}</div></div>',
                    ])->label(false) ?>
                </div>

                <div class="d-flex align-items-center my-4">
                    <img src="/img/info-reg.svg" alt="">
                    <span class="w5 ml-3">Введите email который указали при регистрации</span>
                </div>

                <div class=" mt-1">
                    <input class="fon-grey btn__small" type="submit" value="<?=Yii::t('users', 'SEND')?>" style="width: 100%;">
                </div>

                <div class=" text-center mt-5 text__small">
                    <p class="">У вас есть аккаунт? <a href="/site/login">Войти</a></p>
                </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>

