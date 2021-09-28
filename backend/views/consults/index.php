<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы на консультацию';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beds-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tel',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
