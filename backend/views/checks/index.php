<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сверка балансов';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .badge-primary {
        color: #fff;
        background-color: #007bff;
    }
    .badge-success {
        color: #fff;
        background-color: #28a745;
    }
    .badge-danger {
        color: #fff;
        background-color: #dc3545;
    }
    .badge-warning {
        color: #212529;
        background-color: #ffc107;
    }
</style>
<div class="beds-index">
    <?
    $users = \common\models\User::find()->where(['activ'=>1])->all();
    $i = 0;
    foreach ($users as $user) {
            $acts = \common\models\Actions::find()->where(['user_id'=>$user['id']])->all();
            $sum = 0;
            $sum = number_format($sum,2, '.', '');
            foreach ($acts as $act) {
                if($act['type'] == 2) continue;
                $type = \common\models\ActionTypes::findOne($act['type']);
                if($type['minus'] == 1){
                    if(!empty($act['sum'])){
                        $sum2 = $act['sum'];
                        $sum = $sum - $sum2;
                    }
                }else{
                    if(!empty($act['sum'])){
                        $sum2 = $act['sum'];
                        $sum = $sum + $sum2;
                    }
                }
            }
            $withs = \common\models\Withdraws::find()->where(['user_id'=>$user['id'],'status'=>[1,3,4]])->all();
                foreach ($withs as $with) {
                    $sum = $sum - $with['sum'];
                }
            $sum = number_format($sum,2, '.', '');
            $balans = $user['w_balans'] + $user['b_balans'];
            $balans = number_format($balans,2, '.', '');
            if($sum == $balans){

            }else{

                $i++;
                echo $user['id'].' '.$user['username']." - ".($balans)." ---- ".$sum;
                echo "<br>";
            }

    }
    echo $i;

    ?>
</div>

