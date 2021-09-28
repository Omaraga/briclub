<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счета для пополнения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-accounts-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'system',
                'content'=>function($data){
                    if($data['system'] == 1){
                        return "Advcash";
                    }elseif($data['system'] == 2){
                        return "Альфабанк";
                    }
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update}'
                ],
        ],
    ]); ?>
</div>
