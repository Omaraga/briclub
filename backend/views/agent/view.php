<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\grid\GridView;
$this->title = "Информация представителя";
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($model->username) ?></h1>

    <p>
        <?if($model->agent_status == \backend\models\AgentForm::ACTIVE):?>
            <?= Html::a('Заблокировать', ['block', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?else:?>
            <?= Html::a('Активировать', ['active', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?endif;?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Логин',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data['username'], ['users/view?id='.$data['id']]);
                }
            ],
            'email:email',
            'fio',
            'phone',
            'created_at:date',
            'w_balans',
        ],
    ]) ?>
    <hr>
    <h1>Операции</h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'sum',
            [
                'attribute' => 'user2_id',
                'label' => 'Кому',
                'content' => function($data){
                    if ($data['user2_id']){
                        $user_to = \common\models\User::findOne($data['user2_id']);
                        return $user_to['username'];
                    }

                    return "";
                }
            ],
            [
                'attribute' => 'type',
                'label' => 'Тип',
                'content' => function($data){
                    if ($data['type']){
                        $type = \common\models\ActionTypes::findOne($data['type']);
                        return $type['title'];
                    }
                    return "";
                }
            ],
            'time:datetime',

        ],
    ]); ?>

</div>
