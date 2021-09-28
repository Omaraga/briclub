<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['/confirm-email', 'token' => $token->new_email_token]);
?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>,</p>

    <p>Пройдите по этой ссылке, чтобы подтвердить ваш Email-a:</p>

    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>
