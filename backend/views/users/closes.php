<?php

use common\models\User;
use kartik\export\ExportMenu;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи закрывшие площадки';
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = $this->title;
$logins = \common\models\User::find()->all();
$data = array();
$data2 = array();
$data3 = array();
$data4 = array();
foreach ($logins as $item) {
    $data[] = $item['username'];
    $data2[] = $item['email'];
    $data3[] = $item['fio'];
    $data4[] = $item['phone'];
}
$all_balans = 0;
$all_minus_balans = 0;

$users = User::find()->all();
$c = 0;
foreach ($users as $user) {
    if($user['w_balans']<0){
        $all_minus_balans = $all_minus_balans - $user['w_balans'];
        $c++;
    }else{
        $all_balans = $all_balans + $user['w_balans'];
    }

}

$columns = [
            ['class' => 'yii\grid\SerialColumn'],

            'email',
            [
                'attribute'=>'username',
                'content'=>function($data){
                    return Html::a($data['username'],'/users/view?id='.$data['id']);
                }
            ],
            [
                'attribute'=>'parent_id',
                'content'=>function($data){
                    $parent = \common\models\User::findOne($data['parent_id'] );
                    return Html::a($parent['username'],'/users/view?id='.$parent['id']);
                }
            ],
            'fio',
            'phone',

            [
                'attribute'=>'Матрица',
                'content'=>function($data){
                    $res = "";

                    if($data['newmatrix'] == 1){
                        $level = \common\models\MatrixRef::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                        $res = $res." Shanyrak+";
                    }else{
                        $res = "Не активирован";
                    }

                    return $res;
                }
            ],
            'w_balans',

            //'order',
            [
                'attribute'=>'time_personal',
                'content'=>function($data){
                    if(!empty($data['time_personal'])){
                        return date("d.m.Y H:i",$data['time_personal']);
                    }
                }
            ],
            //'status',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ];

?>

<div class="site-index">
    <div class="row">

        <div class="col-6">
            <div class="row">
                <div class="col-12">
                <form id="w0" action="/users/closes" method="get">
                    <div class="row">

                    <div class="col-4">
                        <div class="form-group field-activities-username">
                            <label class="control-label" for="activities-username">Площадка</label>
                            <select name="platform" id="platform_id" class="form-control">
                                <option value="">Все уровни</option>
                                <option value="11" <?if($platform == 11){?>selected<?}?>>Не активировано</option>
                                <option value="1" <?if($platform == 1){?>selected<?}?>>Уровень 1</option>
                                <option value="2" <?if($platform == 2){?>selected<?}?>>Уровень 2</option>
                                <option value="3" <?if($platform == 3){?>selected<?}?>>Уровень 3</option>
                                <option value="4" <?if($platform == 4){?>selected<?}?>>Уровень 4</option>
                                <option value="5" <?if($platform == 5){?>selected<?}?>>Уровень 5</option>
                                <option value="6" <?if($platform == 6){?>selected<?}?>>Уровень 6</option>
                            </select>
                            <div class="help-block"><?=$error?></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group field-activities-username">
                            <label class="control-label" for="activities-username">Время регистрации от</label>
                            <input class="form-control" type="date" name="from" value="<?=$from?>">
                            <div class="help-block"><?=$error?></div>
                        </div>
                    </div>
                        <div class="col-4">
                            <div class="form-group field-activities-username">
                                <label class="control-label" for="activities-username">До</label>
                                <input class="form-control" type="date" name="to" value="<?=$to?>">
                                <div class="help-block"><?=$error?></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Поиск</button>    </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="user-index">
	<?= ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns
    ]);?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns
    ]); ?>
</div>
