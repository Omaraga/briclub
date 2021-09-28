<?php
/* @var $this yii\web\View */
/* @var $model \common\models\Insurances */

$user = Yii::$app->user->identity;
$this->title = "Заявка на страхование";
?>

    <div class="main__block">
        <main class="insurance">
            <div class="row">
                <div class="col-12 col-md-5">
                    <h3 class="w5 mb-4">Заявка на страхование</h3>
                    <?php if(!$model->status || $model->status==0):?>
                        <div class="insurance_banner">
                            <img src="../img/insurance.svg" alt="">
                            <span class="btn__small">Страховка в подарок</span>
                            <span class="yellow">на 1 год</span>
                        </div>
                    <p class="mt-4">Уважаемый партнер, для оформления страхового договора от компании
                        <b>АО "Страховая компания "Сентрас Иншуранс" </b>необходимо заполнить ниже форму </p>
                    <div class="box__settings fon">
                        <?php $form = \yii\widgets\ActiveForm::begin() ?>
                            <div class="form-group mt-4">
                                <?= $form->field($model, 'country')->textInput(['class'=>'input__default','placeholder' => 'Страна'])->label('Страна',['class'=>'']) ?>
                            </div>
                            <div class="form-group mt-2">
                                <?= $form->field($model, 'city')->textInput(['class'=>'input__default','placeholder' => 'Город'])->label('Город',['class'=>'']) ?>
                            </div>
							<div class="form-group mt-2">
                                <?= $form->field($model, 'address')->textInput(['class'=>'input__default','placeholder' => 'Адрес'])->label('Адрес проживания',['class'=>'']) ?>
                            </div>
                            <div class="form-group mt-2">
                                <?= $form->field($model, 'phone')->textInput(['class'=>'input__default','placeholder' => 'Телефон'])->label('Номер телефона',['class'=>'']) ?>
                            </div>
                            <div class="form-group mt-2">
                                <?= $form->field($model, 'email')->textInput(['class'=>'input__default','placeholder' => 'Электронная почта'])->label('Электронная почта',['class'=>'']) ?>
                            </div>
                            <div class="mt-4">
                                <p class="">Скан удостоверения личности с передней стороны</p>
                                <div class="file-upload">
                                    <?= $form->field($model, 'file')->fileInput(['class'=>'btn'])->label(false) ?>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span>Скан удостоверения личности с задней стороны</span>
                                <div class="file-upload">
                                    <?= $form->field($model, 'file2')->fileInput(['class'=>'btn'])->label(false) ?>
                                </div>
                            </div>
                            <input class="btn btn__small btn__blue" value="Оставить заявку" type="submit" style="width: 100%"/>
                        <?php \yii\widgets\ActiveForm::end() ?>
                    </div>
                    <?php elseif($model->status == 1):?>
                        <div class="insurance_banner">
                            <img src="../img/insurance.svg" alt="">
                            <div class="ml-4">
                                <span class="text-white">Вы застрахованы</span>
                                <p class="text-white m-0">Осталось: <span class="str_yellow"><?/*=date('d.m.Y H:i', $model->created_at)*/?></span></p>
                            </div>
                        </div>
                        <div class="text">
                            <img src="../img/accept-circle.svg">
                            <p>Документы страхование были отправлены вам на почту </p>
                        </div>
                    <?php elseif($model->status == 2):?>
                        <div class="insurance_banner">
                            <img src="../img/insurance.svg" alt="">
                            <span class="btn__small">Страховка в подарок</span>
                            <span class="yellow">на 1 год</span>
                        </div>
                        <div class="text">
                            <img src="../img/carbon_time.svg" alt="">
                            <p>Ваша заявка на страхование было успешно отправлено,
                                документы будут отправлены на вашу электронную почту. Время обработки заявки
                                7 - 10 дней </p>
                        </div>
                    <?php elseif($model->status == 3):?>
                    <?$formStatus = \yii\widgets\ActiveForm::begin(['action'=>'/profile/insurance-change-status'])?>
                        <div class="insurance_banner">
                            <img src="../img/insurance.svg" alt="">
                            <span class="btn__small">Страховка в подарок</span>
                            <span class="yellow">на 1 год</span>
                        </div>
                        <div>
                            <div class="text">
                                <img src="../img/error_circle.svg">
                                <p class="red">Вы не верно загрузил фото удостоверения. Пожалуйста повторите попытку еще раз.</p>
                            </div>
                            <button class="btn btn__blue mt-5" style="width: 100%;">Повторить заявку</button>
                        </div>
                    <?\yii\widgets\ActiveForm::end()?>

                    <?php endif;?>
                </div>
            </div>
        </main>
    </div>
