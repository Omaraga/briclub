<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\httpclient\Client;
use yii\web\View;


$this->title = "Регистрация";


$countries = \common\models\Countries::find()->all();
$c_list = array();
foreach ($countries as $country) {
    $c_list[$country['id']] = $country['title'];
}
$ref = null;
$get = Yii::$app->request->get();
$ref_cookie = null;
if(!empty($get['referal'])){
    $cookies = Yii::$app->response->cookies;
    $cookies->add(new \yii\web\Cookie([
        'name' => 'referal',
        'value' => $get['referal'],
    ]));
    $parent = \common\models\User::find()->where(['username'=>$get['referal']])->one();
    if(!empty($parent)){
        if($parent['activ'] == 1){
            $ref = $get['referal'];
        }
    }
}else{
    $cookies = Yii::$app->request->cookies;

    if (($cookie = $cookies->get('referal')) !== null) {
        $this->registerJs('$(\'#refModal\').modal("show");');
        $ref_cookie = $cookie->value;
    }
}
$this->registerJsFile('/js/jquery.maskedinput.min.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/js/register.js',['depends'=>'yii\web\JqueryAsset']);


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


    <div class="register-center-block">
        <div class="register-content fon-main txt-white">
            <div class="register-name center">
                <h4>Регистрация</h4>
            </div>
            <div class="register-body">

                <div class="ellipsises between mt-5">
                    <ul class="ellipsises__list list-group list-group-horizontal mb-3">
                        <li class="elips center active ml-lg-3" attr-id="1"><h4>1</h4></li>
                        <li class="elips center ml-lg-5" attr-id="2"><h4>2</h4></li>
                        <li class="elips center ml-lg-5" attr-id="3"><h4>3</h4></li>
                    </ul>
                </div>

                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'signup-form','options'=>['data-pjax'=>true, 'class'=>'form__regs'],'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']]]); ?>
                <div class="reg-tabs" tab-id="1">
                    <?
                    if($ref){
                        echo $form->field($model, 'parent',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Спонсор','value'=>$ref,'disabled'=>'disabled','style' => 'background: #7F60FA; color: #ffffff', ['options'=>['class'=>'form-control'],]])->label('Рекомендатель');
                        echo $form->field($model, 'parent',['options'=>['class'=>'form-group']])->hiddenInput(['value'=>$ref])->label(false);
                    }else{?>
                        <!--                    <div class="info__line bg__red">-->
                        <!--                        <span class="inf__text">Вы не указали спонсора</span>-->
                        <!--                    </div>-->
                    <?}

                    ?>
                    <?= $form->field($model, 'fn',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Имя','id'=> 'exampleInputFirstName','class'=>'form-control input text__small','attr-curr-page'=>'1'])->label('Имя',['class'=>'form-label text__small']) ?>
                    <?= $form->field($model, 'ln',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Фамилия','id'=> 'exampleInputLastName','class'=>'form-control input text__small','attr-curr-page'=>'1'])->label('Фамилия',['class'=>'form-label text__small']) ?>
                    <?= $form->field($model, 'country_id',['options'=>['class'=>'form-group field']])->dropDownList($c_list,['class'=>'form-control input text__small'])->label('Страна',['class'=>'form-label text__small']) ?>
                    <div class="row modale__body-button mt-4 justify-content-end">
                        <button class="btn btn-normal fon-btn-green col-4 button-navigate-next" type="button" attr-curr-page="1" disabled="true">Продолжить</button>
                    </div>
                </div>
                <div class="reg-tabs" tab-id="2">
                    <?= $form->field($model, 'email',['enableAjaxValidation' => true, 'options'=>[  'class'=>'form-group field']])->textInput(['placeholder'=>'E-mail','id'=>'exampleInputEmail','class'=>'form-control input text__small','attr-curr-page'=>'2'])->label('Email',['class'=>'form-label text__small']) ?>
                    <?= $form->field($model, 'username',['enableAjaxValidation' => true, 'options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Придумайте логин', 'id'=>'exampleInputLogin','class'=>'form-control input text__small','attr-curr-page'=>'2'])->label('Логин',['class'=>'form-label text__small']) ?>
                    <?= $form->field($model, 'phone',['options'=>['class'=>'form-group field']])->textInput(['placeholder'=>'Номер телефона','id'=>'exampleInputPhone','class'=>'form-control input text__small','attr-curr-page'=>'2'])->label('Номер',['class'=>'form-label text__small']) ?>

                    <div class="row modale__body-button mt-4 justify-content-end">
                        <button class="btn txt-green col-3 me-2 button-navigate-back" type="button" attr-curr-page="2">Назад</button>
                        <button class="btn btn-normal fon-btn-green col-4 button-navigate-next" type="button" attr-curr-page="2" disabled="true">Продолжить</button>
                    </div>
                </div>
                <div class="reg-tabs" tab-id="3">
                    <?= $form->field($model, 'password',['options'=>['class'=>'form-group field row position-relative ml-1'],'template' => '{label} {input}<a class="eye col-2 mt-1" href="" attr-show="1"><i class="fas fa-eye-slash"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'Пароль','id'=>'exampleInputPassword','class'=>'form-control text__small col-10'])->label('Придумайте пароль',['class'=>'form-label text__small']) ?>
                    <?= $form->field($model, 'password_repeat',['options'=>['class'=>'form-group field row position-relative ml-1'],'template' => '{label} {input}<a class="eye col-2 mt-1" href="" attr-show="1"><i class="fas fa-eye-slash icon__white"></i></a>{error}{hint}'])->passwordInput(['placeholder'=>'Повторите пароль','id'=>'exampleInputPasswordConfirm','class'=>'form-control text__small col-10'])->label('Повторите пароль',['class'=>'form-label text__small']) ?>

                    <label for="checkbox__label1" class="checkbox__reg">
                        <input required name="agree" class=""  id="policy" type="checkbox"><span class="text__small ml-2">Я ознакомился и принимаю <a href="<?=\common\models\Documents::findOne(5)['link']?>" target="_blank">клиентское соглашение</a></span>
                    </label>
                    <label for="checkbox__label" class="checkbox__reg">
                        <input  required name="adult" class="" id="age" type="checkbox"><span class="text__small ml-2">Мне есть 18 лет</span>
                    </label>

                    <div class="d-flex justify-content-end modale__body-button mt-4">
                        <button class="btn txt-green col-3 me-2 button-navigate-back" type="button" attr-curr-page="2">Назад</button>
                        <?
                        if(empty($ref)){
                            echo Html::button('Продолжить', [
                                'type' => 'submit',
                                'id' => 'regbutton',
                                'disabled' => 'disabled',
                                'class' => 'btn btn-normal fon-btn-green',
                            ]);
                        }else{
                            echo Html::button('Продолжить', [
                                'type' => 'submit',
                                'id' => 'regbutton',
                                'disabled' => 'disabled',
                                'class' => 'btn btn-normal fon-btn-green ',
                            ]);
                        }
                        ?>
                    </div>
                </div>



                <?php \yii\widgets\ActiveForm::end(); ?>
                <div class="center-line mt-4">
                    <p class="txt-A3D1">У вас есть аккаунт? <a class="txt-green w4" href="/site/login">Войти</a></p>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="refModal" tabindex="-1" role="dialog" aria-labelledby="refModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Вы переходили по реферальной ссылке пользователя <b><?=$ref_cookie?></b> Использовать эту реферальную ссылку?</p>
                    <p>
                        <a href="/site/signup?referal=<?=$ref_cookie?>" class="btn btn-success">Да </a>
                        <button class="btn btn-danger" data-dismiss="modal">Нет </button>
                    </p>

                </div>

            </div>
        </div>
    </div>

<?
echo \frontend\components\LoginWidget::widget();
?>