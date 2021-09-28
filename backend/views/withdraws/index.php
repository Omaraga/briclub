<?php

use common\models\User;
use kartik\export\ExportMenu;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Запросы на вывод';
$this->params['breadcrumbs'][] = $this->title;
$logins = \common\models\Withdraws::find()->all();
$data = array();
foreach ($logins as $item) {
    $data[] = User::findOne($item['user_id'])['username'];
}

$all1 = \common\models\Withdraws::find()->where(['status'=>1])->all();
$sum1 = 0;
foreach ($all1 as $item1) {
    $sum1 = $sum1 + $item1['sum'];
}

$all2 = \common\models\Withdraws::find()->where(['status'=>2])->all();
$sum2 = 0;
foreach ($all2 as $item2) {
    $sum2 = $sum2 + $item2['sum'];
}

$all3 = \common\models\Withdraws::find()->where(['status'=>3])->all();
$sum3 = 0;
foreach ($all3 as $item3) {
    $sum3 = $sum3 + $item3['sum'];
}

$all4 = \common\models\Withdraws::find()->where(['status'=>4])->all();
$sum4 = 0;
foreach ($all4 as $item4) {
    $sum4 = $sum4+ $item4['sum'];
}
?>

<?php
$columns = [
    ['class' => 'yii\grid\SerialColumn'],

    //'id',
    [
        'attribute'=>'user_id',
        'content'=>function($data){
            $user = \common\models\User::findOne($data['user_id']);
            $matrxes = "";
            if($user['start'] == 1){
                $matrxes = $matrxes."Start";
            }
            if($user['newmatrix'] == 1){
                $matrxes = $matrxes." Personal";
            }
            if($user['global'] == 1){
                $matrxes = $matrxes." Global";
            }
            return "<a href='/users/view?id=".$user['id']."'>".$user['username']."</a> (".$matrxes.")";
        }
    ],
    'account',
    'sum',
    'fee',
    'sum2',
    [
        'attribute'=>'system_id',
        'content'=>function($data){
            if($data['system_id'] == 1){
                return "Advcash";
            }elseif($data['system_id'] == 2){
                return "Perfect Money";
            }elseif($data['system_id'] == 3){
                return "Payeer";
            }
        }
    ],

    [
        'attribute'=>'time',
        'content'=>function($data){
            return date("d.m.Y H:i",$data['time']);
        }
    ],
    [
        'attribute'=>'status',
        'content'=>function($data){
            if($data['status'] == 1){
                return "Начислено";
            }elseif($data['status'] == 2){
                return "Отменено";
            }elseif($data['status'] == 3){
                return "В обработке";
            }elseif($data['status'] == 4){
                return "Начислено вручную";
            }
        }
    ],
    [
        'attribute'=>'admin_id',
        'content'=>function($data){
            $admin = User::findOne($data['admin_id']);
            if(!empty($admin)){
                return $admin['username'];
            };
        }
    ],

    //'status',
];
// Renders a export dropdown menu
echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns
]);

if(User::isAccess('superadmin')){
    $columns[] = ['class' => 'yii\grid\ActionColumn',
        'template' => '{update}',
    ];
}
?>
<div class="withdraws-index">
    <div class="site-index">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <form id="w0" action="/withdraws" method="get">

                            <div class="form-group field-activities-username">
                                <label class="control-label" for="activities-username">Логин</label>
                                <?
                                echo TypeaheadBasic::widget([
                                    'name' => 'username',
                                    'data' =>  $data,
                                    'options' => ['placeholder' => 'Введите логин ...','id'=>'username','class'=>'form-control','autocomplete'=>'off'],
                                    'pluginOptions' => ['highlight'=>true],
                                ]);
                                ?>
                                <!--<input type="text" id="username" <?/*if(!empty($username)){*/?>value="<?/*=$username*/?>" <?/*}*/?> class="form-control" name="username">-->

                                <div class="help-block"><?=$error?></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Поиск</button>    </div>

                        </form>
                    </div>
                    <div class="col-sm-4">
                        <form id="w0" action="/withdraws" method="get">

                            <div class="form-group field-activities-username">
                                <label class="control-label" for="activities-username">Статус</label>
                                <select name="status" class="form-control">
                                    <option value="1">Начислено</option>
                                    <option value="4">Начислено вручную</option>
                                    <option value="2">Отменено</option>
                                    <option value="3">В обработке</option>
                                </select>
                                <div class="help-block"><?=$error?></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Поиск</button>
                            </div>

                        </form>
                    </div>
					<div class="col-sm-4">
                        <form id="w0" action="/withdraws" method="get">

                            <div class="form-group field-activities-username">
                                <label class="control-label" for="wallet" >Кошелек</label>
                                <input type="text" name="wallet" class="form-control tt-input" placeholder="Введите кошелек">
                                <div class="help-block"><?=$error?></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Поиск</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-8">
                        <p>Всего начислено: <?=count($all1)?> На сумму: <?=$sum1?></p>
                        <p>Всего в обработке: <?=count($all2)?> На сумму: <?=$sum2?></p>
                        <p>Всего отменено: <?=count($all3)?> На сумму: <?=$sum3?></p>
                        <p>Всего начислено вручную: <?=count($all4)?> На сумму: <?=$sum4?></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns
    ]); ?>
</div>
