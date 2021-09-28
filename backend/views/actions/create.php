<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Actions */

$this->title = 'Добавить новость';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="actions-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
