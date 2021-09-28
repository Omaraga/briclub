<?php
$countries = \common\models\Countries::find()->all();
$c_list = array();
$c_list[0] = 'Выберите страну';
foreach ($countries as $country) {
    $c_list[$country['id']] = $country['title'];
}
$ref = null;
$get = Yii::$app->request->get();
if(!empty($get['referal'])){
    $parent = \common\models\User::find()->where(['username'=>$get['referal']])->one();
    if(!empty($parent)){
        $ref = $get['referal'];
    }
}

?>
<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Регистрация</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php  \yii\widgets\Pjax::begin(['id'=>'signup-p']);?>
                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'signup-form','options'=>['data-pjax'=>true]]); ?>
                <?
                if($ref){
                    echo $form->field($model, 'parent',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Спонсор','value'=>$ref,'disabled'=>'disabled',['options'=>['class'=>'form-control']]])->label(false);
                }

                ?>
                <?= $form->field($model, 'email',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'E-mail',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'username',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Логин',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'fn',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Имя',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'ln',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Фамилия',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'phone',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Номер телефона',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'country_id',['options'=>['class'=>'form-group']])->dropDownList($c_list,['options'=>['class'=>'form-control']])->label(false) ?>
                <?= $form->field($model, 'password',['options'=>['class'=>'form-group']])->passwordInput(['placeholder'=>'Пароль',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'password_repeat',['options'=>['class'=>'form-group']])->passwordInput(['placeholder'=>'Повторите пароль',['options'=>['class'=>'form-control']]])->label(false) ?>
                <button  type="submit" class="btn btn-primary">Регистрация</button>
                <?php \yii\widgets\ActiveForm::end(); ?>
                <?php  \yii\widgets\Pjax::end();?>
            </div>
        </div>
    </div>
</div>