<?php

/* @var $this yii\web\View */




$this->title = "Настройки личных данных";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$flash = Yii::$app->getSession()->allFlashes;
$user = Yii::$app->user->identity;
$premium = \common\models\Premiums::findOne(['user_id' => Yii::$app->user->id]);
$userAvatar = false;
if ($premium){
    $userAvatar = $premium->img_source;
}
$verification = \common\models\Verifications::find()->where(['user_id' => $user->id])->one();
$personalMatrix = \common\models\MatrixRef::find()->where(['user_id'=>$user['id']])->orderBy('platform_id desc')->one();
$currTable = intval($personalMatrix['platform_id']);
$currTableChildren = intval($personalMatrix['children']);
$verificationValue = 50;
$activityValue = intval(100 * ((($currTable-1) * 6) +$currTableChildren)/36);
//$this->registerJsFile('/js/jquery.maskedinput.min.js',['depends'=>'yii\web\JqueryAsset']);
/*$this->registerJs('
$("#changedataform-phone").mask("+9 (999) 99-99-999");
');*/
$this->registerJsFile('/js/setting.js',['depends'=>'yii\web\JqueryAsset']);

$this->registerJsFile('/js/cropper.min.js');
$this->registerCssFile('/css/cropper.min.css');
?>
    <style>
        .settings__input{
            width: 360px;
            height: 40px;
            background: #292344;
            border: 1px solid #192233;
            border-radius: 4px;
            margin-top: 12px;
            padding: 11px 16px;
            color: #fff;
        }
    </style>
    <main class="settings">
        <h4 class="w7 mb-5">Настройки</h4>
        <div class="container">
            <?if(!empty($flash['success'])){?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?=$flash['success']?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?}?>
        </div>
        <div class="block-img flex-line mb-5">
            <div class="img-user center">
                <div class="flex-line">
                    <h4><?=mb_substr($user['firstname'],0 , 1)?><?=mb_substr($user['lastname'], 0, 1);?></h4>
                </div>
            </div>
            <div class="rows ml-3">
                <h6 class="w7 mb-2"><?=$user['fio'];?><span class="w5">(Логин: <?=$user['username'] ?>)</span></h6>
                <p class="txt-mini"><?=$user['email'] ?><span class="ml-2"><?=($verification==NULL)?"(не подтвержден)":""?></span></p>
            </div>
        </div>

        <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active <?=($tabName=='profile')?'active':'';?>" id="home-tab" data-toggle="tab" href="#one" role="tab" aria-controls="home" aria-selected="true">Настройки аккаунта</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link <?=($tabName=='safety')?'active':'';?>" id="profile-tab" data-toggle="tab" href="#safety" role="tab" aria-controls="profile" aria-selected="false">Безопасность</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Верификация</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <!-- ======== НАСТРОЙКА АККАУНТА ======= -->

            <div class="tab-pane fade show mb-4 <?=($tabName=='personal')?'active':'';?>"  id="one" role="tabpanel" aria-labelledby="home-tab">
                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-data']); ?>
                    <div class="input-form">
                        <h6 class="mb-2">Имя</h6>
                        <?= $form->field($сhangeDataForm, 'firstname',['options'=>['class'=>'form-group field']])->textInput(['id'=> 'exampleInputFirstName','class'=>'settings__input','attr-curr-page'=>'1', 'value'=>$user['firstname']])->label(false) ?>
                    </div>
                    <div class="input-form">
                        <h6 class="mb-2">Фамилия</h6>
                        <?= $form->field($сhangeDataForm, 'lastname',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Фамилия','id'=> 'exampleInputLastName','class'=>'settings__input','attr-curr-page'=>'1', 'value'=>$user['lastname']])->label(false) ?>
                    </div>
                    <div class="input-form">
                        <h6 class="mb-2">Номер</h6>
                        <?= $form->field($сhangeDataForm, 'phone',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Номер телефона','id'=>'exampleInputPhone','class'=>'settings__input','attr-curr-page'=>'2', 'value'=>$user['phone']])->label(false) ?>
                    </div>
                    <div class="input-form">
                        <h6 class="mb-2">Страна</h6>
                        <?= $form->field($сhangeDataForm, 'country',['options'=>['class'=>'form-group field']])->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Countries::find()->all(),'id','title'),['value'=>$user->country_id, 'class'=>'settings__input'])->label(false); ?>
                    </div>
                    <button class="fon-green-100">Сохранить</button>
                <?php \yii\widgets\ActiveForm::end(); ?>
            </div>
            <!-- ======== БЕЗОПАСНОСТЬ ======== -->
            <div class="tab-pane fade <?=($tabName=='safety')?'active':'';?>" id="safety" role="tabpanel" aria-labelledby="profile-tab">
                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-password', 'options'=>['class'=>'col-8']]); ?>
                <div class="input-form">
                    <h6 class="mb-2">Введите пароль</h6>
                    <?php if ($model->password_hash != '') : ?>
                        <?= $form->field($changePasswordForm, 'old_password',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input}<a class="eye" href="" attr-show="1"><i class="fas fa-eye-slash"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'*************','id'=>'oldPassword','class'=>'settings__input'])->label(false) ?>
                    <?php endif;?>
                </div>
                <div class="input-form">
                    <h6 class="mb-2">Введите новый пароль</h6>
                    <?= $form->field($changePasswordForm, 'new_password',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input}<a class="eye" href="" attr-show="1"><i class="fas fa-eye-slash"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'*************','id'=>'newPassword','class'=>'settings__input'])->label(false) ?>
                </div>
                <div class="input-form">
                    <h6 class="mb-2">Повторите новый пароль</h6>
                    <?= $form->field($changePasswordForm, 'new_password_repeat',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input}<a class="eye" href="" attr-show="1"><i class="fas fa-eye-slash"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'*************','id'=>'newPassword_repeat','class'=>'settings__input'])->label(false) ?>
                </div>
                <button class="fon-green-100">Сохранить</button>
                <?php \yii\widgets\ActiveForm::end(); ?>

            </div>

            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <div class="line between">
                    <a href="verification">
                        <div class="flex-line">
                            <div class="circle fon-green-200 mr-3"></div>
                            <h6>Подверждение почты</h6>
                        </div>
                        <img src="/img/settings/arrow.svg" alt="">
                    </a>
                </div>

                <div class="line between">
                    <a href="verification">
                        <div class="flex-line">
                            <div class="circle fon-blue-100 mr-3"></div>
                            <h6>Подверждение почты</h6>
                        </div>
                        <img src="/img/settings/arrow.svg" alt="">
                    </a>
                </div>

                <div class="line between">
                    <a href="verification">
                        <div class="flex-line">
                            <div class="circle fon-gray-200 mr-3"></div>
                            <h6>Подверждение почты</h6>
                        </div>
                        <img src="/img/settings/arrow.svg" alt="">
                    </a>
                </div>

            </div>
        </div>
    </main>



<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>