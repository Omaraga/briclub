<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\httpclient\Client;
use yii\web\View;


$this->title = "Регистрация";
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');

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
$this->registerJs('
//$("#signupform-phone").mask("+9 (999) 99-99-999");
    $("#policy").change(function(){
    if(this.checked == true && $("#age").prop("checked") == true){
        $("#regbutton").attr("disabled",false);
    }else{
        $("#regbutton").attr("disabled",true);
    }
       
    });
    $("#age").change(function(){
    if(this.checked == true && $("#policy").prop("checked") == true){
        $("#regbutton").attr("disabled",false);
    }else{
        $("#regbutton").attr("disabled",true);
    }
       
    });
    $(\'body\').on(\'click\', \'.password-control\', function(){
	if ($(\'#signupform-password\').attr(\'type\') == \'password\'){
		$(this).addClass(\'view\');
		$(\'#signupform-password\').attr(\'type\', \'text\');
	} else {
		$(this).removeClass(\'view\');
		$(\'#signupform-password\').attr(\'type\', \'password\');
	}
	return false;
});
');
$this->registerJsFile('/js/jquery.maskedinput.min.js',['depends'=>'yii\web\JqueryAsset']);
?>
    <style>
        .password-control {
            //position: absolute;
            top: 11px;
            right: 6px;
            display: inline-block;
            width: 20px;
            height: 20px;
            background: url(/img/view.svg) 0 0 no-repeat;
            float: right;
            margin-top: -29px;
            margin-right: 8px;
        }
        .password-control.view {
            background: url(/img/no-view.svg) 0 0 no-repeat;
        }
        .form-check label{
            cursor: pointer;
            margin-top: -3px;
        }
    </style>
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
<div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Регистрация</h5>
            </div>
            <div class="modal-body">
                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'signup-form','options'=>['data-pjax'=>true]]); ?>
                <?
                    if($ref){
                        echo $form->field($model, 'parent',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Спонсор','value'=>$ref,'disabled'=>'disabled',['options'=>['class'=>'form-control']]])->label(false);
                        echo $form->field($model, 'parent',['options'=>['class'=>'form-group']])->hiddenInput(['value'=>$ref])->label(false);
                    }else{?>
                        <div class="alert alert-danger" role="alert" style="color: #ffffff;background-color: #ff3475;">
                            Вы не указали спонсора!
                        </div>
                    <?}

                ?>
                <?= $form->field($model, 'email',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'E-mail',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'username',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Логин',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'fn',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Имя',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'ln',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Фамилия',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'phone',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Номер телефона',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'country_id',['options'=>['class'=>'form-group']])->dropDownList($c_list,['options'=>['class'=>'form-control']])->label(false) ?>

                <?= $form->field($model, 'password',['options'=>['class'=>'form-group'],'template' => '{label} {input}<a href="#" class="password-control"></a>{error}{hint}'])->passwordInput(['placeholder'=>'Пароль',['options'=>['class'=>'form-control']]])->label(false) ?>

                <?= $form->field($model, 'password_repeat',['options'=>['class'=>'form-group']])->passwordInput(['placeholder'=>'Повторите пароль',['options'=>['class'=>'form-control']]])->label(false) ?>
                <div class=" form-check">
                    <label for="policy">
                        <span>
                            <input required type="checkbox" class="form-check-input" id="policy" style="flo">
                            Я ознакомился и принимаю <a href="<?=\common\models\Documents::findOne(5)['link']?>" target="_blank">клиентское соглашение</a>
                        </span>
                    </label>
                </div>
                <div class=" form-check mb-3">
                    <label for="age">
                        <span>
                            <input required type="checkbox" class="form-check-input" id="age" style="flo">Мне есть 18 лет</a></span></label>
                </div>

                <?
                if(empty($ref)){
                    echo Html::button('Регистрация', [
                        'type' => 'submit',
                        'id' => 'regbutton',
                        'disabled' => 'disabled',
                        'class' => 'btn btn-primary',
                        'data' => [
                            'confirm' => 'Вы не указали спонсора, продолжить регистрацию без спонсора?',
                        ],
                    ]);
                }else{
                    echo Html::button('Регистрация', [
                        'type' => 'submit',
                        'id' => 'regbutton',
                        'disabled' => 'disabled',
                        'class' => 'btn btn-primary',
                    ]);
                }
                 ?>

                <?php \yii\widgets\ActiveForm::end(); ?>
                <a href="/site/login" class="btn btn-link pl-0 mt-1">У меня уже есть аккаунт</a>
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