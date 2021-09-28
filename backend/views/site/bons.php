<?php

use common\models\Actions;
use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Начисления дробные';

$this->params['breadcrumbs'][] = $this->title;
$acts = Actions::find()->where(['comment'=>"bon"])->all();
$sum = 0;
foreach ($acts as $act) {
    $sum = $act['sum'] + $sum;
}
?>
<div class="actions-index">
    <h4>Всего начислений на сумму: <?=$sum?></h4>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            [
                'attribute'=>'user_id',
                'content'=>function($data){
                    $user = \common\models\User::findOne($data['user_id']);
                    $matrxes = "";
                    if($user['start'] == 1){
                        $matrxes = $matrxes."Start";
                    }
                    if($user['newmatrix'] == 1){
                        $matrxes = $matrxes." Personal";
                    }
                    if($user['global'] == 1){
                        $matrxes = $matrxes." Global";
                    }
                    return "<a href='/users/view?id=".$user['id']."'>".$user['username']."</a> (".$matrxes.")";
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
                'attribute'=>'time',
                'content'=>function($data){
                    return date("d.m.Y H:i",$data['time']);
                }
            ],
        ],
    ]); ?>
</div>
