<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PromoCodes */

$this->title = 'Create Promo Codes';
$this->params['breadcrumbs'][] = ['label' => 'Promo Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promo-codes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
