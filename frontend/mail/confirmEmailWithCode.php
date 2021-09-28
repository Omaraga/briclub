<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/*@var $code string 123456*/

?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>,</p>

    <p>Код для подтверждения почты:<b> <?=$code;?> </b></p>


</div>
