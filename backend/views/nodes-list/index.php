<?php


use common\models\User;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список нод';
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Логин',
                'content' => function ($data) {
                    $user = User::findOne(['id' => $data['user_id']]);
                    return Html::a($user['username'], '/users/view?id=' . $user['id']);
                }
            ],
            [
                'attribute' => 'Токенов в ноде',
                'content' => function ($data) {
                    return $data['tokens'];
                }
            ],
            [
                'attribute' => 'Тип ноды',
                'content' => function ($data) {
                    $node_type = \common\models\TokenNodeTypes::findOne(['id' => $data['type']])['title'];
                    return $node_type;
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>
