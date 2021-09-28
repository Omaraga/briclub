<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ShanyrakBeds */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shanyrak заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="shanyrak-beds-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Одобрить заявку', ['success', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <? if(!empty($model->doc3)){echo Html::a('Одобрить договор', ['success2', 'id' => $model->id], ['class' => 'btn btn-success']);} ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label'=>'Пользователь',
                'value'=>function($data){
                    $user = \common\models\User::findOne($data['user_id']);
                    return Html::a($user['username'],'/users/view?id='.$data['user_id']);
                },
                'format' => 'raw'
            ],
            [
                'label'=>'Дата',
                'value'=>function($data){
                    return date("d.m.Y H:i",$data['time']);
                },
            ],
            [
                'label'=>'Статус',
                'value'=>function($data){
                    if($data['status'] == 1){
                        return "Одобрено";
                    }elseif($data['status'] == 2){
                        return "В обработке";
                    }elseif($data['status'] == 3){
                        return "Отклонено";
                    }
                },
            ],
            [
                'label'=>'Удостоверение личности',
                'value'=>function($data){
                    return Html::a('Скачать','https://gcfond.com'.$data['doc1']);
                },
                'format' => 'raw'
            ],
            [
                'label'=>'Договор',
                'value'=>function($data){
                    return Html::a('Скачать','https://gcfond.com'.$data['doc2']);
                },
                'format' => 'raw'
            ],
            [
                'label'=>'Подписанный договор	',
                'value'=>function($data){
                    return Html::a('Скачать','https://gcfond.com'.$data['doc3']);
                },
                'format' => 'raw'
            ],
            'name',
            'iin',
            'doc_num',
            'address:ntext',
            'phone',
            'email:email',
            'rooms',
            'term1',
            'term2',
            'sum_all',
            'sum_first',
            'sum_month_1',
            'sum_month_2',
        ],
    ]) ?>

</div>
