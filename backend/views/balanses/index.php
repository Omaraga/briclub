<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Балансы счетов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="balanses-index">

    <p>
        <?= Html::a('Добавить счет', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'sum',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
