<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Еженедельная бонусная программа';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .badge-primary {
        color: #fff;
        background-color: #007bff;
    }
    .badge-success {
        color: #fff;
        background-color: #28a745;
    }
    .badge-danger {
        color: #fff;
        background-color: #dc3545;
    }
    .badge-warning {
        color: #212529;
        background-color: #ffc107;
    }
</style>
<div class="promotion-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute'=>'start',
                'content'=>function($data){
                    return date('d.m.Y H:i',$data['start']);
                }
            ],
            [
                'attribute'=>'end',
                'content'=>function($data){
                    return date('d.m.Y H:i',$data['end']);
                }
            ],
            [
                'attribute'=>'status',
                'content'=>function($data){
                    $status = \common\models\BonusStatuses::findOne($data['status']);
                    return '<span class="badge badge-'.$status['color'].'">'.$status['title'].'</span>';
                }
            ],


            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}'
            ],
        ],
    ]); ?>
</div>
