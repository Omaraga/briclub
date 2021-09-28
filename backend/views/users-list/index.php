<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
$columns = [
    ['class' => 'yii\grid\SerialColumn'],

    [
        'attribute'=>'username',
        'content'=>function($data){
            return Html::a($data['username'],'/users/view?id='.$data['id']);
        },
        'format' => 'raw'
    ],
    'email:email',
    'fio',
    'phone',
    [
        'attribute'=>'parentName',
        'label' => 'Спонсор',
        'value'=>'parent.username',
        'content'=>function($data){
            $parent = \common\models\User::findOne($data['parent_id']);
            if ($parent){
                return Html::a($parent['username'],'/users/view?id='.$data['parent_id']);
            }else{
                return '';
            }

        },
        'format' => 'raw'
    ],
    'w_balans',
    [
        'attribute'=>'Токены',
        'content'=>function($data){
            $balans = \common\models\Tokens::find()->where(['user_id'=>$data['id']])->sum('balans');
            return $balans;
        }
    ],
    ['attribute' => 'countryName','label' => 'Страна','value'=>'country.title'],
    [
        'attribute'=>'Матрица',
        'content'=>function($data){
            $res = "";

            if($data['newmatrix'] == 1){
                $level = \common\models\MatrixRef::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                $res = "Уровень ".$level['platform_id'];
            }else{
                $res = "Не активирован";
            }

            return $res;
        }
    ],
    ['attribute' => 'time_personal', 'format' => ['date', 'php:d.m.Y H:i']],
    ['attribute' => 'created_at', 'format' => ['date', 'php:d.m.Y H:i']],

];
echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns
]);

$sum = \common\models\User::find()->sum('w_balans');
?>
<div class="users-list-index">

    <p>На балансе пользователей всего: <?=$sum?> US</p>

    <?php Pjax::begin(); ?>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
