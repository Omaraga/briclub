<?php

/* @var $this yii\web\View */
/* @var $first_profit int */
/* @var $fourth_profit int */
/* @var $fifth_profit int */
/* @var $sixth_profit int */
/* @var $all_profit int */

use common\models\User;

$this->title = 'Заработок компании со столов';
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = $this->title;
$users = User::find()->where(['activ'=>1])->all();
?>

<div class="user-index">
    <p>Общий заработок компании со всех  столов: <?=$all_profit?></p>
    <p>Заработок компании с 1 стола: <?=$first_profit?></p>
    <p>Заработок компании с 4 стола: <?=$fourth_profit?></p>
    <p>Заработок компании с 5 стола: <?=$fifth_profit?></p>
    <p>Заработок компании с 6 стола: <?=$sixth_profit?></p>
</div>
