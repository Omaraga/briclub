<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lesson Docs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-docs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Lesson Docs', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'link',
            'lesson_id',
            'time:datetime',
            //'type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
