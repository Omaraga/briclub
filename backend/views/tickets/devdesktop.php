<?php
/* @var $tickets common\models\Tickets */
/* @var $dataProviderReady yii\data\ActiveDataProvider */
/* @var $dataProviderWait yii\data\ActiveDataProvider */
/* @var $dataProviderCheck yii\data\ActiveDataProvider */
use \common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
$this->title = 'Заявки в техподдержку для разработчиков';
function getStatus($id){
    if ($id == 1){
        return 'Завершен';
    }else if($id == 2){
        return 'Отвечен';
    }else{
        return 'В обработке';
    }
}
?>
<style>
    .table_row:hover{
        -webkit-box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2) inset;
        -moz-box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2) inset;
        box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2) inset;
    }
</style>
<main id="">
    <div class="container-fluid page-wrap">
        <!-- content here -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#open" aria-controls="open" role="tab" data-toggle="tab">Открытые</a></li>
            <li role="presentation"><a href="#check" aria-controls="check" role="tab" data-toggle="tab">На проверке</a></li>
            <li role="presentation"><a href="#close" aria-controls="close" role="tab" data-toggle="tab">Завершенные</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="open">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderWait,
                        'columns' => [

                            'id',
                            [
                                'attribute'=>'title',
                                'label'=>'Тема',
                                'content'=>function($data){
                                    return Html::a($data['title'],'/tickets/view?id='.$data['id']);
                                }
                            ],
                            [
                                'attribute'=>'user_id',
                                'label'=>'Пользователь',
                                'content'=>function($data){
                                    $username = \common\models\User::findOne($data['user_id']);
                                    $premium = \common\models\Premiums::findOne(['user_id' => $data['user_id']]);
                                    if($premium && $premium->is_active == 1){
                                        return Html::a('<i class="fa fa-diamond" aria-hidden="true"></i> '.$username['username'],'/users/view?id='.$data['user_id'],['style'=>'color:red; font-weight:bold;']);
                                    }else{
                                        return Html::a($username['username'],'/users/view?id='.$data['user_id']);
                                    }
                                }

                            ],
                            [
                                'attribute'=>'time',
                                'label'=>'Время',
                                'content'=>function($data){
                                    return date("d.m.Y H:i",$data['time']);
                                }
                            ],
                            [
                                'attribute'=>'end_time',
                                'label'=>'Срок заявки',
                                'content'=>function($data){
                                    if (is_null($data['end_time'])){
                                        $endDate = $data['time']+3600*24*3;
                                    }else{
                                        $endDate = $data['end_time'];
                                    }
                                    return date("d.m.Y H:i",$endDate);
                                }
                            ],
                            [
                                'attribute'=>'status',
                                'label'=>'Статус',
                                'content'=>function($data){
                                    if($data['dev_status']==3){
                                        return "Закрыт";
                                    }elseif($data['dev_status']==2){
                                        return "На проверке";
                                    }elseif($data['dev_status']==1){
                                        return "В работе";
                                    }else{
                                        return "В обработке";
                                    }
                                }
                            ],


                        ],
                    ]); ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="check">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderWork,
                        'columns' => [

                            'id',
                            [
                                'attribute'=>'title',
                                'label'=>'Тема',
                                'content'=>function($data){
                                    return Html::a($data['title'],'/tickets/view?id='.$data['id']);
                                }
                            ],
                            [
                                'attribute'=>'user_id',
                                'label'=>'Пользователь',
                                'content'=>function($data){
                                    $username = \common\models\User::findOne($data['user_id']);
                                    $premium = \common\models\Premiums::findOne(['user_id' => $data['user_id']]);
                                    if($premium && $premium->is_active == 1){
                                        return Html::a('<i class="fa fa-diamond" aria-hidden="true"></i> '.$username['username'],'/users/view?id='.$data['user_id'],['style'=>'color:red; font-weight:bold;']);
                                    }else{
                                        return Html::a($username['username'],'/users/view?id='.$data['user_id']);
                                    }
                                }
                            ],
                            [
                                'attribute'=>'time',
                                'label'=>'Время',
                                'content'=>function($data){
                                    return date("d.m.Y H:i",$data['time']);
                                }
                            ],
                            [
                                'attribute'=>'end_time',
                                'label'=>'Срок заявки',
                                'content'=>function($data){
                                    if (is_null($data['end_time'])){
                                        $endDate = $data['time']+3600*24*3;
                                    }else{
                                        $endDate = $data['end_time'];
                                    }
                                    return date("d.m.Y H:i",$endDate);
                                }
                            ],
                            [
                                'attribute'=>'status',
                                'label'=>'Статус',
                                'content'=>function($data){
                                    if($data['dev_status']==3){
                                        return "Закрыт";
                                    }elseif($data['dev_status']==2){
                                        return "На проверке";
                                    }elseif($data['dev_status']==1){
                                        return "В работе";
                                    }else{
                                        return "В обработке";
                                    }
                                }
                            ],


                        ],
                    ]); ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="close">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderReady,
                        'columns' => [

                            'id',
                            [
                                'attribute'=>'title',
                                'label'=>'Тема',
                                'content'=>function($data){
                                    return Html::a($data['title'],'/tickets/view?id='.$data['id']);
                                }
                            ],
                            [
                                'attribute'=>'user_id',
                                'label'=>'Пользователь',
                                'content'=>function($data){
                                    $username = \common\models\User::findOne($data['user_id']);
                                    $premium = \common\models\Premiums::findOne(['user_id' => $data['user_id']]);
                                    if($premium && $premium->is_active == 1){
                                        return Html::a('<i class="fa fa-diamond" aria-hidden="true"></i> '.$username['username'],'/users/view?id='.$data['user_id'],['style'=>'color:red; font-weight:bold;']);
                                    }else{
                                        return Html::a($username['username'],'/users/view?id='.$data['user_id']);
                                    }
                                }
                            ],
                            [
                                'attribute'=>'time',
                                'label'=>'Время',
                                'content'=>function($data){
                                    return date("d.m.Y H:i",$data['time']);
                                }
                            ],
                            [
                                'attribute'=>'end_time',
                                'label'=>'Срок заявки',
                                'content'=>function($data){
                                    if (is_null($data['end_time'])){
                                        $endDate = $data['time']+3600*24*3;
                                    }else{
                                        $endDate = $data['end_time'];
                                    }
                                    return date("d.m.Y H:i",$endDate);
                                }
                            ],
                            [
                                'attribute'=>'status',
                                'label'=>'Статус',
                                'content'=>function($data){
                                    if($data['dev_status']==3){
                                        return "Закрыт";
                                    }elseif($data['dev_status']==2){
                                        return "На проверке";
                                    }elseif($data['dev_status']==1){
                                        return "В работе";
                                    }else{
                                        return "В обработке";
                                    }
                                }
                            ],



                        ],
                    ]); ?>

                </div>
            </div>

        </div>

    </div>
</main>