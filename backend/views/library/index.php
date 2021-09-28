<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Библиотека';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documents-index">

    <p>
        <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'link',
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
