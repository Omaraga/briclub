<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Actions */

$this->title = 'Пополнить';
$this->params['breadcrumbs'][] = ['label' => 'Пополнения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="actions-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
