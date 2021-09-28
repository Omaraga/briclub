<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserPlans */

$this->title = 'Изменить план для пользователя: ' . \common\models\User::findOne($model->user_id)['username'];
$this->params['breadcrumbs'][] = ['label' => 'User Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
$user_id = $model->user_id;
?>
<div class="user-plans-update">

    <?= $this->render('_form', [
        'model' => $model,
        'user_id' => $user_id,
    ]) ?>

</div>
