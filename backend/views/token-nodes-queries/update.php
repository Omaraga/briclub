<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TokenNodesQueries */

$this->title = 'Принятие заявки на ноду';
//$this->params['breadcrumbs'][] = ['label' => 'Token Nodes Queries', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="token-nodes-queries-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
