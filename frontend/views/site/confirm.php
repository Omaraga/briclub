<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $message;
?>

<section class="landing">
    <div class="container">
        <div class="">

            <h1><?= Html::encode($this->title) ?></h1>

            <div class="alert alert-success">
                <?= nl2br(Html::encode(Yii::t('users',$message))) ?>
            </div>


        </div>
    </div>
</section>
<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>