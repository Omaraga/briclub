<?php
use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user common\models\User */
/* @var $tab integer */
/* @var $structureSize integer */
/* @var $amountSum integer */
/* @var $amountUserSum integer */

$this->title = 'Структура '.$user->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .nav-item .active{
        background-color: cornflowerblue;
        font-weight: bold;
        color: lightgrey;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist" data-tab="home">
                <li class="nav-item">
                    <a class="nav-link <?=($tab==0)?'active':''?>"  href="/actions/structure-payments?userId=<?=$user->id;?>&tab=0">Пополнение</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?=($tab==1)?'active':''?>"  href="/actions/structure-payments?userId=<?=$user->id;?>&tab=1">Доход</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?=($tab==2)?'active':''?>"   href="/actions/structure-payments?userId=<?=$user->id;?>&tab=2" >Поступления</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?=($tab==3)?'active':''?>" href="/actions/structure-payments?userId=<?=$user->id;?>&tab=3" >Переводы</a>
                </li>
            </ul>

            <div class="col-lg-12">
                <p>
                    Людей в структуре: <b><?=$structureSize;?></b>
                </p>
                <p>
                    Общая сумма операции структуры: <b><?=$amountSum;?> CV</b>
                </p>
                <p>
                    Операции пользователя <i><?=$user->username;?></i>: <b><?=$amountUserSum;?> CV</b>
                </p>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                [
                                    'attribute' => 'user_id',
                                    'label' => 'Пользователь',
                                    'content' => function($data){
                                        $user = \common\models\User::findOne($data['user_id']);
                                        if ($user){
                                            return Html::a($user['username'],['/users/view?id='.$user['id']]);
                                        }else{
                                            return '';
                                        }
                                    }
                                ],
                                'title',
                                'sum',
                                [
                                    'attribute' => 'user2_id',
                                    'label' => 'Отправитель',
                                    'content' => function($data){
                                        $user2 = \common\models\User::findOne($data['user2_id']);
                                        if ($user2){
                                            return $user2['username'];
                                        }else{
                                            return '';
                                        }
                                    }
                                ],
                                //'type',
//            [
//                'attribute' => 'type',
//                'content' => function($data){
//                    return \common\models\ActionTypes::findOne($data['type'])['title'];
//                }
//            ],
                                //'user_id',
                                [
                                    'label' => 'Спонсор',
                                    'content' => function($data){
                                        $currUser = \common\models\User::findOne($data['user_id']);
                                        $sponsor = \common\models\User::findOne($currUser['parent_id']);
                                        if ($sponsor){
                                            return Html::a($sponsor['username'],['/users/view?id='.$sponsor['id']]);
                                        }else{
                                            return 'Не задано';
                                        }
                                    }
                                ],
                                [
                                    'attribute'=>'time',
                                    'content'=>function($data){
                                        return date("d.m.Y H:i",$data['time']);
                                    }
                                ],
                                //'status',


                                //'comment',
                                //'admin_id',
                                //'content:ntext',
                                //'view',

                            ],
                        ]); ?>

            </div>
        </div>
    </div>
</div>





