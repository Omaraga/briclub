<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TicketsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Переводы';
$this->params['breadcrumbs'][] = $this->title;
$columns = [
    ['class' => 'yii\grid\SerialColumn'],

    //'id',
    //'category',
//    [
//        'attribute'=>'title',
//        'content'=>function($data){
//            return Html::a($data['title'],'/tickets/view?id='.$data['id']);
//        }
//    ],
    //'time:datetime',
    //'user_id',
    [
        'attribute'=>'user_id',
        'content'=>function($data){
            $user = \common\models\User::findOne($data['user_id']);
            if($user != null){
                return Html::a($user['username'],'/users/view?id='.$data['user_id']);
            }
            else{
                return "";
            }
        }
    ],
    [
        'attribute'=>'user2_id',
        'content'=>function($data){
            $user = \common\models\User::findOne($data['user2_id']);
            if($user != null){
                return Html::a($user['username'],'/users/view?id='.$data['user2_id']);
            }
            else{
                return "";
            }
        }
    ],
    [
        'attribute'=>'time',
        'content'=>function($data){
            return date("d.m.Y H:i",$data['time']);
        }
    ],
    'sum',
    [
        'attribute'=>'status',
        'content'=>function($data){
            if($data['status'] == 1){
                return "Начислено";
            }elseif($data['status'] == 2){
                return "Отменено";
            }else{
                return "В обработке";
            }
        }
    ],
    [
        'attribute'=>'Отменить',
        'content'=>function($data){
            if($data['status'] !=2){
                return Html::a('Отменить', ['/transfers-list/back', 'id' => $data['id']], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Уверены что хотите отменить перевод?',
                    ],
                ]);
            }

        }
    ],
    //'status',
];
echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns
]);
?>
<div class="users-list-index">

    <?php Pjax::begin(); ?>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
