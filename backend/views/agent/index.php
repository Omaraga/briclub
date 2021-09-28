<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список представителей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">


    <p>
        <?= Html::a('Создать представителя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'username',
                'content' => function($data){
                    return Html::a($data['username'], ['/agent/view?id='.$data['id']]);
                }

            ],
            'w_balans',
            [
                'label' => 'Статус',
                'content' => function($data){
                    if ($data->agent_status == 1){
                        return 'Активный';
                    }else{
                        return 'Заблокирован';
                    }
                }
            ],

        ],
    ]); ?>
</div>
