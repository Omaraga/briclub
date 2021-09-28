<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на страхование';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurances-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'address',
            'phone',
            'email:email',
            [
                'attribute' => 'user_id',
                'content' => function($data){
                    $user = \common\models\User::findOne($data['user_id'])->username;
                    return Html::a($user,'/users/view?id='.$data['user_id']);
                }
            ],
            [
                'attribute'=>'created_at',
                'content'=>function($data){
                    return date("d.m.Y H:i",$data['created_at']);
                }
            ],
            [
                'attribute'=>'status',
                'content'=>function($data){
                    $status = "";
                    if($data['status'] == 1){
                        $status = "Принято";
                    }
                    else if($data['status'] == 2){
                        $status = "На рассмотрении";
                    }
                    else if($data['status'] == 3){
                        $status = "Отклонено";
                    }
                    return $status;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
