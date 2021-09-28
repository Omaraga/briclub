<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$confirmLink = 'http://oneroom.loc/new-user?token='.$token->new_email_token.'course='.$course_id;
$user_fio = \common\models\User::findOne($user->id)['fio'];
$course_name = \common\models\Courses::findOne($course_id)['title'];
?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user_fio) ?>,</p>

    <p>Для вас открыт доступ к курсу <?=$course_name?>. Пройдите по этой ссылке, чтобы подтвердить ваш Email-a и завершить регистрацию:</p>

    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>
