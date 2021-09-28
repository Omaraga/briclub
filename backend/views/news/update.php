<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Actions */

$this->title = 'Изменить новость: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="actions-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
