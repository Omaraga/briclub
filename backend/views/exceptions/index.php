<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Исключения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exceptions-index">

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'user_id',
                'content'=>function($data){
                    $parent = \common\models\User::findOne($data['user_id'] );
                    return Html::a($parent['username'],'/users/view?id='.$parent['id']);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
