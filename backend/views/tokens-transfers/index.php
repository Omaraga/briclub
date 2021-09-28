<?php


use common\models\User;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список переводов токенов';
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Отправитель',
                'content' => function ($data) {
                    $user = User::findOne(['id' => $data['user_id']]);
                    return Html::a($user['username'], '/users/view?id=' . $user['id']);
                }
            ],
            [
                'attribute' => 'Получатель',
                'content' => function ($data) {
                    $user = User::findOne(['id' => $data['user2_id']]);
                    return Html::a($user['username'], '/users/view?id=' . $user['id']);
                }
            ],
            [
                'attribute' => 'Количество токенов',
                'content' => function ($data) {
                    return $data['sum'];
                }
            ],
			[
                'attribute' => 'Комиссия',
                'content' => function ($data) {
                    return $data['fee'];
                }
            ],
            [
                'attribute' => 'Время',
                'content' => function ($data) {
                    return date("d.m.Y H:i", $data['time']);
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>
