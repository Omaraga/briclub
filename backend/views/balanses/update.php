<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Balanses */

$this->title = 'Изменить счет: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Балансы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="balanses-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
