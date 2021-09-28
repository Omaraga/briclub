<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Courses */

$this->title = 'Изменить курс: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="courses-update">

    <?= $this->render('_form', [
        'model' => $model,
        //'events' => $events,
    ]) ?>

</div>
