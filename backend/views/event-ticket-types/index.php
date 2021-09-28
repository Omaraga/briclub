<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Билеты мероприятий';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-ticket-types-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'event_id',
            'count',
            'price',
            //'link',
            //'status',
            //'time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
