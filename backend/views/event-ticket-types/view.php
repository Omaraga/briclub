<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\EventTicketTypes */
$main_url = Yii::$app->params['mainUrl'];
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Билеты мероприятий', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="event-ticket-types-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'event_id',
            'count',
            'price',
            'status',
            'time:datetime',
        ],
    ]) ?>
    <?
    if(!empty($model->link)){?>
        <img src="<?=$main_url?><?=$model->link?>" class="img" width="300" alt="">
    <?}
    ?>
</div>
