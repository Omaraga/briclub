<?php
/* @var $this yii\web\View */

/* @var $user common\models\User */
/* @var $model Verifications */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Verifications;
$this->title = 'Верификация';
$this->registerJsFile('/js/setting.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/js/resizeImage.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<div class="container acc">
    <div class="account-menu">
        <div class="banner-user">
            <input type="file" style="display: none" id="verificationFileInput" data-name="" accept=".jpg, .jpeg, .png">
            <div class="header-img center">
                <h6><?=mb_substr($user['firstname'],0 , 1)?><?=mb_substr($user['lastname'], 0, 1)?></h6>
            </div>
            <h5 class="w6"><?= $user['firstname'] . ' ' . $user['lastname'] ?></h5>
            <h6><?= $user['email'] ?></h6>

            <div class="banner-user_settings">
                <h6>Логин: <?= $user['username'] ?></h6>
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
                    <a class="" href="security">
                        <div class="links__text">
                            <span class="">Безопасность</span>
                            <p class="text__small">Изменить пароль</p>
                        </div>
                        <img src="/img/arrow.svg" alt="">
                    </a>
                </li>
                <li>
                    <a class="account-menu__link__active" href="/academy/profile/verification">
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
    <div class="account-verification">
        <p class="w7 account-setting__size">Верификация</p>
        <div class="account-verification__start">

            <div class="tab-content">
                <? if ($model->stage == Verifications::STAGE_SEND_EMAIL): ?>
                    <div class="tab-pane <?= ($model->stage == 0) ? 'active' : ''; ?>" id="two" role="tabpanel"
                         aria-labelledby="home-tab">
                        <div class="email-veref-text">
                            <h4 class="w6">Верификация вашей электронной почты</h4>
                            <h5 class="my-5">Подтвердите ваш почтовый адрес для прохождения верификации</h5>
                        </div>
                        <? $formEmail = \yii\widgets\ActiveForm::begin(['id' => 'form-email-send']); ?>
                        <?= $formEmail->field($sendEmailModel, 'typeReq')->hiddenInput(['value' => 'send'])->label(false); ?>
                        <button class="btn fon-green form-btn-send" type="submit">Подтвердить почту</button>
                        <? \yii\widgets\ActiveForm::end(); ?>
                    </div>
                <? elseif ($model->stage == Verifications::STAGE_CHECK_EMAIL): ?>
                    <div class="tab-pane <?= ($model->stage == 1) ? 'active' : ''; ?>" id="big_input" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="">
                            <div class="box__settings fon text-center">
                                <div>
                                    <h5 class=" col-12">Верификация вашей электронной почты</h5>
                                    <p class="col-12 mt-5">Введите код отправленный на электронную почту</p>
                                    <? $formEmail2 = \yii\widgets\ActiveForm::begin(['id' => 'form-email-check']); ?>

                                    <div class="right__box-inputs col-12 px-2">
                                        <?= $formEmail2->field($sendEmailModel, 'typeReq')->hiddenInput(['id' => 'inputTypeReq', 'value' => 'check'])->label(false); ?>
                                        <?= $formEmail2->field($sendEmailModel, 'userCode', ['enableClientValidation' => false,])->hiddenInput(['id' => 'inputFullCode'])->label(false); ?>
                                        <input class="input-default email-input-code input" type="text" maxlength="1"
                                               oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                        <input class="input-default email-input-code input" type="text" maxlength="1"
                                               oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                        <input class="input-default email-input-code input" type="text" maxlength="1"
                                               oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                        <input class="input-default email-input-code input" type="text" maxlength="1"
                                               oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                        <input class="input-default email-input-code input" type="text" maxlength="1"
                                               oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                        <input class="input-default email-input-code input" type="text" maxlength="1"
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
                <div class="tab-pane active stage-2" id="veref2" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="box__settings fon">
                        <h4 class="w5 text__middle ml-5 my-4">Этапы верификации</h4>
                        <div class="info__line p-4 fon-asfalt">
                            <img src="/img/galka.svg" alt="">
                            <span class="w5">Ваша электронная почта подтверждена</span>
                        </div>
                        <div class="block__fon-gray my-5 p-4 fon-asfalt">
                            <h5 class="w5 mb-3">Верификация основных данных</h5>
                            <? if ($model->stage == Verifications::STAGE_MODIFY_USER_DATA): ?>
                                <p>
                                    Администратор: <?= (isset($model->comment) && strlen($model->comment) > 0) ? $model->comment : 'верификация не пройдена, загрузите документы повторно'; ?>
                                </p><br>
                                <button class="btn fon-grey " id="verifyMainData">
                                    Верифицировать повторно
                                </button>
                            <? else: ?>
                                <h6 class="mb-3">Подтвердите вашу личность пройдя второй уровень верификации основных данных</h6>
                                <button class="btn fon-green mt-3" id="verifyMainData">
                                    Начать
                                </button>
                            <? endif; ?>
                        </div>
                        <div class="block__fon-gray p-4 fon-asfalt">
                            <h5 class="w5 mb-3">Верификация адресных данных</h5>
                            <h6>Третий уровень верификации будет доступен после подтверждения основных данных личности.</h6>
                            <button class="btn mt-3 fon-disabled" id="verifyAddress" disabled>
                                Начать
                            </button>
                        </div>
                    </div>
                </div>
                <!-- ========= ВЕРИФИКАЦИЯ_ОСНОВНЫХ_ДАНЫХ ======== -->

                <div class="tab-pane stage-2" id="mainDataTab" role="tabpanel" aria-labelledby="home-tab">
                    <div class="box__settings fon">
                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-verification', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                        <div class="tab-verif" id="mainDataTab1">
                            <h4 class="w6">Верификация основных данных</h4>
                            <?= $form->field($verificationForm, 'country_id', ['options' => ['class' => 'form-group field mt-4']])->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Countries::find()->all(), 'id', 'title'), ['value' => $model->country_id, 'class' => 'input__default form-control input-default'])->label('Выберите страну происхождения документа', ['class' => 'form-label w5']); ?>
                            <?= $form->field($verificationForm, 'documentType', ['options' => ['class' => 'form-group field mt-4']])->dropDownList($documentType, ['value' => $model->country_id, 'class' => 'input__default form-control input-default'])->label('Выберите тип документа', ['class' => 'form-label w5 ']); ?>
                            <p class="text__small mt-4">
                                Загрузите фотографию паспорта в развернутом виде. Снимок должен быть:
                            </p>
                            <br>
                            <p>
                                светлым и четким (хорошее качество);<br>
                                необрезанным (видны все углы документа);
                            </p>
                            <div class="mt-4">
                                <p>Передняя сторона документа</p>
                                <div class="fild__name-group mt-2">
                                    <? if ($model->stage == Verifications::STAGE_MODIFY_USER_DATA && $model->doc1_status == 1): ?>
                                        <p>
                                            Передняя сторона документа валидирована,
                                            и не требует повторной загрузки
                                        </p>
                                    <? else: ?>
                                        <div class="file-input-block" id="input-file1">
                                            <? echo $form->field($verificationForm, 'file1Name')->hiddenInput(['value' => (isset($filesFromSession['file1']) ?: null)])->label(false); ?>
                                            <button class="btn btn-blue text-white mb-2 open-input-file"
                                                    data-file="file1">Выбрать файл
                                            </button>
                                            <div class="input-filename p-3 fon-asfalt"><?= (isset($filesFromSession['file1'])) ? 'Файл выбран' : 'Файл не выбран' ?></div>
                                        </div>
                                    <? endif; ?>
                                </div>
                            </div>

                            <div class="mt-3">
                                <p class="text__small">Задняя сторона документа</p>
                                <div class="fild__name-group mt-2">
                                    <? if ($model->stage == Verifications::STAGE_MODIFY_USER_DATA && $model->doc2_status == 1): ?>
                                        <p>Задняя сторона документа валидирована,
                                            и не требует повторной загрузки
                                        </p>
                                    <? else: ?>
                                        <div class="file-input-block" id="input-file2">
                                            <? echo $form->field($verificationForm, 'file2Name')->hiddenInput(['value' => (isset($filesFromSession['file2']) ?: null)])->label(false); ?>
                                            <button class="btn btn-blue text-white mb-2 open-input-file"
                                                    data-file="file2">Выбрать файл
                                            </button>
                                            <div class="input-filename p-3 fon-asfalt"><?= (isset($filesFromSession['file2'])) ? 'Файл выбран' : 'Файл не выбран' ?></div>
                                        </div>
                                    <? endif; ?>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button class="btn fon-grey" type="submit" id="mainDataNext" data-target="#mainDataTab2">Далее</button>
                            </div>
                        </div>
                        <div class="tab-verif" id="mainDataTab2" style="display: none">
                            <h4 class="w6 mb-4 mt-4">Верификация основных данных</h4>
                            <p class=" mt-4">
                                Загрузите фотографию, где изображены Вы с паспортом.
                                Убедитесь что изображение вашего лица четкое,
                                а все паспортные данные можно легко прочитать.
                            </p>
                            <br>
                            <p>
                                светлым и четким (хорошее качество);
                                необрезанным (видны все углы документа);
                            </p>
                            <div class="fild__name-group mt-2">
                                <? if ($model->stage == Verifications::STAGE_MODIFY_USER_DATA && $model->doc3_status == 1): ?>
                                    <p>Фотография валидирована,и не требует повторной загрузки</p>
                                <? else: ?>
                                    <div class="file-input-block" id="input-file3">
                                        <? echo $form->field($verificationForm, 'file3Name')->hiddenInput(['value' => (isset($filesFromSession['file3']) ?: null)])->label(false); ?>
                                        <button class="btn btn-blue open-input-file" data-file="file3">
                                            Выбрать файл
                                        </button>
                                        <div class="input-filename p-3 fon-asfalt mt-3"><?= isset($filesFromSession['file3']) ? 'Файл выбран' : 'Файл не выбран' ?></div>
                                    </div>
                                <? endif; ?>
                            </div>
                            <div class="mt-4 mb-4 d-flex justify-content-end">
                                <button class="btn fon-transparent border-none mr-3"
                                        id="mainDataVerification-2-back">Назад
                                </button>
                                <button class="btn fon-grey" type="submit" id="inputFileSave">Далее
                                </button>
                            </div>
                        </div>
                    </div>
                    <? \yii\widgets\ActiveForm::end(); ?>
                </div>
            </div>
            <? elseif ($model->stage == Verifications::STAGE_USER_DATA_WAIT_VALID): ?>

                <!-- ======== ЭТАПЫ_ВЕРИФИКАЦИИ2 ======= -->
                <div class="tab-pane active" id="veref5" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="box__settings fon">
                        <h4 class="w5 mb-4">Этапы верификации</h4>
                        <div class="fon-asfalt p-4 info__line">
                            <img src="/img/galka.svg" alt="">
                            <span class="w5">Ваша электронная почта подтверждена</span>
                        </div>
                        <div class="fon-asfalt my-5 p-4">
                            <h5 class="w5 mb-3">Верификация основных данных</h5>
                            <h6>Ваши документы находятся на рассмотрении. Максимальное время обработки заявки займет 5 рабочих дней. Результат вы получите по почте.</h6>
                            <div class="moderation-style-1 mt-3">
                                <img src="/img/small_clock.svg" alt="">
                                <span class="ml-2 w5" style="font-size: 14px">На рассмотрении</span>
                            </div>
                        </div>
                        <div class="fon-asfalt p-4">
                            <h5 class="w5">Верификация адресных данных</h5>
                            <button class="btn mt-4 fon-disabled" disabled>Начать</button>
                        </div>
                    </div>
                </div>

            <? elseif ($model->stage == Verifications::STAGE_READY_TO_VALID_ADDRESS || $model->stage == Verifications::STAGE_ADDRESS_MODIFY): ?>
                <!-- ======== ЭТАПЫ_ВЕРИФИКАЦИИ АДРЕСА ======= -->
                <div class="tab-pane active stage-3" id="veref6" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="box__settings fon">
                        <h4 class="w5 mb-4">Этапы верификации</h4>
                        <div class="fon-asfalt p-4 w5 info__line">
                            <img src="/img/galka.svg" alt="">
                            <span class="w5">Ваша электронная почта подтверждена</span>
                        </div>
                        <div class="fon-asfalt my-5 p-4">
                            <h5 class="w5 mb-3">Верификация основных данных</h5>
                            <h6 class="mb-3">Вы успешно прошли второй уровень верификации.</h6>
                            <div class="moderation-style-2 mt-3">
                                <img src="/img/galka.svg" alt="">
                                <h6 class="ml-2 w5">Основные документы верифицированы</h6>
                            </div>
                        </div>

                        <div class="fon-asfalt p-4">
                            <? if ($model->stage == Verifications::STAGE_ADDRESS_MODIFY): ?>
                                <h5 class="w5 mb-3">Верификация адресных данных</h5>
                                <p>
                                    Администратор: <?= (isset($model->comment) && strlen($model->comment) > 0) ? $model->comment : 'верификация не пройдена, загрузите документы повторно'; ?>
                                </p><br>
                                <button class="btn fon-grey" id="startVerifAddress">
                                    Верифицировать повторно
                                </button>
                            <? else: ?>
                                <h5 class="w5 mb-4">Верификация адресных данных</h5>
                                <h6 class="mb-3">Подтвердите ваши адресные данные пройдя третий уровень верификации</h6>
                                <button class="btn fon-green" id="startVerifAddress">
                                    Начать
                                </button>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
                <!-- ========= ВЕРИФИКАЦИЯ_АДРЕСА1 ======== -->
                <div class="tab-pane stage-3" id="addressTab" role="tabpanel" aria-labelledby="home-tab">
                    <div class="box__settings fon">
                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-verification', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                        <div class="verif-address-tab" id="addressVerifTab1">
                            <h4 class="w6 mb-4">Верификация адресных данных</h4>
                            <div class="">
                                <div class="mb-2">
                                    <?= $form->field($verificationForm, 'city', ['options' => ['class' => 'form-group field']])->textInput(['placeholder' => 'Город', 'class' => 'form-control fon-asfalt p-4 mb-4 input__default'])->label('Город', ['class' => 'form-label w5']) ?>
                                </div>
                                <div class="mb-2">
                                    <?= $form->field($verificationForm, 'postIndex', ['options' => ['class' => 'form-group field']])->textInput(['placeholder' => 'Почтовый иднекс', 'class' => 'form-control fon-asfalt p-4 mb-4 input__default'])->label('Почтовый индекс', ['class' => 'form-label w5']) ?>
                                </div>
                                <div class="mb-2">
                                    <?= $form->field($verificationForm, 'address', ['options' => ['class' => 'form-group field']])->textInput(['placeholder' => 'Адрес проживания', 'class' => 'form-control fon-asfalt p-4 mb-4 input__default'])->label('Адрес проживания', ['class' => 'form-label w5']) ?>
                                </div>
                            </div>

                            <div class="mt-4 mb-4 d-flex justify-content-end">
                                <button class="btn fon-grey" id="addressNext">Далее</button>
                            </div>
                        </div>
                        <div class="verif-address-tab" id="addressVerifTab2" style="display:none;">
                            <h4 class="w6 mb-4">Верификация адресных данных</h4>
                            <p class="w5 mb-3">Пожалуйста, загрузите один из следующих документов:</p>
                            <div>
                                <ul>
                                    <li class="mb-2">
                                        <p>
                                            счет за оплату коммунальных услуг (электроэнергия, газ, вода,
                                            отопление и т.п). К рассмотрению принимаются справки не
                                            позднее 3х месяцев от начала его получение.
                                        </p>
                                    </li>
                                    <li class="mb-2">
                                        <p>
                                            справка из банка, где указан адрес вашего проживание. К
                                            рассмотрению
                                            принимаются справки не старше 3х месяцев от начала его
                                            получение.
                                        </p>
                                    </li>
                                    <li class="mb-2">
                                        <p>
                                            документ от государственных органов (налоговая, пенсионный
                                            фонд и т.п). Принимается наиболее свежий вариант.
                                        </p>
                                    </li>
                                    <li class="mb-2">
                                        <p>
                                            любой другой документ государственного образца где
                                            указаны ФИО и адрес.
                                        </p>
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-5">
                                <div class="file-upload">
                                    <? if ($model->stage == Verifications::STAGE_ADDRESS_MODIFY && $model->doc4_status == 1): ?>
                                        <p>Файл не требует повторной загрузки</p>
                                    <? else: ?>
                                        <div class="file-input-block" id="input-file4">
                                            <? echo $form->field($verificationForm, 'file4Name')->hiddenInput(['value' => (isset($filesFromSession['file4']) ?: null)])->label(false); ?>
                                            <button class="btn btn-blue mb-2 open-input-file"
                                                    data-file="file4">
                                                Выбрать файл
                                            </button>
                                            <div class="input-filename fon-asfalt p-3"><?= isset($filesFromSession['file4']) ? 'Файл выбран' : 'Файл не выбран' ?></div>
                                        </div>

                                    <? endif; ?>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button class="btn fon-transparent border-none" id="addressBack">Назад</button>
                                <button class="btn fon-grey" type="submit">Далее</button>
                            </div>
                        </div>
                        <? \yii\widgets\ActiveForm::end(); ?>
                    </div>
                </div>


            <? elseif ($model->stage == Verifications::STAGE_ADDRESS_WAIT_VALID): ?>
                <div class="tab-pane active" id="veref5" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="box__settings fon">
                        <h4 class="w5 mb-4">Этапы верификации</h4>
                        <div class="fon-asfalt p-4 info__line">
                            <img src="/img/galka.svg" alt="">
                            <span class="w5">Ваша электронная почта подтверждена</span>
                        </div>
                        <div class="fon-asfalt my-5 p-4">
                            <h5 class="w5 mb-3">Верификация основных данных</h5>
                            <h6 class="mb-3">Вы успешно прошли второй уровень верификации.</h6>
                            <div class="info__line-green">
                                <img src="/img/galka.svg" alt="">
                                <span class="ml-2 w5">Основные документы верифицированы</span>
                            </div>
                        </div>

                        <div class="fon-asfalt p-4">
                            <h5 class="w5 mb-3">Верификация адресных данных</h5>
                            <h6 class="mb-3">Ваши документы находятся на рассмотрении. Максимальное время обработки заявки займет 5 рабочих дней. Результат вы получите по почте.</h6>
                            <div class="moderation-style-1">
                                <img src="/img/small_clock.svg" alt="">
                                <span class=" ml-2 w5">На рассмотрении</span>
                            </div>
                        </div>
                    </div>
                </div>

            <? elseif ($model->stage == Verifications::STAGE_ALL_VALIDATED): ?>
                <div class="tab-pane  active" id="veref5" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="box__settings fon">
                        <h4 class="w5 mb-4">Этапы верификации</h4>
                        <div class="fon-asfalt p-3 info__line">
                            <img src="/img/galka.svg" alt="">
                            <span class="w5">Ваша электронная почта подтверждена</span>
                        </div>
                        <div class="fon-asfalt p-3 my-5">
                            <h5 class="w5 mb-2">Верификация основных данных</h5>
                            <h6 class="mb-3">Вы успешно прошли второй уровень верификации.</h6>
                            <div class="info__line-green">
                                <img src="/img/galka.svg" alt="">
                                <span class="ml-2 w5">Данные верифицированы</span>
                            </div>
                        </div>
                        <div class="fon-asfalt p-3">
                            <h5 class="w5 mb-2">Верификация адресных данных</h5>
                            <h6 class="mb-3">Вы успешно прошли третий уровень верификации.</h6>
                            <div>
                                <img src="/img/galka.svg" alt="">
                                <span class=" ml-2 w5">Данные верифицированы</span>
                            </div>
                        </div>
                    </div>
                </div>
            <? endif ?>
        </div>

    </div>
</div>


</div>