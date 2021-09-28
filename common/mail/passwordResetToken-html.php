<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/user/reset-password', 'token' => $token->token]);
?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>,</p>

    <p>Пройдите по этой ссылке чтобы поменять ваш пароль:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
