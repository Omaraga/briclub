<?php

use common\models\User;
use common\models\Beds;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->fio;
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$shoulder1 = \common\models\Referals::find()->where(['parent_id'=>$model->id,'shoulder'=>1])->all();
$shoulder2 = \common\models\Referals::find()->where(['parent_id'=>$model->id,'shoulder'=>2])->all();

$proms = \common\models\PromotionNew::find()->all();
?>
<div class="user-view">
    <? foreach ($proms as $prom) {?>
        <h3><?=$prom['title']?></h3>
        <div class="row">
            <div class="col-sm-6">
                <h4>Левое плечо</h4>
                <?
                $i1 = 0;
                foreach ($shoulder1 as $item1) {
                    $user1 = User::findOne($item1['user_id']);
                    ?>
                    <?if($user1['newmatrix'] == 1){
                        if($user1['time_personal'] >= $prom['start'] and $user1['time_personal'] < $prom['end']){
                            $i1++;
                            ?>
                        <p class="col-sm-12">
                            <p class="col-sm-6"><?=$user1['username']?> (Personal) </p>
                            <p class="col-sm-6"><?=date('d.m.Y H:i',$user1['time_personal'])?></p>
                        </p>
                    <?}}?>
                    <?if($user1['global'] == 1){
                        if($user1['time_global'] >= $prom['start'] and $user1['time_global'] < $prom['end']){
                            $i1++;
                            ?>
                        <p class="col-sm-12">
                            <p class="col-sm-6"><?=$user1['username']?> (Global)</p>
                            <p class="col-sm-6"><?=date('d.m.Y H:i',$user1['time_global'])?></p>
                            </p>
                    <?}}?>
                <?}?>
                <h4>Всего: <?=$i1?> контрактов</h4>
            </div>
            <div class="col-sm-6">
                <h4>Правое плечо</h4>
                <?
                $i2 = 0;
                foreach ($shoulder2 as $item2) {
                    $user2 = User::findOne($item2['user_id']);
                    ?>
                    <?if($user2['newmatrix'] == 1){
                        if($user2['time_personal'] >= $prom['start'] and $user2['time_personal'] < $prom['end']){
                            $i2++;
                            ?>
                        <p class="col-sm-12">
                            <p class="col-sm-6"><?=$user2['username']?> (Personal)</p>
                            <p class="col-sm-6"><?=date('d.m.Y H:i',$user2['time_personal'])?></p>
                            </p>
                    <?}}?>
                    <?if($user2['global'] == 1){
                        if($user2['time_global'] >= $prom['start'] and $user2['time_global'] < $prom['end']){
                            $i2++;
                            ?>
                        <p class="col-sm-12">
                            <p class="col-sm-6"><?=$user2['username']?> (Global)</p>
                            <p class="col-sm-6"><?=date('d.m.Y H:i',$user2['time_global'])?></p>
                         </p>
                    <?}}?>
                <?}?>
                <h4>Всего: <?=$i2?> контрактов</h4>
            </div>
        </div>
        <hr>
    <?}?>

</div>
