<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Tickets */

$this->title = 'Изменить статус запроса: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="tickets-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
