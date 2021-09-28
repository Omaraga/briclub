<?php

use common\models\Actions;
use common\models\User;
use kartik\export\ExportMenu;
use kartik\typeahead\TypeaheadBasic;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Покупки токенов';
$this->params['breadcrumbs'][] = $this->title;
$all_perfect = Actions::find()->where(['type'=>55,'system'=>11])->orderBy('id desc')->all();
$all_sum = 0;
foreach ($all_perfect as $item) {
    $all_sum = $all_sum + $item['sum'];
}
$all_perfect2 = Actions::find()->where(['type'=>55,'system'=>1])->orderBy('id desc')->all();
$all_sum2 = 0;
foreach ($all_perfect2 as $item2) {
    $all_sum2 = $all_sum2 + $item2['sum'];
}
$all_perfect3 = Actions::find()->where(['type'=>55,'system'=>2])->orderBy('id desc')->all();
$all_sum3 = 0;
foreach ($all_perfect3 as $item3) {
    $all_sum3 = $all_sum3 + $item3['sum'];
}
$all_perfect4 = Actions::find()->where(['type'=>55,'system'=>3])->orderBy('id desc')->all();
$all_sum4 = 0;
foreach ($all_perfect4 as $item4) {
    $all_sum4 = $all_sum4 + $item4['sum'];
}


$count = count(Yii::$app->request->queryParams);
if($count > 1){
    $end = end($_GET);
    $key = key($_GET);
    Yii::$app->response->redirect('http://admin.shanyrakplus.com/deposit/index?' . $key . '=' . $end);
}

$dp1 = Yii::$app->request->getQueryParam('dp-1-page');
$dp2 = Yii::$app->request->getQueryParam('dp-2-page');
$dp3 = Yii::$app->request->getQueryParam('dp-3-page');
$page = Yii::$app->request->getQueryParam('page');
if(!$dp1 and !$dp3 and !$page and !$dp2){
    $page = 1;
}
?>
<div class="actions-index">

    <p>
        <?
        if(User::isAccess('superadmin')) {
            echo Html::a('Пополнить', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>
    <ul class="nav nav-tabs">
        <li class="<?=$page ? 'active' : '' ?>"><a data-toggle="tab" href="#panel2">За внутренние</a></li>
        <li class="<?=$dp1 ? 'active' : '' ?>"><a data-toggle="tab" href="#panel3">Perfect Money</a></li>
        <li class="<?=$dp2 ? 'active' : '' ?>"><a data-toggle="tab" href="#panel4">Visa/Mastercard</a></li>
        <li class="<?=$dp3 ? 'active' : '' ?>"><a data-toggle="tab" href="#panel5">Payeer</a></li>
    </ul>

    <div class="tab-content">
        <div id="panel2" class="tab-pane fade <?=$page ? 'active in' : '' ?>">
            <h3>За внутренние</h3>

            <p>Всего на сумму: $<?=$all_sum?></p>
            <p>Всего токенов: <?=$all_sum/10?> GRC</p>
            <?
            $columns = [
                ['class' => 'yii\grid\SerialColumn'],

                'sum',
                'tokens',
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
                'comment',
                'promo',

            ];
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $columns
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ]); ?>
        </div>
        <div id="panel3" class="tab-pane fade <?=$dp1 ? 'active in' : '' ?>">
            <h3>Perfect Money</h3>
            <p>Всего на сумму: $<?=$all_sum2?></p>
            <p>Всего токенов: <?=$all_sum2/10?> GRC</p>
            <?
            $columns2 = [
                ['class' => 'yii\grid\SerialColumn'],

                'sum',
                'tokens',
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
                'comment',
                'promo',
            ];
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider2,
                'columns' => $columns2
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider2,
                'columns' => $columns2,
            ]); ?>
        </div>
        <div id="panel4" class="tab-pane fade <?=$dp2 ? 'active in' : '' ?>">
            <h3>Visa</h3>

            <p>Всего на сумму: $<?=$all_sum3?></p>
            <p>Всего токенов: <?=$all_sum3/10?> GRC</p>
            <?
            $columns3 = [
                ['class' => 'yii\grid\SerialColumn'],

                'sum',
                'tokens',
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
                'comment',
                'promo',
            ];
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider3,
                'columns' => $columns3
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider3,
                'columns' => $columns3,
            ]); ?>
        </div>
        <div id="panel5" class="tab-pane fade <?=$dp3 ? 'active in' : '' ?>">
            <h3>Payeer</h3>

            <p>Всего на сумму: $<?=$all_sum4?></p>
            <p>Всего токенов: <?=$all_sum4/10?> GRC</p>
            <?
            $columns4 = [
                ['class' => 'yii\grid\SerialColumn'],

                'sum',
                'tokens',
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
                'comment',
                'promo',
            ];
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider4,
                'columns' => $columns4
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider4,
                'columns' => $columns4,
            ]); ?>
        </div>

    </div>

</div>
