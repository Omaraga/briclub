<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Course Screens';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-screens-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Course Screens', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'screen_id',
            'position',
            'course_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
