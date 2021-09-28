<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\EventTicketTypes */

$this->title = 'Изменить билет: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Билеты мероприятий', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="event-ticket-types-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
