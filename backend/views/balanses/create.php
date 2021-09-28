<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Balanses */

$this->title = 'Добавить счет';
$this->params['breadcrumbs'][] = ['label' => 'Балансы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="balanses-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
