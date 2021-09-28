<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Documents */

$this->title = 'Изменить: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="documents-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
