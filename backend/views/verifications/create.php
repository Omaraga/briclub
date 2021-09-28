<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Verifications */

$this->title = 'Create Verifications';
$this->params['breadcrumbs'][] = ['label' => 'Verifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verifications-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
