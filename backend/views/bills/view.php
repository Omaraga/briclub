<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Bills */

$this->title = "Счет #" . $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bills-view">
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'code',
            'sum',
            'comment:ntext',
            [
                'attribute' => 'created_at',
                'value' => function($data){
                    return date("d.m.Y H:i", $data['created_at']);
                }
            ],
            [
                'attribute' => 'receiver_id',
                'value' => function($data){
                    return \common\models\User::findOne(['id' => $data['receiver_id']])['username'];
                }
            ],
            [
                'attribute' => 'sender_id',
                'value' => function($data){
                    if($data['sender_id']){
                        return \common\models\User::findOne(['id' => $data['sender_id']])['username'];
                    }
                    else{
                        return null;
                    }
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($data){
                    $status = null;
                    if($data['status'] == 1){
                        $status = 'Оплачено';
                    }
                    else if($data['status'] == 2){
                        $status = 'В ожидании';
                    }
                    else if($data['status'] == 3){
                        $status = 'Отменено';
                    }
                    return $status;
                }
            ],
            [
                'attribute' => 'type',
                'value' => function($data){
                    $type = null;
                    if($data['type'] == 1){
                        $type = 'Greenswop';
                    }
                    if($data['type'] == 2){
                        $type = 'Shanyrakplus';
                    }
                    return $type;
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($data){
                    return $data['updated_at'] ? date("d.m.Y H:i", $data['updated_at']) : null;
                }
            ],
        ],
    ]) ?>

</div>
