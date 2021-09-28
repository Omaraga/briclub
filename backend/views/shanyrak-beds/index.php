<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на программу';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shanyrak-beds-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'program_id',
                'content'=>function($data){
                    $program = \common\models\ShanyrakInfo::findOne($data['program_id'] );
                    return $program['title'];
                }
            ],
            [
                'attribute'=>'user_id',
                'content'=>function($data){
                    $user = \common\models\User::findOne($data['user_id']);
                    return "<a href='/users/view?id=".$user['id']."'>".$user['username']."</a>";
                }
            ],
            [
                'attribute'=>'time',
                'content'=>function($data){
                    return date("d.m.Y H:i",$data['time']);
                }
            ],
            [
                'attribute'=>'status',
                'content'=>function($data){
                    if($data['status'] == 1){
                        return "Одобрено";
                    }elseif($data['status'] == 2){
                        return "В обработке";
                    }elseif($data['status'] == 3){
                        return "Отклонено";
                    }
                }
            ],

            ['class' => 'yii\grid\ActionColumn','template'=>'{view}'],
        ],
    ]); ?>
</div>
