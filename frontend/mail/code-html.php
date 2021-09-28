<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->username) ?></p>

    <p>Код для подтверждения транзакции:</p>

    <p><?= $code ?></p>
</div>
