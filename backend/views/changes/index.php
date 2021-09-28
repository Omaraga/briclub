<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Курсы валют';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="changes-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'cur2',

            ['class' => 'yii\grid\ActionColumn','template'=>"{update}"],
        ],
    ]); ?>
</div>
