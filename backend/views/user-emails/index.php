<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Emails';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-emails-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User Emails', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'old_email:email',
            'new_email:email',
            'username',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
