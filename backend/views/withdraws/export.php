<?php

use common\models\User;
use kartik\export\ExportMenu;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Запросы на вывод';
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="withdraws-index">
    <div class="site-index">
        <div class="row">
            <?php
            $gridColumns = [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'user_id',
                'time',
                'status',
                ['class' => 'yii\grid\ActionColumn'],
            ];

            // Renders a export dropdown menu
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns
            ]);

            // You can choose to render your own GridView separately
            echo \kartik\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns
            ]);
            ?>
        </div>

    </div>

</div>
