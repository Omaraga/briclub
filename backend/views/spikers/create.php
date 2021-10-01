<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Spikers */

$this->title = 'Create Spikers';
$this->params['breadcrumbs'][] = ['label' => 'Spikers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spikers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
