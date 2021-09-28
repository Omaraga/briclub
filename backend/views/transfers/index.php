<?php

use common\models\Actions;
use common\models\User;
use kartik\export\ExportMenu;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Переводы';

$this->params['breadcrumbs'][] = $this->title;
$logins = Actions::find()->where(['type'=>3])->all();
$data = array();
foreach ($logins as $item) {
    $data[] = User::findOne($item['user_id'])['username'];
}
$columns = [
    ['class' => 'yii\grid\SerialColumn'],


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
    [
        'attribute'=>'user2_id',
        'content'=>function($data){
            $user = \common\models\User::findOne($data['user2_id']);
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
    'sum',
    [
        'attribute'=>'status',
        'content'=>function($data){
            if($data['status'] == 1){
                return "Начислено";
            }elseif($data['status'] == 2){
                return "Отменено";
            }else{
                return "В обработке";
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
        'attribute'=>'Отменить',
        'content'=>function($data){
            if($data['status'] !=2){
                return Html::a('Отменить', ['/transfers/back', 'id' => $data['id']], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Уверены что хотите отменить перевод?',
                    ],
                ]);
            }

        }
    ],
];
echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns
]);
?>
<div class="actions-index">

    <div class="site-index">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <form id="w0" action="/transfers" method="get">

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
                </div>
            </div>
        </div>

    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]); ?>
</div>
