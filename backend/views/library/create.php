<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Documents */

$this->title = 'Добавить книгу';
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documents-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
