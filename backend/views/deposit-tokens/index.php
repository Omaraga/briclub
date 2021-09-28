<?php

use common\models\Actions;
use common\models\User;
use kartik\export\ExportMenu;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пополнения токенов';
$this->params['breadcrumbs'][] = $this->title;

$all_tokens = Actions::find()->where(['type'=>63])->sum('tokens');


$logins = Actions::find()->where(['type'=>[63]])->all();
$data1 = array();
foreach ($logins as $item) {
    $data1[] = User::findOne($item['user_id'])['username'];
}
?>
<div class="actions-index">

    <p>
        <?
        if(User::isAccess('admin')) {
            echo Html::a('Пополнить', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

    <div class="tab-content">
        <div class="site-index">
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-4">
                            <form id="w0" action="/deposit" method="get">

                                <div class="form-group field-activities-username">
                                    <label class="control-label" for="activities-username">Логин</label>
                                    <?
                                    echo TypeaheadBasic::widget([
                                        'name' => 'username',
                                        'data' =>  $data1,
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
        <p>Всего пополнено токенов: <?=$all_tokens?></p>
        <?
        $columns = [
            ['class' => 'yii\grid\SerialColumn'],

            'tokens',
            [
                'attribute'=>'user_id',
                'content'=>function($data){
                    $user = \common\models\User::findOne($data['user_id']);
                    return "<a href='/users/view?id=".$user['id']."'>".$user['username']."</a>";
                }
            ],
            [
                'attribute'=>'time',
                'content'=>function($data){
                    return date("d.m.Y H:i",$data['time']);
                }
            ],
            'comment',
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
                'attribute'=>'admin_id',
                'content'=>function($data){
                    $admin = User::findOne($data['admin_id']);
                    if(!empty($admin)){
                        return $admin['username'];
                    };
                }
            ],
        ];
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $columns
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]); ?>
    </div>

</div>
