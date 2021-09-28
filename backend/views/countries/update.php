<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Countries */

$this->title = 'Изменить страну: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Страны', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="countries-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
