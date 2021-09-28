<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShanyrakBeds */

$this->title = 'Create Shanyrak Beds';
$this->params['breadcrumbs'][] = ['label' => 'Shanyrak Beds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shanyrak-beds-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
