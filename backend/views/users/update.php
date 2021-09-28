<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Изменить данные пользователя: ' . $id;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $id, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
    ]) ?>

</div>
