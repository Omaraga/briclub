<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="actions-index">

    <p>
        <?= Html::a('Добавить новость', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            //'type',
            //'user_id',
            [
                'attribute'=>'time',
                'content'=>function($data){
                    return date("d.m.Y H:i",$data['time']);
                }
            ],
            //'status',
            //'user2_id',
            //'sum',
            //'comment',
            //'admin_id',
            //'content:ntext',
            //'view',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
