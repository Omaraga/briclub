<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = Yii::t('users', 'RESET_PASSWORD');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('/js/register.js',['depends'=>'yii\web\JqueryAsset']);

?>

<div class="register-center-block">
    <div class="register-content fon-main txt-white">
        <div class="register-name center">
            <h4>Новый пароль</h4>
        </div>
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <p class="">Введите новые данные для пароля</p>
        <div class="info__line bg__blue">
            <span class="inf__text"><?=$userName;?></span>
        </div>

        <?= $form->field($model, 'password',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input}<a class="eye" href="" attr-show="1"><i class="fas fa-eye-slash"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'Пароль','id'=>'exampleInputPassword','class'=>'form-control input text__small'])->label('Придумайте пароль',['class'=>'form-label text__small']) ?>
        <?= $form->field($model, 'password_repeat',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input}<a class="eye" href="" attr-show="1"><i class="fas fa-eye-slash"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'Повторите пароль','id'=>'exampleInputPasswordConfirm','class'=>'form-control input text__small'])->label('Повторите пароль',['class'=>'form-label text__small']) ?>


        <div class="d-grid gap-2 modale__body-button mt-1 text-center">

                <input class="btn fon-btn-green btn__small" type="submit" value="<?=Yii::t('users', 'SAVE')?>">
            </div>


        <?php ActiveForm::end(); ?>
    </div>
</div>
