<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShanyrakUser */

$this->title = 'Create Shanyrak User';
$this->params['breadcrumbs'][] = ['label' => 'Shanyrak Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shanyrak-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
