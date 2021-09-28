<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Audios */

$this->title = 'Изменить аудио: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['/library']];
$this->params['breadcrumbs'][] = ['label' => 'Книга: '.$lib_id, 'url' => ['/library/view?id='.$lib_id]];
$this->params['breadcrumbs'][] = ['label' => 'Аудио', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="audios-update">

    <?= $this->render('_form', [
        'model' => $model,
        'lib_id' => $lib_id
    ]) ?>

</div>
