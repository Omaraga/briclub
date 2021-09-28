<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;
$statuses = \common\models\BedStatuses::find()->all();
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
    <div>
        <form class="form-inline">
            <p>Фильтр:</p>
            <select name="status_id" class="form-control">
                <option value="">Все заявки</option>
                <? foreach ($statuses as $status) {?>
                    <option <?if($status_id == $status['id']){echo "selected";}?> value="<?=$status['id']?>"><?=$status['title']?></option>
                <?}?>
            </select>
            <button type="submit" class="btn btn-success mt-1">Выбрать</button>
        </form>
        <br>
        <br>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            'email:email',
            'tel',
            [
                'attribute' => 'course_id',
                'value' => 'courses.title',
            ],
            [
                'attribute'=>'status',
                'content'=>function($data){
                    $status = \common\models\BedStatuses::findOne($data['status']);
                    return '<span class="badge badge-'.$status['color'].'">'.$status['title'].'</span>';
                }
            ],
            [
                'attribute'=>'created_at',
                'content'=>function($data){
                    if(!empty($data['created_at'])){
                        return date('d.m.Y H:i',$data['created_at']);
                    }
                }
            ],
            [
                'attribute'=>'access',
                'content'=>function($data){
                    if($data['access'] == 1) return "Открыт"; else return "Закрыт";
                }
            ],
            //'type',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {delete}'
            ],
        ],
    ]); ?>
</div>

