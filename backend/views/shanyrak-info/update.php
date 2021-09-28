<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShanyrakInfo */

$this->title = 'Изменить информацию: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Shanyrak', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shanyrak-info-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
