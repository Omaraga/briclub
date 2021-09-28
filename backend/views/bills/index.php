<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Выставление счета';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bills-index">

    <p>
        <?= Html::a('Выставить счет', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'code',
            'sum',
            //'comment:ntext',
//            [
//                'attribute' => 'created_at',
//                'content' => function($data){
//                    return date("d.m.Y H:i", $data['created_at']);
//                }
//            ],
            [
                'attribute' => 'receiver_id',
                'content' => function($data){
                    return \common\models\User::findOne(['id' => $data['receiver_id']])['username'];
                }
            ],
            [
                'attribute' => 'sender_id',
                'content' => function($data){
                    if($data['sender_id']){
                        return \common\models\User::findOne(['id' => $data['sender_id']])['username'];
                    }
                    else{
                        return null;
                    }
                }
            ],
            [
                'attribute' => 'status',
                'content' => function($data){
                    $status = null;
                    if($data['status'] == 1){
                        $status = 'Оплачено';
                    }
                    else if($data['status'] == 2){
                        $status = 'В ожидании';
                    }
                    else if($data['status'] == 3){
                        $status = 'Отменено';
                    }
                    return $status;
                }
            ],
            [
                'attribute' => 'type',
                'content' => function($data){
                    $type = null;
                    if($data['type'] == 1){
                        $type = 'Greenswop';
                    }
                    if($data['type'] == 2){
                        $type = 'Shanyrakplus';
                    }
                    return $type;
                }
            ],
            [
                'attribute' => 'updated_at',
                'content' => function($data){
                    return $data['updated_at'] ? date("d.m.Y H:i", $data['updated_at']) : null;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
