<?php

use common\models\Actions;
use common\models\User;
use kartik\export\ExportMenu;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пополнения';
$this->params['breadcrumbs'][] = $this->title;
$all_perfect = Actions::find()->where(['type'=>5])->orderBy('id desc')->all();
$all_sum = 0;
foreach ($all_perfect as $item) {
    $all_sum = $all_sum + $item['sum'];
}
$all_perfect2 = Actions::find()->where(['type'=>6])->orderBy('id desc')->all();
$all_sum2 = 0;
foreach ($all_perfect2 as $item2) {
    $all_sum2 = $all_sum2 + $item2['sum'];
}
$all_perfect3 = Actions::find()->where(['type'=>[102, 122]])->orderBy('id desc')->all();
$all_sum3 = 0;
foreach ($all_perfect3 as $item3) {
    $all_sum3 = $all_sum3 + $item3['sum'];
}
$all_perfect4 = Actions::find()->where(['type'=>[8,88]])->orderBy('id desc')->all();
$all_sum4 = 0;
foreach ($all_perfect4 as $item4) {
    $all_sum4 = $all_sum4 + $item4['sum'];
}

$logins = Actions::find()->where(['type'=>[5,6,[102,122],103]])->all();
$data1 = array();
foreach ($logins as $item) {
    $data1[] = User::findOne($item['user_id'])['username'];
}

$count = count(Yii::$app->request->queryParams);
if($count > 1){
    $end = end($_GET);
    $key = key($_GET);
    Yii::$app->response->redirect('http://admin.shanyrakplus.com/deposit/index?' . $key . '=' . $end);
}

$dp1 = Yii::$app->request->getQueryParam('dp-1-page');
$dp2 = Yii::$app->request->getQueryParam('dp-2-page');
$dp3 = Yii::$app->request->getQueryParam('dp-3-page');
$page = Yii::$app->request->getQueryParam('page');
if(!$dp1 and !$dp3 and !$page and !$dp2){
	$page = 1;
}
?>
<div class="actions-index">

    <p>
        <?
        if(User::isAccess('superadmin')) {
            echo Html::a('Пополнить', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>
    <ul class="nav nav-tabs">
        <li class="<?=$page ? 'active' : '' ?>"><a data-toggle="tab" href="#panel2">Пополнения админом</a></li>
        <li class="<?=$dp1 ? 'active' : '' ?>"><a data-toggle="tab" href="#panel3">Perfect Money</a></li>
        <li class="<?=$dp2 ? 'active' : '' ?>"><a data-toggle="tab" href="#panel4">Visa/Mastercard</a></li>
        <li class="<?=$dp3 ? 'active' : '' ?>"><a data-toggle="tab" href="#panel5">Payeer</a></li>
    </ul>

    <div class="tab-content">
        <div id="panel2" class="tab-pane fade <?=$page ? 'active in' : '' ?>">
            <h3>Пополнения админом</h3>
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
            <p>Всего на сумму: $<?=$all_sum?></p>
            <?
            $columns = [
                ['class' => 'yii\grid\SerialColumn'],

                'sum',
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
        <div id="panel3" class="tab-pane fade <?=$dp1 ? 'active in' : '' ?>">
            <h3>Perfect Money</h3>
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
                                            'options' => ['placeholder' => 'Введите логин ...','id'=>'data2','class'=>'form-control','autocomplete'=>'off'],
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
            <p>Всего на сумму: $<?=$all_sum2?></p>
            <?
            $columns2 = [
                ['class' => 'yii\grid\SerialColumn'],

                'sum',
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
            ];
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider2,
                'columns' => $columns2
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider2,
                'columns' => $columns2,
            ]); ?>
        </div>
        <div id="panel4" class="tab-pane fade <?=$dp2 ? 'active in' : '' ?>">
            <h3>Visa</h3>
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
                                            'options' => ['placeholder' => 'Введите логин ...','id'=>'data2','class'=>'form-control','autocomplete'=>'off'],
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
            <p>Всего на сумму: $<?=$all_sum3?></p>
            <?
            $columns3 = [
                ['class' => 'yii\grid\SerialColumn'],

                'sum',
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
            ];
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider3,
                'columns' => $columns3
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider3,
                'columns' => $columns3,
            ]); ?>
        </div>
        <div id="panel5" class="tab-pane fade <?=$dp3 ? 'active in' : '' ?>">
            <h3>Payeer</h3>
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
                                            'options' => ['placeholder' => 'Введите логин ...','id'=>'data2','class'=>'form-control','autocomplete'=>'off'],
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
            <p>Всего на сумму: $<?=$all_sum4?></p>
            <?
            $columns4 = [
                ['class' => 'yii\grid\SerialColumn'],

                'sum',
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
            ];
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider4,
                'columns' => $columns4
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider4,
                'columns' => $columns4,
            ]); ?>
        </div>

    </div>

</div>
