<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\typeahead\TypeaheadBasic;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $dataProviderWork yii\data\ActiveDataProvider */
/* @var $dataProviderAnswered yii\data\ActiveDataProvider */
/* @var $dataProviderEnd yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\TicketSearchForm */
/* @var $users array */

$this->title = 'Запросы';
$this->params['breadcrumbs'][] = $this->title;
$tab = 1;
$tab1 = Yii::$app->request->get("dp-1-page");
if ($tab1){
    $tab = 2;
}
$tab2 = Yii::$app->request->get("dp-2-page");
if ($tab2){
    $tab = 3;
}
?>
<main id="">
    <div class="container-fluid page-wrap">
        <div class="row">
            <? $form = \yii\widgets\ActiveForm::begin();?>
            <div class="col-md-4">

                <?=$form->field($searchModel, 'username')->widget(TypeaheadBasic::classname(), [
                'data' => $users,
                'options' => ['placeholder' => 'Логин', 'autocomplete'=>'off'],
                'pluginOptions' => ['highlight'=>true],
                ])->label('Логин');?>
            </div>
            <div class="col-md-4">

                <?=$form->field($searchModel, 'id')->label('ID');?>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-3">
                        <?=$form->field($searchModel, 'date_start')->widget(DatePicker::className(),[
                            'language' => 'ru',
                            'dateFormat' => 'dd.MM.yyyy',
                            'options'=>['autocomplete'=>'off'],
                        ])->label('Дата с:');?>
                    </div>
                    <div class="col-md-3">
                        <?=$form->field($searchModel, 'date_end')->widget(DatePicker::className(),[
                            'language' => 'ru',
                            'dateFormat' => 'dd.MM.yyyy',
                            'options'=>['autocomplete'=>'off'],
                        ])->label('Дата по:');?>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <?=Html::submitButton('Поиск',['class'=>'btn btn-primary']);?>
            </div>
            <? \yii\widgets\ActiveForm::end();?>

        </div>
        <br><br>
        <!-- content here -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="<?=($tab == 1)?'active':'';?>"><a href="#open" aria-controls="open" role="tab" data-toggle="tab">В обработке</a></li>
            <li role="presentation" class="<?=($tab == 2)?'active':'';?>"><a href="#check" aria-controls="check" role="tab" data-toggle="tab">Отвечен</a></li>
            <li role="presentation" class="<?=($tab == 3)?'active':'';?>"><a href="#close" aria-controls="close" role="tab" data-toggle="tab">Завершен</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade <?=($tab == 1)?'in active':'';?>" id="open">

                    <?= GridView::widget([
                        'dataProvider' => $dataProviderWork,
                        'columns' => [
                            //['class' => 'yii\grid\SerialColumn'],

                            'id',
                            //'category',
                            [
                                'attribute'=>'title',
                                'content'=>function($data){
                                    return Html::a($data['title'],'/tickets/view?id='.$data['id']);
                                }
                            ],
                            //'time:datetime',
                            //'user_id',
                            [
                                'attribute'=>'user_id',
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
                                'label'=>'Поступил',
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
                                'content'=>function($data){
                                    if($data['status']==3){
                                        return "В обработке";
                                    }elseif($data['status']==2){
                                        return "Отвечен";
                                    }elseif($data['status']==1){
                                        return "Закрыт";
                                    }
                                }
                            ],
                            [
                                'attribute'=>'dev_status',
                                'label'=>'Статус разраб.',
                                'content'=>function($data){
                                    if($data['dev_status']==3){
                                        return "Закрыт";
                                    }elseif($data['dev_status']==2){
                                        return "На проверке";
                                    }elseif($data['dev_status']==1){
                                        return "Принято в работу";
                                    }elseif($data['dev_status']==0){
                                        return "";
                                    }
                                }
                            ],
                            //'status',


                        ],
                    ]); ?>

            </div>
            <div role="tabpanel" class="tab-pane fade <?=($tab == 2)?'in active':'';?>" id="check">

                    <?= GridView::widget([
                        'dataProvider' => $dataProviderAnswered,
                        'columns' => [
                           // ['class' => 'yii\grid\SerialColumn'],

                            'id',
                            //'category',
                            [
                                'attribute'=>'title',
                                'content'=>function($data){
                                    return Html::a($data['title'],'/tickets/view?id='.$data['id']);
                                }
                            ],
                            //'time:datetime',
                            //'user_id',
                            [
                                'attribute'=>'user_id',
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
                                'label'=>'Поступил',
                                'content'=>function($data){
                                    return date("d.m.Y H:i",$data['time']);
                                }
                            ],
                            [
                                'attribute'=>'end_time',
                                'label'=>'Поступил',
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
                                'content'=>function($data){
                                    if($data['status']==3){
                                        return "В обработке";
                                    }elseif($data['status']==2){
                                        return "Отвечен";
                                    }elseif($data['status']==1){
                                        return "Закрыт";
                                    }
                                }
                            ],
                            [
                                'attribute'=>'dev_status',
                                'label'=>'Статус разраб.',
                                'content'=>function($data){
                                    if($data['dev_status']==3){
                                        return "Закрыт";
                                    }elseif($data['dev_status']==2){
                                        return "На проверке";
                                    }elseif($data['dev_status']==1){
                                        return "Принято в работу";
                                    }elseif($data['dev_status']==0){
                                        return "";
                                    }
                                }
                            ],
                            //'status',


                        ],
                    ]); ?>

            </div>
            <div role="tabpanel" class="tab-pane fade <?=($tab == 3)?'in active':'';?>" id="close">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderEnd,
                        'columns' => [
                            //['class' => 'yii\grid\SerialColumn'],

                            'id',
                            //'category',
                            [
                                'attribute'=>'title',
                                'content'=>function($data){
                                    return Html::a($data['title'],'/tickets/view?id='.$data['id']);
                                }
                            ],
                            //'time:datetime',
                            //'user_id',
                            [
                                'attribute'=>'user_id',
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
                                'label'=>'Поступил',
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
                                'content'=>function($data){
                                    if($data['status']==3){
                                        return "В обработке";
                                    }elseif($data['status']==2){
                                        return "Отвечен";
                                    }elseif($data['status']==1){
                                        return "Закрыт";
                                    }
                                }
                            ],
                            [
                                'attribute'=>'dev_status',
                                'label'=>'Статус разраб.',
                                'content'=>function($data){
                                    if($data['dev_status']==3){
                                        return "Закрыт";
                                    }elseif($data['dev_status']==2){
                                        return "На проверке";
                                    }elseif($data['dev_status']==1){
                                        return "Принято в работу";
                                    }elseif($data['dev_status']==0){
                                        return "";
                                    }
                                }
                            ],
                            //'status',


                        ],
                    ]); ?>


            </div>

        </div>

    </div>
</main>

