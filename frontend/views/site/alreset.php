<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = $message;
?>
    <section class="landing" style="min-height: 438px">
        <div class="container">
            <div class="">

                <h5><?= Html::encode($message) ?></h5>
                <p>Если вы забыли свой пароль, восстановите этой <a href="/requestPasswordReset">ссылке.</a></p>
            </div>
        </div>
    </section>
<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>