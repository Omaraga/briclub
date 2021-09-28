<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PromoCodes */

$this->title = 'Update Promo Codes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Promo Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="promo-codes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
