<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Exceptions */

$this->title = 'Добавить исключение';
$this->params['breadcrumbs'][] = ['label' => 'Исключения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exceptions-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
