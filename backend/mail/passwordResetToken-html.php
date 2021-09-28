<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$confirmLink = 'https://gcfond.com/new-user?token='.$token->token.'&course='.$course_id;
$user_fio = \common\models\User::findOne($user->id)['fio'];
//$course_name = \common\models\Courses::findOne($course_id)['title'];
?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->email) ?>,</p>

    <p>Для вас открыт доступ к платформе Gcfond.com. Пройдите по этой ссылке, чтобы подтвердить ваш Email-a и завершить регистрацию:</p>

    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>
