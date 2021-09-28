<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
$this->context->layout = "main_error.php";
if (Yii::$app->user->isGuest){
    $link = '/site/login';
}else{
    $link = '/profile';
}
?>

<!--<section class="landing">-->
<!--    <div class="container">-->
<!--        <div class="site-error">-->
<!---->
<!--            <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
<!---->
<!--            <div class="alert alert-danger">-->
<!--                --><?//= nl2br(Html::encode(Yii::t('users',$message))) ?>
<!--            </div>-->
<!---->
<!--            <p>-->
<!--                Вышеуказанная ошибка произошла, когда веб-сервер обрабатывал ваш запрос.-->
<!--            </p>-->
<!--            <p>-->
<!--                Пожалуйста, свяжитесь с нами, если считаете, что это ошибка сервера. Спасибо.-->
<!--            </p>-->
<!---->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<main class="error_404">
    <div class="content">
        <div class="img_group">
            <div class="img four"></div>
            <div class="img cent"></div>
            <div class="img four"></div>
        </div>
        <div class="text_group">
            <p class="text_main w5"><?= nl2br(Html::encode(Yii::t('users',$message))) ?></p>
            <p class="text_content">Вышеуказанная ошибка произошла, когда веб-сервер обрабатывал ваш запрос<br>
                Пожалуйста, свяжитесь с нами, если считаете, что это ошибка сервера.<br>
                Спасибо.</p>
        </div>
        <a href='<?=$link;?>' class="btn btn__blue">Перейти на главную</a>
    </div>
</main>
