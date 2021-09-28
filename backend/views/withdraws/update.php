<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Withdraws */

$this->title = 'Изменить статус: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Вывод', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="withdraws-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
