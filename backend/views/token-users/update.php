<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TokenUsers */

$this->title = 'Пользователи для токенов: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи для токенов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="token-users-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
