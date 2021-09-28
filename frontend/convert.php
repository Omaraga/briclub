<?php
    /* @var $this yii\web\View */
    /* @var $model \frontend\models\forms\ConvertForm */

    $this->title = "Конвертация";

    $user = \common\models\User::findOne(Yii::$app->user->id);
    $balance = $user['w_balans'];
    $p_balance = $user['p_balans'];
?>

<main>
    <div class="col-lg-5">
        <h3>Конвертация</h3>
        <p>Ваш баланс: <?=$balance?> CV</p>
        <p>Ваш агентский баланс: <?=$p_balance?> PV</p>
        <?php $form = \yii\widgets\ActiveForm::begin()?>
        <?=$form->field($model, 'sum')->textInput(['placeholder' => 'Введите сумму для конвертации'])->label(false)?>
        <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit">
        <?php \yii\widgets\ActiveForm::end()?>
    </div>
</main>

