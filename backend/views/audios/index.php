<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Аудио';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audios-index">
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'link',
            'lib_id',
            'time',
            [
                'attribute'=>'status',
                'content'=>function($data){
                    if($data['status'] == 1){
                        return "Опубликовано";
                    }elseif($data['status'] == 2){
                        return "Скрыто";
                    }
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
