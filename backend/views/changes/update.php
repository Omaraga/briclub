<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Changes */

$this->title = 'Изменить пару: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Курсы валют', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Измеинть';
?>
<div class="changes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
