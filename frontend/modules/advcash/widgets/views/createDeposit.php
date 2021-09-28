<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


/*Pjax::begin(['enablePushState' => false]);*/
$form = ActiveForm::begin([
    'action' => Url::to(['/advcash/advcash-sci/create']),
    /*'options' => ['data-pjax' => true]*/
]);

?>
    <?= $form->field($model, 'ac_amount') ?>
    <?= $form->field($model, 'ac_account_email', ['errorOptions' => ['tag' => null]])->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'ac_sci_name', ['errorOptions' => ['tag' => null]])->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'ac_currency', ['errorOptions' => ['tag' => null]])->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'ac_order_id', ['errorOptions' => ['tag' => null]])->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'ac_sign', ['errorOptions' => ['tag' => null]])->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'ac_comments', ['errorOptions' => ['tag' => null]])->hiddenInput()->label(false) ?>

    <?= Html::submitButton('Пополнить', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end();
/*Pjax::end();*/ ?>