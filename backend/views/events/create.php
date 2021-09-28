<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Actions */

$this->title = 'Добавить мероприятие';
$this->params['breadcrumbs'][] = ['label' => 'Мероприятия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="actions-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
