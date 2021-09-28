<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Content', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'type',
            'title:ntext',
            'max_length',
            'link',
            //'screen_course_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
