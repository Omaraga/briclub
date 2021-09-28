<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShanyrakBeds */

$this->title = 'Изменить заявку: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shanyrak заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="shanyrak-beds-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
