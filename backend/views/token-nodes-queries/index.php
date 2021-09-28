<?php

use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на ноду';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-nodes-queries-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                    'attribute' => 'Логин',
                    'content' => function($data){
                        $user = User::findOne(['id' => $data['user_id']]);
                        return Html::a($user['username'],'/users/view?id='.$user['id']);
                    }
            ],
            'tokens_count',
            'admin_id',
            [
                    'attribute' => 'query_date',
                    'content' => function($data){
                        return date('d.m.Y H:i', $data['query_date']);
                    }
            ],
            [
                    'attribute' => 'status',
                    'content' => function($data){
                        $status = '';
                        if($data['status'] == 1){
                            $status = 'Принята';
                        }
                        else if($data['status'] == 2){
                            $status = 'Отклонена';
                        }
                        else{
                            $status = 'Подана';
                        }
                        return $status;
                    }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
