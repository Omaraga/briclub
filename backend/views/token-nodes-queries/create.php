<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TokenNodesQueries */

$this->title = 'Create Token Nodes Queries';
$this->params['breadcrumbs'][] = ['label' => 'Token Nodes Queries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-nodes-queries-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
