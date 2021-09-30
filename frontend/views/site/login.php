<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Войти";
$this->registerJs("$('.eye').click(function(e){
        e.preventDefault()
        let isShow = $(this).attr('attr-show')
        console.log(isShow)

        if(isShow == 1){
            $(this).children().removeClass('fa-eye-slash').addClass('fa-eye')
            $(this).attr('attr-show','2')
            $(this).parent().find('input').attr('type', 'text')
        }else{
            $(this).children().removeClass('fa-eye').addClass('fa-eye-slash')
            $(this).attr('attr-show','1')
            $(this).parent().find('input').attr('type', 'password')
        }

    })");
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css');
$this->registerCssFile('/css/signup.css');
$this->registerJsFile('/js/fa-fa.js',['depends'=>'yii\web\JqueryAsset']);
?>
<?
$flashes = Yii::$app->session->allFlashes;
if(!empty($flashes)){
    foreach ($flashes as $key => $flash) {?>
        <div class="alert alert-<?=$key?> alert-dismissible fade show" role="alert">
            <?=$flash?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?}}
?>
    <style>
        body {
            background: url(/img/register/reg-img.svg)no-repeat center;
            background-size: cover;
        }
    </style>
    <!--    <main class="cours">-->
    <!--        <div class="container-fluid">-->
    <!--            <div class="modal-dialog" role="document">-->
    <!--                <div class="modal-content">-->
    <!--                    <div class="modal-header">-->
    <!--                        <h5 class="modal-title" id="exampleModalLabel">Вход</h5>-->
    <!--                    </div>-->
    <!--                    <div class="modal-body">-->
    <!--                        --><?php //$form = \yii\widgets\ActiveForm::begin(['id' => 'login-form','options'=>['data-pjax'=>true]]); ?>
    <!--                        --><?//= $form->field($model, 'email',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'E-mail или логин',['options'=>['class'=>'form-control']]])->label(false) ?>
    <!--                        --><?//= $form->field($model, 'password',['options'=>['class'=>'form-group']])->passwordInput(['placeholder'=>'Пароль',['options'=>['class'=>'form-control']]])->label(false) ?>
    <!--                        <button  type="submit" class="btn btn-primary">Войти</button>-->
    <!--                        <a href="/site/signup" class="btn btn-primary">Регистрация</a>-->
    <!--                        --><?php //\yii\widgets\ActiveForm::end(); ?>
    <!--                        <p>-->
    <!--                            <a href="/requestPasswordReset">Забыли пароль?</a>-->
    <!--                        </p>-->
    <!--                        <p>-->
    <!--                            <a href="https://bestroadinvestment.com">Перейти на главную</a>-->
    <!--                        </p>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!---->
    <!---->
    <!---->
    <!--    </main>-->


    <div class="register-center-block">
    <div class="center">
        <img src="/img/logo.svg" alt="">
    </div>
    <div class="register-content fon-main txt-white">
        <div class="register-name center">
            <h4>Войти</h4>
        </div>
        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'login-form','options'=>['data-pjax'=>true]]); ?>

        <?= $form->field($model, 'email',['options'=>['class'=>'form-group field input-form']])->textInput(['placeholder'=>'E-mail или логин','id'=>'exampleInputEmail1','aria-describedby'=>'emailHelp','class'=>'form-control input'])->label('Email',['class'=>'form-label text__small']) ?>
        <?= $form->field($model, 'password',['options'=>['class'=>'form-group field position-relative input-form'],'template' => '{label} {input}<a class="eye" href="" attr-show="1"><i class="fas fa-eye-slash"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'Пароль','id'=>'exampleInputPassword1', 'class'=>'form-control input'])->label('Пароль',['class'=>'form-label text__small']) ?>


        <div class="">
            <div class="checkbox__group">
                <label for="checkbox__label" class="checkbox__label">
                    <input class="" id="checkbox__label" type="checkbox"><span class="ml-2">Запомнить меня</span>
                </label>
                <label class="float-right">
                    <a href="/requestPasswordReset">Забыли пароль?</a>
                </label>
            </div>
            <div class="d-grid gap-2 modale__body-button mt-3">
                <button class="btn btn-normal fon-btn-green" type="submit">Войти</button>
            </div>


            <div class="center-line mt-4">
                <p class="txt-A3D1">У вас нет аккаунта? <a class="txt-green w4" href="/site/signup">Регистрация</a></p>
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>
<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>