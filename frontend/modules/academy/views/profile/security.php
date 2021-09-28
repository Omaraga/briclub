<?php
/* @var $this yii\web\View*/
/* @var $user common\models\User*/
/* @var $changePasswordForm frontend\models\forms\ChangeDataForm*/

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Безопасность';
?>

<div class="container acc">
    <div class="account-menu">
        <div class="banner-user">
            <div class="header-img center">
                <h6><?=mb_substr($user['firstname'],0 , 1)?><?=mb_substr($user['lastname'], 0, 1);?></h6>
            </div>
            <h5 class="w6"><?=$user['fio']; ?></h5>
            <h6><?=$user['email']; ?></h6>

            <div class="banner-user_settings">
                <h6>Логин: <?=$user['username']; ?></h6>
            </div>

        </div>
        <div class="account-menu__links">
            <ul>
                <li>
                    <a class=" " href="/academy/profile">
                        <div class="links__text">
                            <span class="">Настройки аккаунта</span>
                            <p class="text__small">Личная Информация, Email</p>
                        </div>
                        <img src="/img/arrow.svg" alt="">
                    </a>
                </li>
                <li>
                    <a class="account-menu__link__active" href="#">
                        <div class="links__text">
                            <span class="">Безопасность</span>
                            <p class="text__small">Изменить пароль</p>
                        </div>
                        <img src="/img/arrow.svg" alt="">
                    </a>
                </li>
                <li>
                    <a class="" href="/academy/profile/verification">
                        <div class="links__text">
                            <span class="">Верификация</span>
                            <p class="text__small">Подтверждение аккаунта</p>
                        </div>
                        <img src="/img/arrow.svg" alt="">
                    </a>
                </li>
            </ul>
        </div>

    </div>
    <div class="account-setting">
        <p class="text w7 mb-4 account-setting__size">Безопасность</p>
        <p class="text w5 mb-5" style="font-size: 16px">Изменить пароль</p>
        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-password']); ?>

        <div class="account-setting_input account-setting_input__text_style">
            <?php if ($user->password_hash != '') : ?>
                <?= $form->field($changePasswordForm, 'old_password',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input} {error}{hint}'])->passwordInput(['placeholder'=>'*************','id'=>'oldPassword','class'=>'form-control text__small input__default'])->label('Введите пароль',['class'=>'form-label text__small']) ?>
            <?php endif;?>
        </div>
        <div class="account-setting_input account-setting_input__text_style">
            <?= $form->field($changePasswordForm, 'new_password',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input} {error}{hint}'])->passwordInput(['placeholder'=>'*************','id'=>'newPassword','class'=>'form-control text__small input__default'])->label('Введите новый пароль',['class'=>'form-label text__small']) ?>
        </div>
        <div class="account-setting_input account-setting_input__text_style">
            <?= $form->field($changePasswordForm, 'new_password_repeat',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input} {error}{hint}'])->passwordInput(['placeholder'=>'*************','id'=>'newPassword_repeat','class'=>'form-control text__small input__default'])->label('Повторите новый пароль',['class'=>'form-label text__small']) ?>
        </div>
        <div class="change_buttons">
            <button class="btn__small btn fon-green" type="submit">Сохранить</button>
        </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>