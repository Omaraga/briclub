<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отзывы курсов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-reviews-index">


    <p>
        <?= Html::a('Создать отзыв', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'course_id',
            'content:ntext',
            'img_url:url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
