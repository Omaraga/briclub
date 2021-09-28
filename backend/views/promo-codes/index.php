<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Промокоды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promo-codes-index">


<!--    <p>-->
<!--        --><?//= Html::a('Create Promo Codes', ['create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                    'attribute' => 'user_id',
                    'content' => function($data){
                        $userName = \common\models\User::findOne($data['user_id'])['username'];
                        return Html::a($userName, '/users/view?id=' . $data['user_id']);
                    }
            ],
            'promo',
            [
                    'attribute' => 'created_at',
                    'content' => function($data){
                        return date("d.m.Y H:i",$data['created_at']);
                    }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
