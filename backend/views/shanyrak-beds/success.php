<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShanyrakBeds */

$this->title = 'Одобрить заявку: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shanyrak заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Одобрить';
?>
<div class="shanyrak-beds-update">

    <?= $this->render('_success', [
        'model' => $model,
    ]) ?>

</div>
