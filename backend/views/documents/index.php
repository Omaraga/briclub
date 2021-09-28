<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documents-index">

    <p>
        <?= Html::a('Добавить документ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'type',
                'content'=>function($data){
                    if($data['type'] == 1){
                        return "Документ";
                    }elseif($data['type'] == 2){
                        return "Промо материал";
                    }
                }
            ],
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
			'link2',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
