<?php

use common\models\User;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страны пользователей';
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
$countries = User::find()->select('country_id')->distinct()->all();
?>

<div class="site-index">
    <div class="row">

        <div class="col-6">
            <div class="row">
                <div class="col-12">
                <form id="w0" action="/users/countries" method="get">
                    <div class="row">

                    <div class="col-4">
                        <div class="form-group field-activities-username">
                            <label class="control-label" for="activities-username">Страна</label>
                            <select name="country" id="countyr" class="form-control">
                                <option value="1111">Все страны</option>
                                <? foreach ($countries as $countr) {
                                    if($countr['country_id'] == null) continue;
                                    $user_count = User::find()->where(['country_id'=>$countr['country_id']])->count();
                                    ?>
                                    <option value="<?=$countr['country_id']?>" <?if($country == $countr['country_id']){?>selected<?}?>><?=\common\models\Countries::findOne($countr['country_id'])['title']?> (<?=$user_count?>)</option>
                                <?}?>
                            </select>
                            <div class="help-block"><?=$error?></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group field-activities-username">
                            <label class="control-label" for="activities-username">Время активации от</label>
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
        <div class="col-12">
            <h3>
            <?
            if(empty($from) and empty($to)){
                echo "За все время ";
            }else{
                if(!empty($from) and empty($to)){
                    echo "С ".$from;
                }
                if(!empty($to) and empty($from)){
                    echo "До ".$to;
                }
                if(!empty($from) and !empty($to)){
                    echo "С ".$from." До ".$to;
                }
            }
            if(!empty($country) and $country !=1111){
                echo " с страны ".\common\models\Countries::findOne($country)['title'];
            }
            ?>
             было активировано <?=$dataProvider->totalCount?> аккаунтов</h3>
        </div>
    </div>

</div>
<div class="user-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
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
                'attribute'=>'country_id',
                'content'=>function($data){
                    $country = \common\models\Countries::findOne($data['country_id'] );
                    return $country['title'];
                }
            ],

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
                'attribute'=>'created_at',
                'content'=>function($data){
                    return date("d.m.Y H:i",$data['created_at']);
                }
            ],
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
        ],
    ]); ?>
</div>
