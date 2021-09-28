<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TokenUsers */

$this->title = 'Добавить пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи для токенов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-users-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
