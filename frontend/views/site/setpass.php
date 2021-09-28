<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Завершение регистрации";
?>
    <section class="landing">
        <div class="container">
            <div class="">

                <h5><?= Html::encode($this->title) ?></h5>

                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Пароль'])->label(false) ?>
                <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder'=>'Повторите пароль'])->label(false) ?>
                <input class="btn-success btn" type="submit" value="<?=Yii::t('users', 'SAVE')?>">
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
    </section>
<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>