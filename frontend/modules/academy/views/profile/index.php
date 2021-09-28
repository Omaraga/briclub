<?php
/* @var $this yii\web\View*/
/* @var $user common\models\User*/
/* @var $changeDataForm frontend\models\forms\ChangeDataForm*/

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Профиль';

$script = <<<JS
    $(document).ready(function (){
        if (window.width)
    });
JS;

?>

<div class="container acc">
    <div class="account-menu">
        <div class="banner-user">
            <div class="header-img center">
                <h6><?=mb_substr($user['firstname'],0 , 1)?><?=mb_substr($user['lastname'], 0, 1);?></h6>
            </div>
            <h5 class="w6"><?=$user['fio'];?></h5>
            <h6><?=$user['email'] ?></h6>

            <div class="banner-user_settings">
                <h6>Логин: <?=$user['username'];?></h6>
            </div>

        </div>
        <div class="account-menu__links">
            <ul>
                <li>
                    <a class=" account-menu__link__active" href="#">
                        <div class="links__text">
                            <span class="">Настройки аккаунта</span>
                            <p class="text__small">Личная Информация, Email</p>
                        </div>
                        <img src="/img/arrow.svg" alt="">
                    </a>
                </li>
                <li>
                    <a class="" href="/academy/profile/security">
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
    <div class="account-setting right-block">
        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-data']); ?>
            <p class="text w7 mb-4 account-setting__size">Настройки аккаунта</p>
            <p class="w5 mb-5" style="font-size: 16px">Личная Информация</p>
            <div class="account-setting_input">
                <?= $form->field($changeDataForm, 'firstname',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Имя','id'=> 'exampleInputFirstName','class'=>'form-control text__small input__default','attr-curr-page'=>'1', 'value'=>$user['firstname']])->label('Имя',['class'=>'form-label text__small ']) ?>
            </div>
            <div class="account-setting_input">
                <?= $form->field($changeDataForm, 'lastname',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Фамилия','id'=> 'exampleInputLastName','class'=>'form-control text__small input__default','attr-curr-page'=>'1', 'value'=>$user['lastname']])->label('Фамилия',['class'=>'form-label text__small']) ?>
            </div>
            <div class="account-setting_input">
                <p class="w5 mb-3" style="font-size: 16px">Email</p>
                <input type="text" id="changedataform-email" class="form-control"  value="<?=$user['email']?>" disabled aria-invalid="false">
            </div>
            <div class="account-setting_input">
                <?= $form->field($changeDataForm, 'phone',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Номер телефона','id'=>'exampleInputPhone','class'=>'form-control text__small input__default','attr-curr-page'=>'2', 'value'=>$user['phone']])->label('Номер',['class'=>'form-label text__small']) ?>
            </div>
            <div class="dropdown">
                <?= $form->field($changeDataForm, 'country',['options'=>['class'=>'form-group field']])->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Countries::find()->all(),'id','title'),['value'=>$user->country_id, 'class'=>'input__default form-control dropdown-bg'])->label('Страна',['class'=>'form-label text__small']); ?>
            </div>
            <div class="change_buttons">
                <button class="btn fon-transparent border-none mr-3 w5" type="reset" id="cancelOwnInfo">Отменить изменения</button>
                <button class="btn__small btn fon-green" type="submit">Сохранить</button>
            </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>