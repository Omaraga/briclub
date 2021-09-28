<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Вход</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php \yii\widgets\Pjax::begin(['id'=>'login-p']);?>
                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'login-form','options'=>['data-pjax'=>true]]); ?>
                <?= $form->field($model, 'email',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'E-mail или логин',['options'=>['class'=>'form-control']]])->label(false) ?>
                <?= $form->field($model, 'password',['options'=>['class'=>'form-group']])->passwordInput(['placeholder'=>'Пароль',['options'=>['class'=>'form-control']]])->label(false) ?>
                <button  type="submit" class="btn btn-primary">Войти</button>
                <a href="/site/signup" class="btn btn-primary">Регистрация</a>

                <?php \yii\widgets\ActiveForm::end(); ?>
                <?php \yii\widgets\Pjax::end();?>
                <p>
                    <a href="/requestPasswordReset">Забыли пароль?</a>
                </p>
            </div>
        </div>
    </div>
</div>