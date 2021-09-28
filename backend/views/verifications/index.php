<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProviderAll yii\data\ActiveDataProvider */
/* @var $dataProviderWait yii\data\ActiveDataProvider */
/* @var $dataProviderCanceled yii\data\ActiveDataProvider */
/* @var $dataProviderComplete yii\data\ActiveDataProvider */
/* @var $data common\models\Verifications */



$this->title = 'Верификация';
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    'id',
    [
        'attribute'=>'stage',
        'label'=>'Стадия верификации',
        'content'=>function($data){
            return Html::a($data->getStageName(), ['verifications/view', 'id' => $data->id]);
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

    ['class' => 'yii\grid\ActionColumn','template'=>'{view}']];


?>

<main id="">
    <div class="container-fluid page-wrap">
        <!-- content here -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#open" aria-controls="open" role="tab" data-toggle="tab">Открытые</a></li>
            <li role="presentation"><a href="#check" aria-controls="check" role="tab" data-toggle="tab">На доработке</a></li>
            <li role="presentation"><a href="#close" aria-controls="close" role="tab" data-toggle="tab">Завершенные</a></li>
            <li role="presentation"><a href="#all" aria-controls="all" role="tab" data-toggle="tab">Все</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="open">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderWait,
                        'columns' => $columns,
                    ]); ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="check">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderCanceled,
                        'columns' => $columns,
                    ]); ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="close">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderComplete,
                        'columns' => $columns,
                    ]); ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="all">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderAll,
                        'columns' => $columns,
                    ]); ?>
                </div>
            </div>


        </div>

    </div>
</main>
<div class="verifications-index">


</div>
