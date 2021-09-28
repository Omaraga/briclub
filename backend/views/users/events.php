<?php

use common\models\User;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$dp1 = Yii::$app->request->getQueryParam('dp-1-page');
$dp2 = Yii::$app->request->getQueryParam('dp-2-page');
$dp3 = Yii::$app->request->getQueryParam('dp-3-page');
$dp4 = Yii::$app->request->getQueryParam('dp-4-page');
$page = Yii::$app->request->getQueryParam('page');
if(!$dp1 and !$dp3 and !$page and !$dp2 and !$dp4){
    $page = 1;
}
$this->title = 'Билеты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="actions-index">
    <ul class="nav nav-tabs">
        <li class="<?=$page ? 'active' : '' ?>"><a data-toggle="tab" href="#panel2">Все билеты</a></li>
        <li class="<?=$dp1 ? 'active' : '' ?>"><a data-toggle="tab" href="#panel3">115</a></li>
        <li class="<?=$dp2 ? 'active' : '' ?>"><a data-toggle="tab" href="#panel4">65</a></li>
        <li class="<?=$dp3 ? 'active' : '' ?>"><a data-toggle="tab" href="#panel5">45</a></li>
        <li class="<?=$dp4 ? 'active' : '' ?>"><a data-toggle="tab" href="#panel6">15</a></li>
    </ul>

    <div class="tab-content">
        <div id="panel2" class="tab-pane fade <?=$page ? 'active in' : '' ?>">
            <h3>Все билеты</h3>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],

                    'id',
                    //'title',
                    //'type',
                    //'user_id',
                    [
                        'attribute'=>'Логин',
                        'content'=>function($data){
                            return "<a target='_blank' href='/users/view?id=".$data['user_id']."'>".\common\models\User::findOne($data['user_id'])['username']."</a>";

                        },
						'headerOptions' => ['style' => 'width:20%'],
                    ],
                    [
                        'attribute'=>'ФИО',
                        'content'=>function($data){
                            return \common\models\User::findOne($data['user_id'])['fio'];

                        },
                        'headerOptions' => ['style' => 'width:20%'],
                    ],
                    /*[
                        'attribute'=>'Телефон',
                        'content'=>function($data){
                            return \common\models\User::findOne($data['user_id'])['phone'];

                        }
                    ],*/
                    [
                        'attribute'=>'type_id',
                        'content'=>function($data){
                            return \common\models\EventTicketTypes::findOne($data['type_id'])['title'];
                        }
                    ],
                   /* [
                        'attribute'=>'Подпись',
                        'content'=>function($data){
                            return "";
                        }
                    ],*/
                ],
            ]); ?>
        </div>
        <div id="panel3" class="tab-pane fade <?=$dp1 ? 'active in' : '' ?>">
            <h3>115</h3>
            <?= GridView::widget([
                'dataProvider' => $dataProvider1,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    //'title',
                    //'type',
                    //'user_id',
                    [
                        'attribute'=>'user_id',
                        'content'=>function($data){
                            return "<a target='_blank' href='/users/view?id=".$data['user_id']."'>".\common\models\User::findOne($data['user_id'])['username']."</a>";

                        }
                    ],
                    [
                        'attribute'=>'type_id',
                        'content'=>function($data){
                            return \common\models\EventTicketTypes::findOne($data['type_id'])['price'];
                        }
                    ],
                    [
                        'attribute'=>'time',
                        'content'=>function($data){
                            return date('d.m.Y H:i',$data['time']);
                        }
                    ],
                    [
                        'attribute'=>'link',
                        'content'=>function($data){
                            return "<a target='_blank' href='https://shanyrakplus.com/".$data['link']."'>Смотреть</a>";
                        }
                    ],
                    //'user2_id',
                    //'sum',
                    //'comment',
                    //'admin_id',
                    //'content:ntext',
                    //'view',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
        <div id="panel4" class="tab-pane fade <?=$dp2 ? 'active in' : '' ?>">
            <h3>65</h3>
            <?= GridView::widget([
                'dataProvider' => $dataProvider2,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    //'title',
                    //'type',
                    //'user_id',
                    [
                        'attribute'=>'user_id',
                        'content'=>function($data){
                            return "<a target='_blank' href='/users/view?id=".$data['user_id']."'>".\common\models\User::findOne($data['user_id'])['username']."</a>";

                        }
                    ],
                    [
                        'attribute'=>'type_id',
                        'content'=>function($data){
                            return \common\models\EventTicketTypes::findOne($data['type_id'])['price'];
                        }
                    ],
                    [
                        'attribute'=>'time',
                        'content'=>function($data){
                            return date('d.m.Y H:i',$data['time']);
                        }
                    ],
                    [
                        'attribute'=>'link',
                        'content'=>function($data){
                            return "<a target='_blank' href='https://shanyrakplus.com/".$data['link']."'>Смотреть</a>";
                        }
                    ],
                    //'user2_id',
                    //'sum',
                    //'comment',
                    //'admin_id',
                    //'content:ntext',
                    //'view',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
        <div id="panel5" class="tab-pane fade <?=$dp3 ? 'active in' : '' ?>">
            <h3>45</h3>

            <?= GridView::widget([
                'dataProvider' => $dataProvider3,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    //'title',
                    //'type',
                    //'user_id',
                    [
                        'attribute'=>'Логин',
                        'content'=>function($data){
                            return "<a target='_blank' href='/users/view?id=".$data['user_id']."'>".\common\models\User::findOne($data['user_id'])['username']."</a>";

                        }
                    ],
                    [
                        'attribute'=>'ФИО',
                        'content'=>function($data){
                            return \common\models\User::findOne($data['user_id'])['fio'];

                        }
                    ],
                    [
                        'attribute'=>'Телефон',
                        'content'=>function($data){
                            return \common\models\User::findOne($data['user_id'])['phone'];

                        }
                    ],
                    [
                        'attribute'=>'type_id',
                        'content'=>function($data){
                            return \common\models\EventTicketTypes::findOne($data['type_id'])['title'];
                        }
                    ],
                    [
                        'attribute'=>'Подпись',
                        'content'=>function($data){
                            return "";
                        }
                    ],
                    /*[
                        'attribute'=>'time',
                        'content'=>function($data){
                            return date('d.m.Y H:i',$data['time']);
                        }
                    ],
                    [
                        'attribute'=>'link',
                        'content'=>function($data){
                            return "<a target='_blank' href='https://shanyrakplus.com/".$data['link']."'>Смотреть</a>";
                        }
                    ],*/
                    //'user2_id',
                    //'sum',
                    //'comment',
                    //'admin_id',
                    //'content:ntext',
                    //'view',

                    //['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
        <div id="panel6" class="tab-pane fade <?=$dp4 ? 'active in' : '' ?>">
            <h3>15</h3>

            <?= GridView::widget([
                'dataProvider' => $dataProvider4,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    //'title',
                    //'type',
                    //'user_id',
                    [
                        'attribute'=>'user_id',
                        'content'=>function($data){
                            return "<a target='_blank' href='/users/view?id=".$data['user_id']."'>".\common\models\User::findOne($data['user_id'])['username']."</a>";

                        }
                    ],
                    [
                        'attribute'=>'type_id',
                        'content'=>function($data){
                            return \common\models\EventTicketTypes::findOne($data['type_id'])['price'];
                        }
                    ],
                    [
                        'attribute'=>'time',
                        'content'=>function($data){
                            return date('d.m.Y H:i',$data['time']);
                        }
                    ],
                    [
                        'attribute'=>'link',
                        'content'=>function($data){
                            return "<a target='_blank' href='https://shanyrakplus.com/".$data['link']."'>Смотреть</a>";
                        }
                    ],
                    //'user2_id',
                    //'sum',
                    //'comment',
                    //'admin_id',
                    //'content:ntext',
                    //'view',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>

    </div>


</div>
