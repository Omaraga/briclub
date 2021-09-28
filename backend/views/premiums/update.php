<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Premiums */

$this->title = 'Update Premiums: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Premiums', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="premiums-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
