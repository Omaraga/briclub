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
    <main class="personal__area">
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
        <div class="container-fluid">
            <div class="">
                <h2 class="h2 w5 main__title">Личный кабинет</h2>
            </div>
            <div class="row mt-5">
                <div id="user_block" class="col-lg-3 col-sm-4 col-12 text-center">
                    <div class="user">
                        <?if($userAvatar):?>
                            <div class="user__img mb-4" style="background:url('<?=$userAvatar?>') no-repeat;background-size: cover">
                            </div>
                        <?else:?>
                            <div class="user__img mb-4">
                                <i class="far fa-user"></i>
                            </div>
                        <?endif;?>
                        <?if($premium && $premium->is_active == 1):?>
                            <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                            <label>
                                <? echo $form->field($avatarForm, 'imageFile', ['options'=>['style'=>'display:none']])->fileInput(['class'=>'form-control-file upload btn btn__blue btn__small image', 'id'=>'uploadfour', 'name' => 'image'])->label(false);  ?>
                                <label class="btn btn__blue" for="uploadfour">Фото профиля</label>

                                <?if($userAvatar):?>
                                    <a href="/profile/delete-avatar" class="mb-2 btn btn-danger">Удалить</a>
                                <?endif;?>

                            </label>
                            <?php \yii\widgets\ActiveForm::end() ?>
                        <?endif;?>
                        <h6 class=""><?=$user['fio']?></h6>
                        <h6 class=""><?=$user['email']?></h6>

                        <div class="mt-5">
                            <span class="">Рейтинг активности: <span><?=$activityValue;?>%</span></span>
                            <img class="" src="/img/info.svg" alt="">
                        </div>
                        <div class="rating__group mt-3">
                            <? for($i=0; $i<100;$i+=16.7){?>
                                <? if($i < $activityValue){?>
                                    <div class="col rating active"></div>
                                <?}else{?>
                                    <div class="col rating"></div>
                                <?}?>
                            <?}?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-sm-8 col-12">
                    <div class="box__right">
                        <div class="row">
                            <div id="block__userSettings"  class="col-lg-5 col-md-12">
                                <p class="text__middle w5 mb-5 col--12">Настройки</p>
                                <div class="info__block">
                                    <div class="d-flex">

                                        <div class="text__group">
                                            <p class="w5">Информация профиля</p>
                                            <p class="text__mini">Аккаунт  нужно верифицировать для подтверждение личности</p>
                                        </div>
                                    </div>
                                    <a class="btn__white btn" href="/profile/verification" style="display: block">Верификация</a>
                                </div>
                                <div class="">
                                    <ul class="nav nav-tabs tabs__list mt-4" id="myTab" role="tablist">

                                        <li class="nav-item tabs__item" role="presentation">
                                            <a class="nav-link tabs__link hide_button <?=($tabName=='personal')?'active':'';?>" id="home-tab" data-toggle="tab" href="#one" role="tab" aria-controls="home" aria-selected="true">
                                                <span class="btn btn__blue btn__border btn__default"><i class="fas fa-user"></i></span>
                                                <span>
                                                  <span class="w5">Настройки аккаунта</span>
                                                  <p class="text__small m-0">Личная Информация, Email</p>
                                                </span>
                                                <img src="/img/arrow.svg" alt="">
                                            </a>
                                        </li>

                                        <li class="nav-item tabs__item" role="presentation">
                                            <a class="nav-link tabs__link hide_button <?=($tabName=='safety')?'active':'';?>" id="profile-tab" data-toggle="tab" href="#safety" role="tab" aria-controls="profile" aria-selected="false">
                                                <span class="btn btn__blue btn__border btn__default"><i class="fas fa-lock"></i></span>
                                                <span>
                                                  <span class="w5">Безопасность</span>
                                                  <p class="text__small m-0">Изменить пароль</p>
                                                </span>
                                                <img src="/img/arrow.svg" alt="">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-7 visib d-lg-block">
                                <div class="tab-content">
                                    <!-- ======== НАСТРОЙКА АККАУНТА ======= -->
                                    <div class="tab-pane <?=($tabName=='personal')?'active':'';?> tab-modal-setting" id="one" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="">
                                            <div class="box__settings fon">
                                                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-data']); ?>
                                                <div class="row">
                                                    <div class="back" style="width: 100%;">
                                                        <img class="btn__back" src="/img/back.svg" alt="">
                                                        <div class="back_2 text-center">
                                                            <span class="w5 text__middle">Настройка Аккаунта</span>
                                                        </div>
                                                    </div>
                                                    <p class="col-lg-12 mt-4">Личная Информация</p>
                                                    <div class="col">
                                                        <!--                                                            --><?//= $form->field($сhangeDataForm, 'firstname')->textInput(['value'=>$user['firstname']]); ?>
                                                        <?= $form->field($сhangeDataForm, 'firstname',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Имя','id'=> 'exampleInputFirstName','class'=>'form-control text__small input__default','attr-curr-page'=>'1', 'value'=>$user['firstname']])->label('Имя',['class'=>'form-label text__small']) ?>
                                                    </div>
                                                    <div class="col">
                                                        <?= $form->field($сhangeDataForm, 'lastname',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Фамилия','id'=> 'exampleInputLastName','class'=>'form-control text__small input__default','attr-curr-page'=>'1', 'value'=>$user['lastname']])->label('Фамилия',['class'=>'form-label text__small']) ?>
                                                    </div>
                                                    <div class="col-12 my-4">
                                                        <div class="form-group field-changedataform-email has-success">
                                                            <label class="control-label" for="changedataform-email">Email</label>
                                                            <input type="text" id="changedataform-email" class="form-control"  value="<?=$user['email']?>" disabled aria-invalid="false">

                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <?= $form->field($сhangeDataForm, 'phone',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Номер телефона','id'=>'exampleInputPhone','class'=>'form-control text__small input__default','attr-curr-page'=>'2', 'value'=>$user['phone']])->label('Номер',['class'=>'form-label text__small']) ?>
                                                    </div>
                                                    <div class="col-12">
                                                        <?= $form->field($сhangeDataForm, 'country',['options'=>['class'=>'form-group field']])->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Countries::find()->all(),'id','title'),['value'=>$user->country_id, 'class'=>'input__default form-control'])->label('Страна',['class'=>'form-label text__small']); ?>
                                                    </div>
                                                    <div class="my-4 text-end">
                                                        <button class="btn__small btn btn__white btn-cancel" id="cancelOwnInfo" disabled>Отменить изменения</button>
                                                        <button class="btn__small btn btn__blue" type="submit">Сохранить</button>
                                                    </div>
                                                </div>
                                                <?php \yii\widgets\ActiveForm::end(); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ======== БЕЗОПАСНОСТЬ ======== -->
                                    <div class="tab-pane <?=($tabName=='safety')?'active':'';?>" id="safety" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="">
                                            <div class="box__settings fon">
                                                <div class="row">
                                                    <div class="back" style="width: 100%;">
                                                        <img class="btn__back" src="/img/back.svg" alt="">
                                                        <div class="back_2 text-center">
                                                            <span class="w5 text__middle">Безопасность</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 my-4">
                                                        <p class="">Изменить пароль</p>
                                                    </div>
                                                    <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-password', 'options'=>['class'=>'col-8']]); ?>
                                                    <?php if ($model->password_hash != '') : ?>
                                                        <?= $form->field($changePasswordForm, 'old_password',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input}<a class="eye" href="" attr-show="1"><i class="fas fa-eye-slash"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'*************','id'=>'oldPassword','class'=>'form-control text__small input__default'])->label('Введите пароль',['class'=>'form-label text__small']) ?>
                                                    <?php endif;?>
                                                    <?= $form->field($changePasswordForm, 'new_password',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input}<a class="eye" href="" attr-show="1"><i class="fas fa-eye-slash"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'*************','id'=>'newPassword','class'=>'form-control text__small input__default'])->label('Введите новый пароль',['class'=>'form-label text__small']) ?>
                                                    <?= $form->field($changePasswordForm, 'new_password_repeat',['options'=>['class'=>'form-group field position-relative'],'template' => '{label} {input}<a class="eye" href="" attr-show="1"><i class="fas fa-eye-slash"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'*************','id'=>'newPassword_repeat','class'=>'form-control text__small input__default'])->label('Введите новый пароль',['class'=>'form-label text__small']) ?>
                                                    <div class="my-4 text-end">
                                                        <button class="btn__small btn btn__blue" type="submit">Сохранить</button>
                                                    </div>
                                                    <?php \yii\widgets\ActiveForm::end(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style type="text/css">
                img {
                    display: block;
                    max-width: 100%;
                }
                .preview {
                    overflow: hidden;
                    width: 160px;
                    height: 160px;
                    margin: 10px;
                    border: 1px solid red;
                }

            </style>

            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Выберите область фото для отображения</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="img-container">
                                <div class="row">
                                    <div class="col-md-8">
                                        <!--  default image where we will set the src via jquery-->
                                        <img id="image">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="preview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                            <button type="button" class="btn btn-primary" id="crop">Загрузить</button>
                        </div>
                    </div>
                </div>
            </div>
    </main>

<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>