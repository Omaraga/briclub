<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Promotion */


$parents = \common\models\Parents::find()->select(['parent_id'])->distinct('parent_id')->where(['>=','time',$model->start])->andWhere(['<','time',$model->end])->all();


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
        foreach ($parents as $parent) {
            $parent_db = \common\models\User::findOne($parent['parent_id']);
            $children = \common\models\User::find()->where(['parent_id'=>$parent_db['id']])->orderBy('order asc')->all();
            $part1 = array();
            $part2 = array();
            $all_children1 = array();
            $all_children2 = array();
            $k = 0;
            foreach ($children as $child) {
                $k++;
                if($k%2 == 1){
                    $part1[] = $child['id'];
                    $all_children1 = array_merge($all_children1,\common\models\Parents::find()->where(['parent_id'=>$child['id']])->andWhere(['>=','time',$model->start])->andWhere(['<','time',$model->end])->all());
                }else{
                    $part2[] = $child['id'];
                    $all_children2 = array_merge($all_children2,\common\models\Parents::find()->where(['parent_id'=>$child['id']])->andWhere(['>=','time',$model->start])->andWhere(['<','time',$model->end])->all());
                }
            }
            $own_children = \common\models\Parents::find()->where(['parent_id'=>$parent_db['id'],'level'=>1])->andWhere(['>=','time',$model->start])->andWhere(['<','time',$model->end])->all();
            $j = 0;
            foreach ($own_children as $child2) {
                $j++;
                if($j%2 == 1){
                    $all_children1[] = $child2;
                }else{
                    $all_children2[] = $child2;
                }

            }
            $i++;
            ?>
            <tr data-key="36">
                <td><?=$i?></td>
                <td><a href="/users/view?id=<?=$parent_db['id']?>"><?=$parent_db['username']?></a></td>
                <td>
                    <?=count($all_children1)+count($all_children2)?>
                </td>
                <td>
                    <?=count($all_children1)?>
                </td>
                <td>
                    <?=count($all_children2)?>
                </td>
                <td>
                    <?
                        $tarifs = \common\models\BonusTarifs::find()->all();
                        foreach ($tarifs as $tarif) {
                            if(count($all_children1)>=$tarif['children'] and count($all_children2)>=$tarif['children']){
                                $user_bonus = \common\models\UserPromotions::find()->where(['user_id'=>$parent_db['id'],'pr_id'=>$model->id])->orderBy('id desc')->one();
                                if(empty($user_bonus)){
                                    $user_bonus = new \common\models\UserPromotions();
                                }
                                $user_bonus->user_id = $parent_db['id'];
                                $user_bonus->tarif_id = $tarif['id'];
                                $user_bonus->pr_id = $model->id;
                                $user_bonus->save();
                            }
                        }
                        $user_pr = \common\models\UserPromotions::find()->where(['user_id'=>$parent_db['id'],'pr_id'=>$model->id])->orderBy('id desc')->one();
                        if(!empty($user_pr)){
                            echo \common\models\BonusTarifs::findOne($user_pr['tarif_id'])['title'];
                        }else{
                            echo "Нет уровня";
                        }

                    ?>
                </td>
            </tr>
        <?}?>
        </tbody>
    </table>
</div>