<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\EventTicketTypes */

$this->title = 'Добавить билет';
$this->params['breadcrumbs'][] = ['label' => 'Билеты на мероприятия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-ticket-types-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
