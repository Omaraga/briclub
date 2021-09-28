<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('users', 'Подтвердить email');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="landing">
    <div class="container">
        <div class="">

            <h5><?= Html::encode($this->title) ?></h5>
            <form action="/new-user">
                <div class="form-group required">

                    <input type="text" class="form-control" name="token" required placeholder="E-mail" aria-required="true" aria-invalid="false">
                    <input type="hidden" name="course" value="28">

                </div>
                <div class="form-group required">
                    <input class="btn btn-success" type="submit" value="<?=Yii::t('users', 'SEND')?>">
                </div>

            </form>


            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>

        </div>
    </div>
</section>
<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>

