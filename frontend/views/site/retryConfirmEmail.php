<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('users', 'EMAIL_CONFIRMATION');
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="login-full-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div id="login-box">
                    <div id="login-box-holder">
                        <div class="row">
                            <div class="col-xs-12">
                                <header id="login-header">
                                    <div class="center">
                                        <img src="/img/logo.svg" alt="">
                                    </div>
                                </header>
                                <div id="login-box-inner">
                                    <?php $form = ActiveForm::begin(['id' => 'retry-confirm-email-form']); ?>
                                    <?= $form->field($model, 'email')->textInput(['placeholder'=>'E-mail', 'class'=>'input'])->label(false) ?>
                                    <div class="form-group">
                                        <div class="g-recaptcha" data-sitekey="6Le_8x4UAAAAAI1-lV1rwMepHkh7PbrlrsEz5LUt"></div>
                                    </div>
                                    <input class="btn fon-btn-green" type="submit" value="<?=Yii::t('users', 'SEND')?>">
                                    <?php ActiveForm::end(); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>