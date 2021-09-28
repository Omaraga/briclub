<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Promotion */


$parents1 = \common\models\Referals::find()->select(['parent_id'])->distinct('parent_id')->where(['>=','time_personal',$model->start,])->andWhere(['<','time_personal',$model->end])->orderBy('id asc')->all();
$parents2 = \common\models\Referals::find()->select(['parent_id'])->distinct('parent_id')->where(['>=','time_global',$model->start,])->andWhere(['<','time_global',$model->end])->orderBy('id asc')->all();

$parents = array_merge($parents1,$parents2);
foreach ($parents as $parent) {
    $parent_db = \common\models\User::findOne($parent['parent_id']);

    $all_children1_personal = \common\models\Referals::find()->where(['parent_id'=>$parent['parent_id'],'shoulder'=>1])->andWhere(['>=','time_personal',$model->start])->andWhere(['<','time_personal',$model->end])->all();
    $all_children1_global = \common\models\Referals::find()->where(['parent_id'=>$parent['parent_id'],'shoulder'=>1])->andWhere(['>=','time_global',$model->start])->andWhere(['<','time_global',$model->end])->all();
    $all_children2_personal = \common\models\Referals::find()->where(['parent_id'=>$parent['parent_id'],'shoulder'=>2])->andWhere(['>=','time_personal',$model->start])->andWhere(['<','time_personal',$model->end])->all();
    $all_children2_global = \common\models\Referals::find()->where(['parent_id'=>$parent['parent_id'],'shoulder'=>2])->andWhere(['>=','time_global',$model->start])->andWhere(['<','time_global',$model->end])->all();

    $all = (count($all_children1_personal) + count($all_children1_global)) + (count($all_children2_personal) + count($all_children2_global));
    $shoulder1 = (count($all_children1_personal) + count($all_children1_global));
    $shoulder2 = (count($all_children2_personal) + count($all_children2_global));
    $tarifs = \common\models\BonusTarifsNew::find()->all();
    foreach ($tarifs as $tarif) {
        if($shoulder1>=$tarif['children'] and $shoulder2>=$tarif['children']){
            $user_bonus = \common\models\UserPromotionsNew::find()->where(['user_id'=>$parent_db['id'],'pr_id'=>$model->id])->orderBy('id desc')->one();
            if(empty($user_bonus)){
                $user_bonus = new \common\models\UserPromotionsNew();
            }
            $user_bonus->user_id = $parent_db['id'];
            $user_bonus->tarif_id = $tarif['id'];
            $user_bonus->pr_id = $model->id;
            $user_bonus->all = $all;
            $user_bonus->shoulder1 = $shoulder1;
            $user_bonus->shoulder2 = $shoulder2;
            $user_bonus->save();
        }
    }
}
$prom_users = \common\models\UserPromotionsNew::find()->where(['pr_id'=>$model->id])->orderBy('tarif_id desc')->all();
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Бинар', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
<div class="promotion-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'label'=>'Начало',
                'value'=>function($data){
                    return date('d.m.Y H:i',$data['start']);
                }
            ],
            [
                'label'=>'Конец',
                'value'=>function($data){
                    return date('d.m.Y H:i',$data['end']);
                }
            ],
            [
                'label'=>'Статус',
                'value'=>function($data){
                    $status = \common\models\BonusStatuses::findOne($data['status']);
                    return '<span class="badge badge-'.$status['color'].'">'.$status['title'].'</span>';
                },
                'format' => 'raw'
            ],
        ],
    ]) ?>
    <p>
        <? if($model->end < time()){
            if(User::isAccess('superadmin')){
                echo Html::a('Завершить', ['end', 'id' => $model->id], ['class' => 'btn btn-primary']);
            }

        } ?>

    </p>
</div>
<div class="beds-index">
    <h3>Участники</h3>
    <table class="table table-striped table-bordered"><thead>
        <tr>
            <th>#</th>
            <th>
                <a data-sort="title">Логин</a>
            </th>
            <th>
                <a  data-sort="email">Всего в структуре</a>
            </th>
            <th>
                <a data-sort="email">Левое плечо</a>
            </th>
            <th>
                <a data-sort="tel">Правое плечо</a>
            </th>
            <th>
                <a data-sort="tel">Уровень</a>
            </th>
        </tr>
        </thead>
        <tbody>
        <?
        $i = 0;
        foreach ($prom_users as $parent) {
            $parent_db = \common\models\User::findOne($parent['user_id']);
            $i++;
            ?>
            <tr data-key="36">
                <td><?=$i?></td>
                <td><a href="/users/view?id=<?=$parent_db['id']?>"><?=$parent_db['username']?></a></td>
                <td>
                    <?=$parent['all']?>
                </td>
                <td>
                    <?=$parent['shoulder1']?>
                </td>
                <td>
                    <?=$parent['shoulder2']?>
                </td>
                <td>
                    <?
                    echo \common\models\BonusTarifsNew::findOne($parent['tarif_id'])['title'];
                    ?>
                </td>
            </tr>
        <?}?>
        </tbody>
    </table>
</div>