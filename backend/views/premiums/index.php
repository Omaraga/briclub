<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Премиум-аккаунты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="premiums-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
                    $action = \common\models\Actions::find()
                        ->where(['user_id' => $data['user_id']])
                        ->andWhere(['>=', 'type', 80])
                        ->andWhere(['<=', 'type', 84])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();


                    return date("d.m.Y H:i",$action['time']);
                }
            ],
            [
                'attribute'=>'expires_at',
                'content'=>function($data){
                    return date("d.m.Y H:i",$data['expires_at'] + $data['time']);
                }
            ],
            [
                'attribute'=>'is_active',
                'content'=>function($data){
                    return $data['is_active'] == 1 ? "Да" : "Нет";
                }
            ],
            [
                'attribute' => 'premium_id',
                'content' => function($data){
                    return \common\models\PremiumTariffs::findOne($data['tariff_id'])->name;
                }
            ],
            [
                'label' => 'Способ оплаты',
                'value' => function($data){
                    $action = \common\models\Actions::find()
                    ->where(['user_id' => $data['user_id']])
                    ->andWhere(['>=', 'type', 80])
                    ->andWhere(['<=', 'type', 84])
                    ->one();

                    $pay_type = \common\models\ActionTypes::findOne($action->type);

                    return $pay_type->title;
                }
            ]
            //'img_source',


        ],
    ]); ?>
</div>
