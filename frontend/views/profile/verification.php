<?php

use common\models\Verifications;

/* @var $this yii\web\View */
$verificationValue = 50;

$this->title = "Верификация аккаунта";
$flash = Yii::$app->getSession()->allFlashes;
$user = Yii::$app->user->identity;
$premium = \common\models\Premiums::findOne(['user_id' => Yii::$app->user->id]);
$userAvatar = false;
if ($premium) {
    $userAvatar = $premium->img_source;
}
$personalMatrix = \common\models\MatrixRef::find()->where(['user_id' => $user['id']])->orderBy('platform_id desc')->one();
$currTable = intval($personalMatrix['platform_id']);
$currTableChildren = intval($personalMatrix['children']);
$verificationValue = 50;
$activityValue = intval(100 * ((($currTable - 1) * 6) + $currTableChildren) / 36);
$ver = \common\models\Verifications::find()->where(['user_id' => $user['id']])->one();
$this->registerJsFile('/js/setting.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/js/resizeImage.js', ['depends' => 'yii\web\JqueryAsset']);
if (Yii::$app->session->has('verification')) {
    $filesFromSession = Yii::$app->session->get('verification');
} else {
    $filesFromSession = ['file1' => null, 'file2' => null, 'file3' => null];
}
?>


    <main class="personal__area" id="personalArea">
        <div class="container">
            <? if (!empty($flash['success'])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $flash['success'] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <? } ?>
        </div>
        <div class="container-fluid">
            <input type="file" style="display: none" id="verificationFileInput" data-name="" accept=".jpg, .jpeg, .png">
            <div class="">
                <h2 class="h2 main__title w5">Личный кабинет</h2>
            </div>
            <div class="row mt-5">
                <div class="box__right">
                    <div class="tab-content">
                        <? if ($model->stage == Verifications::STAGE_SEND_EMAIL): ?>
                            <div class="tab-pane <?= ($model->stage == 0) ? 'active' : ''; ?>" id="two" role="tabpanel"
                                 aria-labelledby="home-tab">
                                <div class="text-center">
                                    <div class="box__settings-verefik fon">
                                        <h4 class="w5 mb-5 text-white">Верификация вашей электронной почты</h4>
                                        <p>Подтвердите ваш почтовый адрес для прохождения верификации</p>
                                        <div class="col-12 ">
                                            <? $formEmail = \yii\widgets\ActiveForm::begin(['id' => 'form-email-send']); ?>
                                            <?= $formEmail->field($sendEmailModel, 'typeReq')->hiddenInput(['value' => 'send'])->label(false); ?>
                                            <button class="btn fon-btn-green" type="submit">Подтвердить почту
                                            </button>
                                            <? \yii\widgets\ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? elseif ($model->stage == Verifications::STAGE_CHECK_EMAIL): ?>
                            <div class="tab-pane <?= ($model->stage == 1) ? 'active' : ''; ?>" id="big_input"
                                 role="tabpanel" aria-labelledby="profile-tab">
                                <div class="">
                                    <div class="box__settings fon text-center">
                                        <div class="">
                                            <h5 class="text-white col-12">Верификация вашей электронной почты</h5>
                                            <p class="col-12 mt-5" style="color: #B3C4E5;">Введите код отправленный на
                                                электронную почту</p>
                                            <? $formEmail2 = \yii\widgets\ActiveForm::begin(['id' => 'form-email-check']); ?>

                                            <div class="right__box-inputs col-12 px-2">
                                                <?= $formEmail2->field($sendEmailModel, 'typeReq')->hiddenInput(['id' => 'inputTypeReq', 'value' => 'check'])->label(false); ?>
                                                <?= $formEmail2->field($sendEmailModel, 'userCode', ['enableClientValidation' => false,])->hiddenInput(['id' => 'inputFullCode'])->label(false); ?>
                                                <input class="input" type="text" maxlength="1"
                                                       oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                                <input class="input" type="text" maxlength="1"
                                                       oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                                <input class="input" type="text" maxlength="1"
                                                       oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                                <input class="input" type="text" maxlength="1"
                                                       oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                                <input class="input" type="text" maxlength="1"
                                                       oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                                <input class="input" type="text" maxlength="1"
                                                       oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                            </div>
                                            <div class="mt-3" id=timer></div>

                                            <input type="button" value="Отправить повторно?" class="mt-3" id="sendEven"
                                                   name="sendEven"
                                                   style="display: none; background-color: Transparent;background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;color:#ffffff;"
                                                   disabled/>

                                            <button class="btn btn-primary col-9 mt-5" type="submit">Подтвердить почту
                                            </button>
                                            <? \yii\widgets\ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? elseif ($model->stage == Verifications::STAGE_READY_TO_VALID_USER_DATA || $model->stage == Verifications::STAGE_MODIFY_USER_DATA): ?>
                            <!-- ======== ЭТАПЫ_ВЕРИФИКАЦИИ ======= -->
                            <div class="tab-pane active stage-2" id="veref2" role="tabpanel"
                                 aria-labelledby="profile-tab">
                                <div class="">
                                    <div class="box__settings fon">
                                        <div class="row">
                                            <div class="back" style="width: 100%;">
                                                <img class="btn__back" src="/img/back.svg" alt="">
                                                <div class="back_2 text-center">
                                                    <span class="w5 text__middle">Настройка Аккаунта</span>
                                                </div>
                                            </div>
                                            <p class="w5 text__middle mb-4">Этапы верификации</p>
                                            <div class="col-12-12 info__line">
                                                <img src="/img/galka.svg" alt="">
                                                <span>Ваша электронная почта подтверждена</span>
                                            </div>
                                            <div class="block__fon-gray my-5 p-4">
                                                <p class="w5">Верификация основных данных</p>

                                                <? if ($model->stage == Verifications::STAGE_MODIFY_USER_DATA): ?>
                                                    <p class="text__small">
                                                        Адинистратор: <?= (isset($model->comment) && strlen($model->comment) > 0) ? $model->comment : 'верификация не пройдена загрузите документы повторно'; ?></p>
                                                    <button class="btn__small btn__blue " id="verifyMainData">
                                                        Верифицировать повторно
                                                    </button>
                                                <? else: ?>
                                                    <p class="text__small">Подтвердите вашу личность пройдя второй
                                                        уровень верификации основных данных
                                                    </p>
                                                    <button class="btn btn__small btn__blue " id="verifyMainData">
                                                        Начать
                                                    </button>
                                                <? endif; ?>

                                            </div>
                                            <div class="block__fon-gray p-4">
                                                <p class="w5">Верификация адресных данных</p>
                                                <p class="text__small">Третий уровень верификации будет доступен после
                                                    подтверждения основных данных личности.</p>

                                                <button class="btn btn__small btn__blue " id="verifyAddress" disabled>
                                                    Начать
                                                </button>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ========= ВЕРИФИКАЦИЯ_ОСНОВНЫХ_ДАНЫХ ======== -->

                            <div class="tab-pane stage-2" id="mainDataTab" role="tabpanel" aria-labelledby="home-tab">
                                <div class="">
                                    <div class="box__settings fon">
                                        <div class="row">

                                            <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-verification', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                            <div class="tab-verif" id="mainDataTab1">
                                                <p class="w5 text__middle">Верификация основных данных</p>
                                                <?= $form->field($verificationForm, 'country_id', ['options' => ['class' => 'form-group field mt-4']])->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Countries::find()->all(), 'id', 'title'), ['value' => $model->country_id, 'class' => 'input__default form-control'])->label('Выберите страну происхождения документа', ['class' => 'form-label text__small']); ?>
                                                <?= $form->field($verificationForm, 'documentType', ['options' => ['class' => 'form-group field mt-4']])->dropDownList($documentType, ['value' => $model->country_id, 'class' => 'input__default form-control'])->label('Выберите страну происхождения документа', ['class' => 'form-label text__small']); ?>
                                                <p class="text__small mt-4">
                                                    Загрузите фотографию паспорта в развернутом виде. Снимок должен
                                                    быть:
                                                </p>
                                                <p class="text__small">светлым и четким (хорошее качество);<br>
                                                    необрезанным (видны все углы документа);
                                                </p>
                                                <div class="mt-4">
                                                    <p class="text__small">Передняя сторона документа</p>
                                                    <div class="fild__name-group mt-2">
                                                        <? if ($model->stage == Verifications::STAGE_MODIFY_USER_DATA && $model->doc1_status == 1): ?>
                                                            <p>Передняя сторона документа валидирована,и не требует
                                                                повторной загрузки</p>
                                                        <? else: ?>
                                                            <div class="file-input-block" id="input-file1">
                                                                <? echo $form->field($verificationForm, 'file1Name')->hiddenInput(['value' => (isset($filesFromSession['file1']) ?: null)])->label(false); ?>
                                                                <button class="btn btn__blue btn__small open-input-file"
                                                                        data-file="file1">Выбрать файл
                                                                </button>
                                                                <div class="input-filename"><?= (isset($filesFromSession['file1'])) ? 'Файл выбран' : 'Файл не выбран' ?></div>
                                                            </div>
                                                        <? endif; ?>

                                                    </div>

                                                </div>

                                                <div class="mt-3">
                                                    <p class="text__small">Задняя сторона документа</p>
                                                    <div class="fild__name-group mt-2">
                                                        <? if ($model->stage == Verifications::STAGE_MODIFY_USER_DATA && $model->doc2_status == 1): ?>
                                                            <p>Задняя сторона документа валидирована,и не требует
                                                                повторной загрузки</p>
                                                        <? else: ?>
                                                            <div class="file-input-block" id="input-file2">
                                                                <? echo $form->field($verificationForm, 'file2Name')->hiddenInput(['value' => (isset($filesFromSession['file2']) ?: null)])->label(false); ?>
                                                                <button class="btn btn__blue btn__small open-input-file"
                                                                        data-file="file2">Выбрать файл
                                                                </button>
                                                                <div class="input-filename"><?= (isset($filesFromSession['file2'])) ? 'Файл выбран' : 'Файл не выбран' ?></div>
                                                            </div>
                                                        <? endif; ?>
                                                    </div>
                                                </div>

                                                <div class="mt-4 text-end">
                                                    <button class="btn__small btn__blue " id="mainDataNext">Далее
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="tab-verif" id="mainDataTab2" style="display: none">
                                                <p class="w5 text__middle">Верификация основных данных</p>

                                                <p class="text__small mt-4">
                                                    Загрузите фотографию, где изображены Вы с паспортом. Убедитесь что
                                                    изображение вашего лица четкое, а все паспортные данные можно легко
                                                    прочитать.
                                                </p>
                                                <p class="text__small">
                                                    светлым и четким (хорошее качество);
                                                    необрезанным (видны все углы документа);
                                                </p>

                                                <div class="fild__name-group mt-2">
                                                    <? if ($model->stage == Verifications::STAGE_MODIFY_USER_DATA && $model->doc3_status == 1): ?>
                                                        <p>Фотография валидирована,и не требует повторной загрузки</p>
                                                    <? else: ?>
                                                        <div class="file-input-block" id="input-file3">
                                                            <? echo $form->field($verificationForm, 'file3Name')->hiddenInput(['value' => (isset($filesFromSession['file3']) ?: null)])->label(false); ?>
                                                            <button class="btn btn__blue btn__small open-input-file"
                                                                    data-file="file3">Выбрать файл
                                                            </button>
                                                            <div class="input-filename"><?= isset($filesFromSession['file3']) ? 'Файл выбран' : 'Файл не выбран' ?></div>
                                                        </div>


                                                    <? endif; ?>
                                                </div>


                                                <div class="mt-4 text-end">
                                                    <button class="btn btn__small btn__white"
                                                            id="mainDataVerification-2-back">Назад
                                                    </button>
                                                    <button class="btn btn__small btn__blue" type="submit"
                                                            id="inputFileSave">Далее
                                                    </button>
                                                </div>
                                            </div>


                                            <? \yii\widgets\ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? elseif ($model->stage == Verifications::STAGE_USER_DATA_WAIT_VALID): ?>

                            <!-- ======== ЭТАПЫ_ВЕРИФИКАЦИИ2 ======= -->
                            <div class="tab-pane active" id="veref5" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="">
                                    <div class="box__settings fon">
                                        <div class="row">
                                            <div class="back" style="width: 100%;">
                                                <img class="btn__back" src="/img/back.svg" alt="">
                                                <div class="back_2 text-center">
                                                    <span class="w5 text__small">Вернуться назад</span>
                                                </div>
                                            </div>
                                            <p class="w5 text__middle mb-4">Этапы верификации</p>
                                            <div class="col-12-12 info__line">
                                                <img src="/img/galka.svg" alt="">
                                                <span>Ваша электронная почта подтверждена</span>
                                            </div>
                                            <div class="block__fon-gray my-5 p-4">
                                                <p class="w5">Верификация основных данных</p>
                                                <p class="text__small">Ваши документы находятся на рассмотрении.
                                                    Максимальное время обработки заявки займет 5 рабочих дней. Результат
                                                    вы получите по почте.
                                                </p>
                                                <div class="info__line-orange">
                                                    <img src="./img/personal_area/small_clock.svg" alt="">
                                                    <span class="text__small ml-2 text-dark w5">
                                                                    На рассмотрении
                                                                </span>
                                                </div>
                                            </div>
                                            <div class="block__fon-gray p-4">
                                                <p class="w5">Верификация адресных данных</p>
                                                <p class="text__small">Третий уровень верификации будет доступен после
                                                    подтверждения основных данных личности.</p>
                                                <button class="btn btn__small btn__blue " disabled>Начать</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <? elseif ($model->stage == Verifications::STAGE_READY_TO_VALID_ADDRESS || $model->stage == Verifications::STAGE_ADDRESS_MODIFY): ?>
                            <!-- ======== ЭТАПЫ_ВЕРИФИКАЦИИ АДРЕСА ======= -->
                            <div class="tab-pane active stage-3" id="veref6" role="tabpanel"
                                 aria-labelledby="profile-tab">
                                <div class="">
                                    <div class="box__settings fon">
                                        <div class="row">
                                            <p class="w5 text__middle mb-4">Этапы верификации</p>
                                            <div class="col-12-12 info__line">
                                                <img src="/img/galka.svg" alt="">
                                                <span>Ваша электронная почта подтверждена</span>
                                            </div>
                                            <div class="block__fon-gray my-5 p-4">
                                                <p class="w5">Верификация основных данных</p>
                                                <p class="">Вы успешно прошли второй уровень верификации. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                <div class="info__line-green">
                                                    <img src="/img/galka.svg" alt="">
                                                    <span class="text__small ml-2 text-dark w5">Основные документы верифицированы</span>
                                                </div>
                                            </div>

                                            <div class="block__fon-gray p-4">
                                                <? if ($model->stage == Verifications::STAGE_ADDRESS_MODIFY): ?>
                                                    <p class="w5">Верификация адресных данных</p>
                                                    <p class="text__small">
                                                        Адинистратор: <?= (isset($model->comment) && strlen($model->comment) > 0) ? $model->comment : 'верификация не пройдена загрузите документы повторно'; ?></p>
                                                    <button class="btn__small btn__blue" id="startVerifAddress">
                                                        Верифицировать повторно
                                                    </button>
                                                <? else: ?>
                                                    <p class="w5">Верификация адресных данных</p>
                                                    <p class="text__small">Подтвердите ваши адресные данные пройдя
                                                        третий уровень верификации</p>
                                                    <button class="btn__small btn__blue" id="startVerifAddress">Начать
                                                    </button>
                                                <? endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ========= ВЕРИФИКАЦИЯ_АДРЕСА1 ======== -->
                            <div class="tab-pane stage-3" id="addressTab" role="tabpanel" aria-labelledby="home-tab">
                                <div class="">
                                    <div class="box__settings fon">
                                        <div class="row">
                                            <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-verification', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                            <div class="verif-address-tab" id="addressVerifTab1">
                                                <p class="w5 text__middle">Верификация адресных данных</p>
                                                <div class="">
                                                    <div class="d-flex">
                                                        <div class="col">
                                                            <?= $form->field($verificationForm, 'city', ['options' => ['class' => 'form-group field']])->textInput(['placeholder' => 'Город', 'class' => 'form-control text__small input__default'])->label('Город', ['class' => 'form-label text__small']) ?>


                                                        </div>
                                                        <div class="col">
                                                            <?= $form->field($verificationForm, 'postIndex', ['options' => ['class' => 'form-group field']])->textInput(['placeholder' => 'Почтовый иднекс', 'class' => 'form-control text__small input__default'])->label('Почтовый индекс', ['class' => 'form-label text__small']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 my-4">
                                                    <?= $form->field($verificationForm, 'address', ['options' => ['class' => 'form-group field']])->textInput(['placeholder' => 'Адрес проживание', 'class' => 'form-control text__small input__default'])->label('Адрес проживание', ['class' => 'form-label text__small']) ?>
                                                </div>
                                                <div class="mt-4 text-end">
                                                    <button class="btn__small btn__blue" id="addressNext">Далее</button>
                                                </div>
                                            </div>
                                            <div class="verif-address-tab" id="addressVerifTab2" style="display:none;">
                                                <p class="w5 text__middle">Верификация адресных данных</p>
                                                <p class="text__small">Пожалуйста, загрузите один из следующих
                                                    документов:</p>

                                                <div class="">
                                                    <ul>
                                                        <li>
                                                            <p class="text__small">
                                                                счет за оплату комунальных услуг (электроэнергия, газ,
                                                                вода, отопление и т.п). К рассмотрению принимаются
                                                                справки не позднее 3х месяцев от начала его получение.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p class="text__small">
                                                                справка из банка, где указан адрес вашего проживание. К
                                                                рассмотрению принимаются справки не старше 3х месяцев от
                                                                начала его получение.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p class="text__small">
                                                                документ от государственных органов (налоговая,
                                                                пенсионный фонд и т.п). Принимается наиболее свежий
                                                                вариант.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p class="text__small">
                                                                любой другой документ государственного образца где
                                                                указаны полнык ФИО и адрес.
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="mt-4 ">
                                                    <p class="text__small">Задняя сторона документа</p>
                                                    <div class="file-upload">
                                                        <? if ($model->stage == Verifications::STAGE_ADDRESS_MODIFY && $model->doc4_status == 1): ?>
                                                            <p>Файл не требует повторной загрузки</p>
                                                        <? else: ?>
                                                            <div class="file-input-block" id="input-file4">
                                                                <? echo $form->field($verificationForm, 'file4Name')->hiddenInput(['value' => (isset($filesFromSession['file4']) ?: null)])->label(false); ?>
                                                                <button class="btn btn__blue btn__small open-input-file"
                                                                        data-file="file4">Выбрать файл
                                                                </button>
                                                                <div class="input-filename"><?= isset($filesFromSession['file4']) ? 'Файл выбран' : 'Файл не выбран' ?></div>
                                                            </div>

                                                        <? endif; ?>
                                                    </div>
                                                </div>

                                                <div class="mt-4 text-end">
                                                    <button class="btn btn__small btn__white" id="addressBack">Назад
                                                    </button>
                                                    <button class="btn__small btn__blue " type="submit">Далее</button>
                                                </div>

                                            </div>
                                            <? \yii\widgets\ActiveForm::end(); ?>


                                        </div>
                                    </div>
                                </div>
                            </div>


                        <? elseif ($model->stage == Verifications::STAGE_ADDRESS_WAIT_VALID): ?>
                            <div class="tab-pane active" id="veref5" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="">
                                    <div class="box__settings fon">
                                        <div class="row">
                                            <p class="w5 text__middle mb-4">Этапы верификации</p>
                                            <div class="col-12-12 info__line">
                                                <img src="/img/galka.svg" alt="">
                                                <span>Ваша электронная почта подтверждена</span>
                                            </div>
                                            <div class="block__fon-gray my-5 p-4">
                                                <p class="w5">Верификация основных данных</p>
                                                <p class="text__small">Вы успешно прошли второй уровень верификации.
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                <div class="info__line-green">
                                                    <img src="/img/galka.svg" alt="">
                                                    <span class="text__small ml-2 text-dark w5">Основные документы верифицированы</span>
                                                </div>
                                            </div>

                                            <div class="block__fon-gray p-4">
                                                <p class="w5">Верификация адресных данных</p>
                                                <p class="text__small">Ваши документы находятся на рассмотрении.
                                                    Максимальное время обработки заявки займет 5 рабочих дней. Результат
                                                    вы получите по почте.
                                                </p>
                                                <div class="info__line-orange">
                                                    <img src="/img/small_clock.svg" alt="">
                                                    <span class="text__small ml-2 text-dark w5">
                                                                На рассмотрении
                                                              </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <? elseif ($model->stage == Verifications::STAGE_ALL_VALIDATED): ?>
                            <div class="tab-pane active" id="veref5" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="">
                                    <div class="box__settings fon">
                                        <div class="row">
                                            <p class="w5 text__middle mb-4">Этапы верификации</p>
                                            <div class="col-12-12 info__line">
                                                <img src="/img/galka.svg" alt="">
                                                <span>Ваша электронная почта подтверждена</span>
                                            </div>
                                            <div class="block__fon-gray my-5 p-4">
                                                <p class="w5">Верификация основных данных</p>
                                                <p class="text__small">Вы успешно прошли второй уровень верификации.
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                <div class="info__line-green">
                                                    <img src="/img/galka.svg" alt="">
                                                    <span class="text__small ml-2 text-dark w5">Данные верифицированы</span>
                                                </div>
                                            </div>

                                            <div class="block__fon-gray p-4">
                                                <p class="w5">Верификация адресных данных</p>
                                                <p class="text__small">Вы успешно прошли третий уровень верификации.
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                <div class="info__line-green">
                                                    <img src="/img/galka.svg" alt="">
                                                    <span class="text__small ml-2 text-dark w5">Данные верифицированы</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? endif ?>
                    </div>

                </div>
            </div>
    </main>
<?
echo \frontend\components\LoginWidget::widget();
?>