<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ContentGroups */

$this->title = 'Create Content Groups';
$this->params['breadcrumbs'][] = ['label' => 'Content Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-groups-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
