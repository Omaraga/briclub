<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserPlans */
$user_id = Yii::$app->request->get('user_id');
$this->title = 'Добавить план для пользователя' . \common\models\User::findOne($user_id)['username'];
$this->params['breadcrumbs'][] = ['label' => 'Планы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-plans-create">
    <?= $this->render('_form', [
        'model' => $model,
        'user_id' => $user_id,
    ]) ?>

</div>
