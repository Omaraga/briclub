<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TicketsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Тех поддержка';
$this->params['breadcrumbs'][] = $this->title;
$columns = [
    ['class' => 'yii\grid\SerialColumn'],

    'id',
    //'category',
    [
        'attribute'=>'title',
        'content'=>function($data){
            return Html::a($data['title'],'/tickets/view?id='.$data['id']);
        }
    ],
    //'time:datetime',
    //'user_id',
    [
        'attribute'=>'user_id',
        'content'=>function($data){
            $username = \common\models\User::findOne($data['user_id']);
            return Html::a($username['username'],'/users/view?id='.$data['user_id']);
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
            if($data['status']==3){
                return "В обработке";
            }elseif($data['status']==2){
                return "Отвечен";
            }elseif($data['status']==1){
                return "Закрыт";
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
