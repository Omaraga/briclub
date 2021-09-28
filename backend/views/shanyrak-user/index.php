<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Участники Shanyrak';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shanyrak-user-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'user_id',
                'content'=>function($data){
                    $user = \common\models\User::findOne($data['user_id']);
                    return "<a href='/users/view?id=".$user['id']."'>".$user['username']."</a>";
                }
            ],
            [
                'attribute'=>'program_id',
                'content'=>function($data){
                    if($data['program_id'] == 5){
                        return "Недвижимость";
                    }elseif($data['program_id'] == 6){
                        return "Авто";
                    }elseif($data['program_id'] == 7){
                        return "Техника";
                    }

                }
            ],
            [
                'attribute'=>'time',
                'content'=>function($data){
                    return date("d.m.Y H:i",$data['time']);
                }
            ],
            'step',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
