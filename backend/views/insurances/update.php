<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Insurances */

$this->title = 'Изменить заяку ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки на страхование', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="insurances-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
