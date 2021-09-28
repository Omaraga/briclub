<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShanyrakInfo */

$this->title = 'Create Shanyrak Info';
$this->params['breadcrumbs'][] = ['label' => 'Shanyrak Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shanyrak-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
