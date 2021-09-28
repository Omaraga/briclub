<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Shanyrak Информация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shanyrak-info-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>"{view}"],
        ],
    ]); ?>
</div>
